## Dummify.php

> Programmatically dummifies your database to non-sensitive data for development use!

[![Build Status](https://travis-ci.org/dummify/dummify.php.svg?branch=master)](https://travis-ci.org/dummify/dummify.php) [![StyleCI](https://styleci.io/repos/111016957/shield?branch=master)](https://styleci.io/repos/111016957)

### TL;DR

```php
// Use an array of parameters to connect to a database
$connection = ['driver' => 'sqlite', 'database' => ':memory:'];

// You may populate your database with dummy data
Dummify::connectTo($connection)
->from('users')
->insert(function($row) {
  $row->name = 'generic name 2';
  $row->email = 'generic2@email.com';
  return $row;
});

// Or you can dummify with a new rule
Dummify::connectTo($connection)
->from('users')
->update(function($row) {
  $row->name = 'generic name 2';
  $row->email = 'generic2@email.com';
  return $row;
});
```

### Setup a connection

Using [`Illuminate\Database`](https://github.com/illuminate/database) capsule for database connections, `Dummify.php` can connect to:
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

### Instantiate a Dummify

Once you have your connection array you can connect into your database using:

```php
$dummify = Dummify::connectTo($connection)
```

Later you may choose a table using the `from($table)` method.

```php
$dummify->from('users')
```

### Populate a table with dummy data

You may populate a table using the `insert(callable $callable, $iterations = 1)` method. In this case we are using 
[Faker](https://github.com/fzaninotto/Faker) to help us generate random data!

```php
$faker = Faker\Factory::create();

$dummify
  ->from('users')
  ->insert(function($row){
    $row->name = $faker->name
    $row->email = $faker->email
    return $row;
  });

// (Optional) You can pass how many you want to create
$dummify
  ->from('users')
  ->insert(function($row){
    $row->name = $faker->name
    $row->email = $faker->email
    return $row;
  }, 100);
```

### Update a table with dummy data

You may setup how the iterator will work over each line using the `update(callable $callable)` method!


```php
$faker = Faker\Factory::create();

$dummify
  ->from('users')
  ->update(function($row){
    $row->name = $faker->name
    $row->email = $faker->email
    return $row
  });
```

#### Making restrictions for updates
If you are interested on limiting or adding conditions to your SQL query, you can use all `Illuminate\Database` fluent syntax!

For more docs about it follow-up with `Laravel` [docs](https://laravel.com/docs/queries);

```php
$dummify->from('users', function($query) {
  return $query->where('name', 'like', '%Filipe%');
});
```
