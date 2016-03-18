<?php
namespace Caffeinated\Plugins;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;

class Plugins
{
	/**
	 * @var  Container
	 */
	protected $container;

	/**
	 * @var  BladeCompiler
	 */
	protected $blade;

	/**
	 * @var  array
	 */
	protected $groups = array();

	/**
	 * @var  array
	 */
	protected $plugins = array();

	/**
	 * Constructor method.
	 *
	 * @param  Container      $container
	 * @param  BladeCompiler  $blade
	 */
	public function __construct(Container $container, BladeCompiler $blade)
	{
		$this->container = $container;
		$this->blade     = $blade;
	}

	/**
	 * Register a new plugin.
	 *
	 * @param   string           $name
	 * @param   string|callable  $callback
	 * @return  void
	 */
	public function register($name, $callback)
	{
		$this->plugins[$name] = $callback;

		$this->registerTag($name);
	}

	/**
	 * Register Blade syntax for a specific plugin.
	 *
	 * @param   string  $method
	 * @return  void
	 */
	protected function registerTag($method)
	{
		$this->blade->directive($method, function($expression) use ($method) {
			return '<?php echo '.$method.$expression.'; ?>';
		});
	}

	/**
	 * Determine whether a plugin exists or not.
	 *
	 * @param   string  $name
	 * @return  bool
	 */
	public function exists($name)
	{
		return array_key_exists($name, $this->plugins);
	}

	/**
	 * Call a specific plugin.
	 *
	 * @param   string  $name
	 * @param   array   $parameters
	 * @return  mixed
	 */
	public function call($name, array $parameters = array())
	{
		if ($this->groupExists($name)) return $this->callGroup($name, $parameters);

		if ($this->exists($name)) {
			$callback = $this->plugins[$name];

			return $this->getCallback($callback, $parameters);
		}

		return null;
	}

	/**
	 * Get a callback from a specific plugin.
	 *
	 * @param   mixed  $callback
	 * @param   array  $parameters
	 * @return  mixed
	 */
	protected function getCallback($callback, array $parameters)
	{
		if ($callback instanceof Closure) {
			return $this->createCallableCallback($callback, $parameters);
		} elseif (is_string($callback)) {
			return $this->createStringCallback($callback, $parameters);
		} else {
			return null;
		}
	}

	/**
	 * Get a result from a string callback.
	 *
	 * @param   string  $callback
	 * @param   array   $parameters
	 * @return  mixed
	 */
	protected function createStringCallback($callback, array $parameters)
	{
		if (function_exists($callback)) {
			return $this->createCallableCallback($callback, $parameters);
		} else {
			return $this->createClassCallback($callback, $parameters);
		}
	}

	/**
	 * Get a result from a callable callback.
	 *
	 * @param   callable  $callback
	 * @param   array     $parameters
	 * @return  mixed
	 */
	protected function createCallableCallback($callback, array $parameters)
	{
		return call_user_func_array($callback, $parameters);
	}

	/**
	 * Get a result from a class callback.
	 *
	 * @param   callable  $callback
	 * @param   array     $parameters
	 * @return  mixed
	 */
	protected function createClassCallback($callback, array $parameters)
	{
		list($className, $method) = Str::parseCallback($callback, 'run');

		$instance = $this->container->make($className);

		$callable = array($instance, $method);

		return $this->createCallableCallback($callable, $parameters);
	}

	/**
	 * Create a new plugin group.
	 *
	 * @param   string  $name
	 * @param   array   $plugins
	 * @return  void
	 */
	public function group($name, array $plugins)
	{
		$this->groups[$name] = $plugins;

		$this->registerTag($name);
	}

	/**
	 * Determine whether a group of plugins exists or not
	 *
	 * @param   string  $name
	 * @return  bool
	 */
	public function groupExists($name)
	{
		return array_key_exists($name, $this->groups);
	}

	/**
	 * Call a specific group of plugins.
	 *
	 * @param   string  $name
	 * @param   array   $parameters
	 * @return  null|string
	 */
	public function callGroup($name, $parameters = array())
	{
		if (! $this->groupExists($name)) return null;

		$result = '';

		foreach ($this->groups[$name] as $key => $plugin) {
			$result .= $this->call($plugin, array_get($parameters, $key, array()));
		}

		return $result;
	}

	/**
	 * Handle magic __call methods against the class.
	 *
	 * @param   string  $method
	 * @param   array   $parameters
	 * @return  mixed
	 */
	public function __call($method, $parameters = array())
	{
		return $this->call($method, $parameters);
	}
}
