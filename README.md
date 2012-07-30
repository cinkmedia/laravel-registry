# Registry Bundle

Registry a database based Registry library for Laravel.

Inspired by Richard Davey's Codeiginiter spark:
http://www.richarddavey.com
https://github.com/richarddavey/codeigniter-registry

## Installation

Create a database table to store registry values

```sql
CREATE TABLE IF NOT EXISTS `registry` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

Install via artisan:

```bash
php artisan bundle:install registry
```

or download the zip from github and unzip into your 'bundles' directory

https://github.com/cinkmedia/laravel-registry

## Bundle Registration

Add the following to your **application/bundles.php** file:

```php
'registry',
```

So your bundle configuration includes the 'registry' bundle ('docs' is not required by Registry and is there in a default Laravel install):

```php
return array(

	'docs' => array('handles' => 'docs'),
	'registry',
);
```

## Guide

Get a registry value:

```php
Registry::getValue('my_key_to_fetch');
```

Set a registry value temporarily - this value will only be stored for the lifetime of the instance:

```php
Registry::setValue('my_key_to_set','my_value_to_set');
```

Set a registry value permanently - this value will permanently stored in the database:

```php
Registry::setValue('my_key_to_set','my_value_to_set', true);
```

Reset a registry value to the one stored in the database:

```php
Registry::resetValue('my_key_to_reset');
```

Delete a registry value:

```php
Registry::deleteValue('my_key_to_delete');
```

Save all overriden values to the database

```php
Registry::save();
```

## Configure

You can define the name of the table used to store registry values in **config/registry.php**.  Full documentation is included in the config file.
