<?php namespace KDuma\Permissions;

use Illuminate\Support\ServiceProvider;
use KDuma\Permissions\Helpers\PermissionsAdderHelper;
use KDuma\Permissions\Helpers\PermissionsTemplateHelper;

class PermissionsServiceProvider extends ServiceProvider {

//	/**
//	 * Indicates if loading of the provider is deferred.
//	 *
//	 * @var bool
//	 */
//	protected $defer = true;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
//		$this->publishes([
//			__DIR__.'/Config/permissions.php' => config_path('permissions.php'),
//		]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__.'/Config/permissions.php', 'permissions'
		);
		$this->app->singleton('permissions.templatehelper', function()
		{
			return new PermissionsTemplateHelper();
		});
		$this->app->singleton('permissions.adderhelper', function()
		{
			return new PermissionsAdderHelper();
		});
//
//		$this->commands('command.config.env');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
//		return array(
//			'command.config.env'
//		);
	}

}
