Caffeinated Plugins
===================
[![Laravel 5.0](https://img.shields.io/badge/Laravel-5.0-orange.svg?style=flat-square)](http://laravel.com)
[![Laravel 5.1](https://img.shields.io/badge/Laravel-5.1-orange.svg?style=flat-square)](http://laravel.com)
[![Source](http://img.shields.io/badge/source-caffeinated/plugins-blue.svg?style=flat-square)](https://github.com/caffeinated/plugins)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Abstraction layer between Blade/Twig to allow the means to "plug in" data through a consistent interface.

Quick Installation
------------------
Begin by installing the package through Composer. Depending on what version of Laravel you are using (5.0 or 5.1), you'll want to pull in the `~1.0` or `~2.0` release, respectively:

#### Laravel 5.0.x
```
composer require caffeinated/plugins=~1.0
```

#### Laravel 5.1.x
```
composer require caffeinated/plugins=~2.0
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Laravel 5.0.x
##### Service Provider
```php
'Caffeinated\Plugins\PluginsServiceProvider',
```

##### Facade
```php
'Plugin' => 'Caffeinated\Plugins\Facades\Plugin',
```

#### Laravel 5.1.x
##### Service Provider
```php
Caffeinated\Plugins\PluginsServiceProvider::class,
```

##### Facade
```php
'Plugin' => Caffeinated\Plugins\Facades\Plugin::class,
```

And that's it! With your coffee in reach, start plugging in some data!

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
