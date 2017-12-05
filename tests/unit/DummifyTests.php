<?php

use Dummify\Dummify;
use DummifyTests\Assets\ResetableTrait;
use PHPUnit\Framework\TestCase;

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
    public function dummify_not_created_yet()
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
    public function dummify_can_be_created()
    {
        $this->assertInstanceOf(Dummify::class, Dummify::connectTo(self::$connection));
    }
}
