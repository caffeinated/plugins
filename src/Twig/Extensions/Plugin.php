<?php
namespace Caffeinated\Plugins\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

class Plugin extends Twig_Extension
{
	/**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
	public function getName()
	{
		return 'Caffeinated_Plugins_Extension_Plugin';
	}

	/**
	 * Returns a list of global functions to add to the existing list.
	 *
	 * @return array An array of global functions
	 */
	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('*', function ($name) {
					$arguments = array_slice(func_get_args(), 1);

					return \Plugin::call($name, $arguments);
				}, ['is_safe' => ['html']]
			),
		];
	}
}
