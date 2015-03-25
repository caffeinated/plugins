Caffeinated Plugins
===================
Abstraction layer between Blade/Twig to allow the means to "plug in" data through a consistent interface.

Quick Usage
-----------
Build your plugin:
**app\Plugins\YourPlugin.php**
```php
<?php
namespace App\Plugins;

class YourPlugin
{
	public function run()
	{
		return 'Whatever you want';
	}
}
```

Register your plugin, ideally within a service provider:

```php
Plugin::register('plugin_name', 'App\Plugins\YourPlugin');
```

Now simply use it!

```php
{{ plugin_name() }}  // Echo's "whatever you want" in this case
```
