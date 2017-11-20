## Dummify.php

[![Build Status](https://travis-ci.org/dummify/dummify.php.svg?branch=master)](https://travis-ci.org/dummify/dummify.php)

Programmatically dummifies your database to non-sensitive data for development use!

### TL;DR

```php
Dummify::connectTo(['driver' => 'sqlite', 'database' => ':memory:'])
  // choose a table
  ->from('users')
  // (optional) you may add conditionals if you want
  // ->from('users', function($query){ return $query->where('name', 'like', '%Filipe%'); })
  // iterate over each line
  ->each(function($line) {
    $line->name = 'generic name 2';
    $line->email = 'generic2@email.com';
    return $line;
  });
```

### Setup a connection

Using `Illuminate\Database` capsule for database connections, `Dummify.php` can connect to:
- MySQL
- PostgreSQL
- SQL Server
- SQLite

To create a new connection you need an array of parameters like this one:

##### MySQL/MariaDB connection

There is an [example here](https://github.com/laravel/laravel/blob/master/config/database.php#L42)!

```php
$connection = [
  'driver' => 'mysql',
  'host' => '127.0.0.1',
  'port' => '3306',
  'database' => 'example',
  'username' => 'root',
  'password' => '',
  'unix_socket' => '',
  'charset' => 'utf8mb4',
  'collation' => 'utf8mb4_unicode_ci',
  'prefix' => '',
  'strict' => true,
  'engine' => null,
];
```

##### PostgreSQL connection

There is an [example here](https://github.com/laravel/laravel/blob/master/config/database.php#L57)!

```php
$connection = [
  'driver' => 'pgsql',
  'host' => '127.0.0.1',
  'port' => '5432',
  'database' => 'example',
  'username' => 'root',
  'password' => '',
  'charset' => 'utf8',
  'prefix' => '',
  'schema' => 'public',
  'sslmode' => 'prefer',
];
```

##### SQL Server connection

There is an [example here](https://github.com/laravel/laravel/blob/master/config/database.php#L70)!

```php
$connection = [
  'driver' => 'sqlsrv',
  'host' => '127.0.0.1'),
  'port' => '1433',
  'database' => 'example',
  'username' => 'root',
  'password' => '',
  'charset' => 'utf8',
  'prefix' => '',
];
```

##### SQLite connection

There is an [example here](https://github.com/laravel/laravel/blob/master/config/database.php#L36)!

```php
$connection = [
  'driver' => 'sqlite',
  'database' => '/static/path/to/database.sqlite',
  'prefix' => '',
];
```

Or you can use in memory connection like this:

```php
$connection = [
  'driver' => 'sqlite',
  'database' => ':memory:',
  'prefix' => '',
];
```

Once you have your connection array you can connect into your database using:

```php
$dummify = Dummify::connectTo($connection)
```

### Iterate over a table

You may choose a table using the `from($table)` method.

```php
$dummify->from('users')
```

If you are interested on limiting or adding conditions to your SQL query, you can use all `Illuminate\Database` fluent syntax!

For more docs about it follow-up with `Laravel` [docs](https://laravel.com/docs/5.5/queries);

```php
$dummify->from('users', function($query) {
  $query->where('name', 'like', '%Filipe%');
});
```

Than you can setup how the iterator will work over each line!

```php
$dummify
  ->from('users')
  ->each(function($line){
    $line->name = 'Frankenstein'
    $line->email = 'contact@franky.com'
  });
```

And in this case we will have a whole table of Frankensteins!

Or maybe you want to use another method using [Faker](https://github.com/fzaninotto/Faker)!

```php
$faker = Faker\Factory::create();

$dummify
  ->from('users')
  ->each(function($line){
    $line->name = $faker->name
    $line->email = $faker->email
  });
```
