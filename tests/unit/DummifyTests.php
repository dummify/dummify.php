<?php

use Dummify\Dummify;
use PHPUnit\Framework\TestCase;
use DummifyTests\Assets\ResetableTrait;

/**
 * @coversDefaultClass \Dummify\Dummify
 */
class DummifyTests extends TestCase
{
    use ResetableTrait;

    protected static $connection;

    public static function setUpBeforeClass()
    {
        self::$connection = ['driver' => 'sqlite', 'database' => ':memory:'];
        static::useConnectionToResetAndPopulateDatabase(self::$connection);
    }

    /**
     * @test
     * @covers ::getInstance
     */
    function dummify_not_created_yet()
    {
        $this->assertNull(Dummify::getInstance());
    }

    /**
     * @test
     * @covers ::connectTo
     * @covers ::getInstance
     * @covers ::initialize
     * @covers ::__construct
     * @covers ::addConnection
     */
    function dummify_can_be_created()
    {
        $this->assertInstanceOf(Dummify::class, Dummify::connectTo(self::$connection));
    }
}