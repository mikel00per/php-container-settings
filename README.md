# PHP Container settings

This is a package provides a factory called ```ContainerFactory```, a container (PSR-11) using [PHP-DI](https://php-di.org/). This container will have an implementation of ````SettingsInterface```` that implementation allow access to the settings using "dot annotations". Also, you can optimize perform in your project using the cache feature.

## Example

To use this package you need create a file or an array,  in case of use a file you must return an array in that. Here you can see an example of use:

```
// settings.php
return [
    'foo' => [
        'bar' => 1
    ]
];
```

```
// index.php
use ContainerFactory;

$settingsPath = __DIR__ . '/settings.php';
$container = ContainerFactory::buildContainer($settingsPath);

$settings = $container->get(SettingsInterface::class');
$number = $settings->get('foo.bar');
echo $number; // 1
```

