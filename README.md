zf-maintenance-mode
===================

This ZF2 "maintenance mode" module allows you to stall your application to maintenance (503) via CLI.

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

Eventually, ```cp maintenance.config.global.php.dist config/autoload/maintenance.config.global.php```

To enable maintenance mode
==========================

```sh
cd path/to/install
php public/index.php maintenance enable
```

Note: clear your configuration if needed.

To disable maintenance mode
===========================

```sh
cd path/to/install
php public/index.php maintenance disable
```
