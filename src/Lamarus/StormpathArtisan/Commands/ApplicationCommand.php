<?php namespace Lamarus\StormpathArtisan\Commands;

use Lamarus\StormpathArtisan\Stormpath\Application;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ApplicationCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'stormpath:application';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Work with stormpath applications';

	/**
     * Application instance
     *
     * @var Lamarus\StormpathArtisan\Stormpath\Application
     */
    protected $application;

    /**
     * Application the user wants to interact with
     *
     * @var int
     */
    protected $applicationToInteractWith;
    /**
     * Table Helper Set
     */
    protected $table;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Application $application)
	{
		parent::__construct();
		$this->application = $application;
		
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->info('Here are your current applications');

		$this->table = $this->getHelperSet()->get('table');

		$this->application->getApplicationsTable($this->table);

		$this->table->addRow(array('c','Create a new Application'));

		$this->table->render($this->getOutput());

		$this->applicationToInteractWith = $this->ask('Which Application do you want to interact with?');

		if($this->applicationToInteractWith == 'c') {
			// We want to create a new application;
			$this->createApplication();
			return;
		}

		if(array_key_exists($this->applicationToInteractWith, $this->application->getApplicationsArray())) {
			// We want to interact with an applicaiton;
			$this->whatDoYouWantToDo();
		}


	}

	public function whatDoYouWantToDo()	{
		$whatToDo = $this->ask('What do you want to do? [enable(e)|disable(d)|modify(m)|remove(r)]');

		$application = $this->application->getApplicationsArray()[$this->applicationToInteractWith];

		switch($whatToDo) {
			case 'enable' :
			case 'e' :
				$this->enableApplication($application['id']);
			break;
			case 'disable' :
			case 'd':
				$this->disableApplication($application['id']);
			break;
			case 'remove' :
			case 'r':
				$this->deleteApplication($application['id']);
			break;
			case 'modify' :
			case 'm' :
				$this->modifyApplication($application);
			break;
			default:
				$this->error('I dont understand!');
				$this->whatDoYouWantToDo();
			break;
		}
	}

	public function createApplication() {
		$appName = $this->ask('Application Name');
		$appDescription = $this->ask('Applicaiton Description');
		$enable = $this->confirm('Enable Applicaiton [yes|no]', 'yes');

		if($enable == 'yes') {
			$enabled = 'enabled';
		} else {
			$enabled = 'disabled';
		}

		$createApplication = $this->application->createApplication(array('appName'=>$appName,'appDescription'=>$appDescription,'enabled'=>$enabled));
		$this->$createApplication['messageType']($createApplication['message']);	
	}

	public function enableApplication($applicationID) {
		$enable = $this->application->enableApplication($applicationID);
		$this->$enable['messageType']($enable['message']);
	}

	public function disableApplication($applicationID) {
		$disable = $this->application->disableApplication($applicationID);
		$this->$disable['messageType']($disable['message']);
	}

	public function deleteApplication($applicationID) {
		$delete = $this->application->deleteApplication($applicationID);
		$this->$delete['messageType']($delete['message']);
	}

	public function modifyApplication($application) {
		$appName = $this->ask("Application Name [{$application['name']}]",$application['name']);
		$appDescription = $this->ask("Application Description [{$application['description']}]",$application['description']);

		$modifyApplication = $this->application->modifyApplication(array('id'=>$application['id'],'appName'=>$appName,'appDescription'=>$appDescription));
		$this->$modifyApplication['messageType']($modifyApplication['message']);	
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('create', InputArgument::REQUIRED, 'Create an Application'),
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
			// array('create', 'c', InputOption::VALUE_OPTIONAL, 'Create an application', true),
		);
	}

}