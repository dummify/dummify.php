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
            ]]
        ];
    }

    /**
     * @test
     * @dataProvider getConnections
     * @covers Dummify\Dummify::connectTo
     * @covers Dummify\Dummify::from
     * @covers Dummify\Dummify::update
     */
    function dummify_populates_a_table($connection)
    {
        static::useConnectionToResetDatabase($connection);

        Dummify::connectTo($connection)
            ->from('users')
            ->populate(function ($row) {
                $row->name = 'generic name 1';
                $row->email = 'generic1@email.com';
                return $row;
            }, 100);

        $data = User::where('id',50)->first();
        $this->assertEquals($data->name, 'generic name 1');
        $this->assertEquals($data->email, 'generic1@email.com');
    }

    /**
     * @test
     * @dataProvider getConnections
     * @covers Dummify\Dummify::connectTo
     * @covers Dummify\Dummify::from
     * @covers Dummify\Dummify::update
     */
    function dummify_updates_a_table($connection)
    {
        static::useConnectionToResetAndPopulateDatabase($connection);

        Dummify::connectTo($connection)
            ->from('users')
            ->update(function ($line) {
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
     * @covers Dummify\Dummify::update
     * @covers Dummify\Dummify::getQuery
     */
    function dummify_updates_a_table_with_conditionals($connection)
    {
        static::useConnectionToResetAndPopulateDatabase($connection);

        Dummify::connectTo($connection)
            ->from('users', function ($query){ return $query->where('name', 'like', '%'); })
            ->update(function ($line) {
                $line->name = 'generic name 2';
                $line->email = 'generic2@email.com';
                return $line;
            });

        $data = User::first();
        $this->assertEquals($data->name, 'generic name 2');
        $this->assertEquals($data->email, 'generic2@email.com');
    }
}