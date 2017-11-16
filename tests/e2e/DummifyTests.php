<?php

use Faker\Factory;
use Dummify\Dummify;
use PHPUnit\Framework\TestCase;

/**
 * 
 */
class SqliteTests extends TestCase
{
  function test_it_gets_a()
  {
    $faker = Faker\Factory::create();

    Dummify::connectTo(['driver' => 'sqlite', 'database' => ':memory:'])
      ->from('users')
      ->do(function($line) {
        $line->name = 'test1';
        $line->email = 'test1';
        return $line;
      });
    
    $data = Dummify::getInstance()->connection('default')->table('users')->first();
    $this->assertEquals($data->name, 'test1');
    $this->assertEquals($data->email, 'test1');
  }
}