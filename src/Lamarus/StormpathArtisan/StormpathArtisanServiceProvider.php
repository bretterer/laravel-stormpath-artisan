<?php namespace Lamarus\StormpathArtisan;

use Lamarus\StormpathArtisan\Commands;
use Lamarus\StormpathArtisan\Stormpath;
use Illuminate\Support\ServiceProvider;

class StormpathArtisanServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('lamarus/stormpath-artisan');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerApplicationCommand();
		$this->registerDirectoryCommand();

		$this->commands(
			'stormpath.application',
			'stormpath.directory'
		);
	}

	/**
	 * Register application command
	 *
	 * @return Commands\ApplicationCommand
	 */
	protected function registerApplicationCommand()
	{
		$this->app['stormpath.application'] = $this->app->share(function($app) {
			$application = new Stormpath\Application;

			return new Commands\ApplicationCommand($application);
		});
	}

	/**
	 * Register directory command
	 *
	 * @return Commands\DirectoryCommand
	 */
	protected function registerDirectoryCommand()
	{
		$this->app['stormpath.directory'] = $this->app->share(function($app) {
			$directory = new Stormpath\Directory;

			return new Commands\DirectoryCommand($directory);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}