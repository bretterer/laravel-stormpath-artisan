<?php namespace Lamarus\StormpathArtisan\Commands;

use Lamarus\StormpathArtisan\Stormpath\Directory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DirectoryCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'stormpath:directory';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Directory Commands for Stormpath API';

	/**
     * Directory instance
     *
     * @var Lamarus\StormpathArtisan\Stormpath\Directory
     */
    protected $directory;

    /**
     * Directory the user wants to interact with
     *
     * @var int
     */
    protected $directoryToInteractWith;

    /**
     * Table Helper Set
     */
    protected $table;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Directory $directory)
	{
		parent::__construct();
		$this->directory = $directory;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->info('Here are your current Directories');

		$this->table = $this->getHelperSet()->get('table');

		$this->directory->getDirectoryTable($this->table);

		$this->table->addRow(array('c','Create a new Directory'));

		$this->table->render($this->getOutput());

		$this->directoryToInteractWith = $this->ask('Which Directory do you want to interact with?');

		if($this->directoryToInteractWith == 'c') {
			// We want to create a new directory;
			$this->createDirectory();
			return;
		}

		if(array_key_exists($this->directoryToInteractWith, $this->directory->getDirectoriesArray())) {
			// We want to interact with an directory;
			$this->whatDoYouWantToDo();
		}
	}

	public function whatDoYouWantToDo()	{
		$whatToDo = $this->ask('What do you want to do? [enable(e)|disable(d)|modify(m)|remove(r)]');

		$directory = $this->directory->getdirectoriesArray()[$this->directoryToInteractWith];

		switch($whatToDo) {
			case 'enable' :
			case 'e' :
				$this->enableDirectory($directory['id']);
			break;
			case 'disable' :
			case 'd':
				$this->disableDirectory($directory['id']);
			break;
			case 'remove' :
			case 'r':
				$this->deleteDirectory($directory['id']);
			break;
			case 'modify' :
			case 'm' :
				$this->modifyDirectory($directory);
			break;
			default:
				$this->error('I dont understand!');
				$this->whatDoYouWantToDo();
			break;
		}
	}

	public function createDirectory() {
		$directoryName = $this->ask('Directory Name');
		$directoryDescription = $this->ask('Directory Description');
		$enable = $this->confirm('Enable Directory [yes|no]', 'yes');

		if($enable == 'yes') {
			$enabled = 'enabled';
		} else {
			$enabled = 'disabled';
		}

		$createdirectory = $this->directory->createdirectory(array('directoryName'=>$directoryName,'directoryDescription'=>$directoryDescription,'enabled'=>$enabled));
		$this->$createdirectory['messageType']($createdirectory['message']);	
	}

	public function enableDirectory($directoryID) {
		$enable = $this->directory->enableDirectory($directoryID);
		$this->$enable['messageType']($enable['message']);
	}

	public function disableDirectory($directoryID) {
		$disable = $this->directory->disableDirectory($directoryID);
		$this->$disable['messageType']($disable['message']);
	}

	public function deleteDirectory($directoryID) {
		$delete = $this->directory->deleteDirectory($directoryID);
		$this->$delete['messageType']($delete['message']);
	}

	public function modifyDirectory($directory) {
		$directoryName = $this->ask("Directory Name [{$directory['name']}]",$directory['name']);
		$directoryDescription = $this->ask("Directory Description [{$directory['description']}]",$directory['description']);

		$modifydirectory = $this->directory->modifyDirectory(array('id'=>$directory['id'],'directoryName'=>$directoryName,'directoryDescription'=>$directoryDescription));
		$this->$modifydirectory['messageType']($modifydirectory['message']);	
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('testarg', InputArgument::OPTIONAL, 'What do you want to do'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}