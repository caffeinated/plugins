<?php
namespace Caffeinated\Plugins;

use Illuminate\Support\ServiceProvider;

class PluginsServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return null
	 */
	public function register()
	{
		$this->registerServices();

		$this->configureSapling();
	}

	/**
	 * Register the package services.
	 *
	 * @return null
	 */
	protected function registerServices()
	{
		$this->app->bindShared('plugins', function($app) {
			$blade = $app['view']->getEngineResolver()->resolve('blade')->getCompiler();

			return new Plugins($app, $blade);
		});
	}

	/**
	 * Configure Sapling
	 *
	 * Configures Sapling (Twig) extensions if the Sapling package
	 * is found to be installed.
	 *
	 * @return void
	 */
	protected function configureSapling()
	{
		if ($this->app['config']->has('sapling')) {
			$this->app['config']->push(
				'sapling.extensions',
				'Caffeinated\Plugins\Twig\Extensions\Plugin'
			);
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['plugins'];
	}
}
