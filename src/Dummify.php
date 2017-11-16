<?php

namespace Dummify;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * 
 */
class Dummify
{
  static protected $instance;
  
  protected $capsule;

  protected $table;

  protected $filter;

  /**
   * 
   */
  static public function initialize()
  {
    $instance = new static;
    static::$instance = $instance;
  }

  /**
   * 
   */
  public function __construct()
  {
    $this->capsule = new DB;
    $this->table = null;
    $this->filter = null;
  }

  /**
   * 
   */
  public static function getInstance()
  {
    return static::$instance;
  }

  /**
   * 
   */
  public static function connectTo($params = [], $name = 'default')
  {
    $instance = static::getInstance();

    if(is_null($instance)) {
      static::initialize();
      $instance = static::getInstance();
    }

    $instance->connection($name, $params);

    return $instance;
  }

  /**
   * 
   */
  public function hasConnection($name)
  {
    return is_null($this->capsule->connection($name));
  }

  /**
   * 
   */
  public function addConnection($name = 'default', $params = [])
  {
    $this->capsule->addConnection($params, $name);
    return $this;
  }

  /**
   * 
   */
  public function connection($name = 'default', $params = [])
  {
    if(!$this->hasConnection($name)) {
      $this->addConnection($name, $params);
    }

    return $this->capsule->connection($name);
  }

  /**
   * 
   */
  public function from($table, Callable $callable = null)
  {
    $this->table = $table;
    $this->filter = $callable;
    return $this;
  }

  /**
   * 
   */
  protected function getQuery()
  {
    $query = $this->capsule->table($this->table);

    $filter = $this->filter;
    if(!is_null($filter)) {
      $query = $filter($query);
    }

    return $query;
  }

  /**
   * 
   */
  public function do(Callable $callable)
  {
    $data = $this->getQuery()->get();

    $data->each(function($row) use ($callable) {
      $query = $this->getQuery();
      $query->where((array) $row);
      $newRow = $callable($row);
      $query->update((array) $newRow);
      return $newRow;
    });
  }
}