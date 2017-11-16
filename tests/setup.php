<?php

use DummifyTests\Assets\User;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;

$database = new DB;
$database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
$database->bootEloquent();
$database->setAsGlobal();

DB::schema()->create('users', function($table) {
  $table->increments('id');
  $table->string('name');
  $table->string('email');
  $table->timestamps();
});

$faker = Faker\Factory::create();

for($i=0;$i<100; $i++) {
  User::forceCreate([
    'name' => $faker->name,
    'email' => $faker->email,
  ]);
}