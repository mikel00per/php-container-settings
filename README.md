# PHP Container Settings


This is a package provides a factory called ```ContainerFactory```, a container (PSR-11) using [PHP-DI](https://php-di.org/). This container will have an implementation of ````SettingsInterface```` that implementation allow access to the settings using "dot annotations". Also, you can optimize perform in your project using the cache feature.

## Example use

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

$settingsPath = __DIR__ . '/settings.php'; // or array
$container = ContainerFactory::create($settingsPath);

$settings = $container->get(SettingsInterface::class');
$number = $settings->get('foo.bar');
echo $number; // 1
```

## Make commands 

````
$ make help

Usage: make [target] ...

Container:
  run                 Build and run php container
  build               Build php container
  stop                Stop php container
  destroy             Remove all data related with php container
  shell               SHH in container
  logs                Show logs in container
                      
Miscellaneous:
  help                Show this help
                      
Code:
  exec                Execute composer commands
                      
Tests:
  test                Execute tests
  test-coverage       Execute tests with coverage
                      
Style:
  lint                Show style errors
  lint-fix            Fix style errors
                      
Written by Antonio Miguel Morillo Chica, version v1.0
Please report any bug or error to the author.

````