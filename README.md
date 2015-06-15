[![PHP 5.5+][ico-engine]][lang]
[![MIT Licensed][ico-license]][license]
[ico-engine]: http://img.shields.io/badge/php-5.5+-8892BF.svg
[lang]: http://php.net
[ico-license]: http://img.shields.io/packagist/l/adlawson/veval.svg
[license]: LICENSE

zf2 maintenance mode
====================

This ZF2 "maintenance mode" module allows you to stall your application to maintenance (503 by default) via CLI.
It's heavily inspired from the [apigility development mode module] (https://github.com/zfcampus/zf-development-mode).

It mainly short-circuits the module manager during modules initialization and short-circuits the framework bootstrapping to prevent any service to load, like db connections for example. 

Requirements
============
  
PHP 5.5+

Installation
============
Run the command below to install via Composer

```shell
composer require mpalourdio/zf2-maintenance-mode
```

Add "ZfMaintenanceMode" to your **modules list** in **application.config.php**

Eventually, copy ``` maintenance.config.global.php.dist``` to ````config/autoload/maintenance.config.global.php``` to personalize the message and/or the http status code and/or the path of the flag file.

Enable maintenance mode
==========================

```sh
cd path/to/project/root
php public/index.php maintenance enable
```

Note: clear your cached configuration if needed.

Disable maintenance mode
===========================

```sh
cd path/to/project/root
php public/index.php maintenance disable
```
