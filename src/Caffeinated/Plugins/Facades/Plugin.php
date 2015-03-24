<?php
namespace Caffeinated\Plugins\Facades;

use Illuminate\Support\Facades\Facade;

class Plugin extends Facade
{
	/**
	 * Get the registered name of the plugin.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'plugins';
	}
}
