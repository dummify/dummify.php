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
    public static function connectTo($params)
    {
        $instance = static::getInstance();

        if(is_null($instance)) {
            static::initialize();
            $instance = static::getInstance();
        }

        $instance->connection($params);

        return $instance;
    }

    /**
     * 
     */
    public function hasConnection($name)
    {
        return isset($this->capsule->getDatabaseManager()->getConnections()[$name]);
    }

    /**
     * 
     */
    public function addConnection($params)
    {
        $this->capsule->addConnection($params, 'default');
        return $this;
    }

    /**
     * 
     */
    public function getConnection()
    {
        return $this->capsule->connection('default');
    }

    /**
     * 
     */
    public function connection($params)
    {
        $this->addConnection($params);
        return $this->getConnection();
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
            $query->update((array) $callable($row));
        });
    }
}