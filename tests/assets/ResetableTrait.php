<?php

namespace DummifyTests\Assets;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * 
 */
trait ResetableTrait
{
    /**
     * 
     */
    public static function resetDatabaseUsingConnection($connection)
    {
        $database = new DB;
        $database->addConnection($connection);
        $database->setAsGlobal();
        $database->bootEloquent();

        DB::schema()->dropIfExists('users');

        DB::schema()->create('users', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });

        $faker = Factory::create();

        for($i=0;$i<100; $i++) {
            User::forceCreate([
                'name' => $faker->name,
                'email' => $faker->email,
            ]);
        }
    }
}
