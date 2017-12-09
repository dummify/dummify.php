<?php

namespace Dummify\Tests\E2e;

use Dummify\Dummify;
use Dummify\Tests\Assets\ResetableTrait;
use Dummify\Tests\Assets\User;
use PHPUnit\Framework\TestCase;

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
                'driver'   => 'sqlite',
                'database' => ':memory:',
            ]],
        ];
    }

    /**
     * @test
     * @dataProvider getConnections
     * @covers Dummify\Dummify::connectTo
     * @covers Dummify\Dummify::from
     * @covers Dummify\Dummify::insert
     */
    public function dummify_populates_a_table($connection)
    {
        static::useConnectionToResetDatabase($connection);

        Dummify::connectTo($connection)
            ->from('users')
            ->insert(function ($row) {
                $row->name = 'generic name 1';
                $row->email = 'generic1@email.com';

                return $row;
            }, 100);

        $data = User::where('id', 50)->first();
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
    public function dummify_updates_a_table($connection)
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
    public function dummify_updates_a_table_with_conditionals($connection)
    {
        static::useConnectionToResetAndPopulateDatabase($connection);

        Dummify::connectTo($connection)
            ->from('users', function ($query) {
                return $query->where('name', 'like', '%');
            })
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
