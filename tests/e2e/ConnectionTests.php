<?php

use Faker\Factory;
use Dummify\Dummify;
use DummifyTests\Assets\User;
use PHPUnit\Framework\TestCase;
use DummifyTests\Assets\ResetableTrait;

/**
 * @coversDefaultClass \Dummify\Dummify
 */
class ConnectionTests extends TestCase
{
    use ResetableTrait;

    /**
     * Default dataProvider
     */
    public function getConnections()
    {
        return [
            'SQLite' => [[
                'driver' => 'sqlite',
                'database' => ':memory:'
            ]],
        ];
    }

    /**
     * @test
     * @dataProvider getConnections
     * @covers Dummify\Dummify::connectTo
     * @covers Dummify\Dummify::from
     * @covers Dummify\Dummify::each
     */
    function dummify_runs_over_a_table($connection)
    {
        static::resetDatabaseUsingConnection($connection);

        Dummify::connectTo($connection)
            ->from('users', function ($query){ return $query->where('name', 'like', '%'); })
            ->each(function ($line) {
                $line->name = 'generic name 1';
                $line->email = 'generic1@email.com';
                return $line;
            });

        $data = User::first();
        $this->assertEquals($data->name, 'generic name 1');
        $this->assertEquals($data->email, 'generic1@email.com');
    }

    /**
     * @test
     * @dataProvider getConnections
     * @covers Dummify\Dummify::connectTo
     * @covers Dummify\Dummify::from
     * @covers Dummify\Dummify::each
     */
    function dummify_runs_over_a_table_with_conditionals($connection)
    {
        static::resetDatabaseUsingConnection($connection);

        Dummify::connectTo($connection)
            ->from('users', function ($query){ return $query->where('name', 'like', '%'); })
            ->each(function ($line) {
                $line->name = 'generic name 2';
                $line->email = 'generic2@email.com';
                return $line;
            });

        $data = User::first();
        $this->assertEquals($data->name, 'generic name 2');
        $this->assertEquals($data->email, 'generic2@email.com');
    }
}