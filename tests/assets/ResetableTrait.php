<?php

namespace Dummify\Tests\Assets;

use Faker\Factory;
use Illuminate\Database\Capsule\Manager as DB;

trait ResetableTrait
{
    public static function useConnectionToResetDatabase($connection)
    {
        $database = new DB();
        $database->addConnection($connection);
        $database->setAsGlobal();
        $database->bootEloquent();

        DB::schema()->dropIfExists('users');

        DB::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });
    }

    public static function useConnectionToResetAndPopulateDatabase($connection, $iterations = 100)
    {
        static::useConnectionToResetDatabase($connection);

        $faker = Factory::create();

        for ($i = 0; $i < $iterations; $i++) {
            User::forceCreate([
                'name'  => $faker->name,
                'email' => $faker->email,
            ]);
        }
    }
}
