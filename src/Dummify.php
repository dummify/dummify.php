<?php

namespace Dummify;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Dummify class
 */
class Dummify
{
    protected static $instance;

    protected $capsule;

    protected $table;

    protected $filter;

    /**
     * Creates a single for Dummify
     */
    public static function initialize()
    {
        $instance = new static();
        static::$instance = $instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->capsule = new DB();
        $this->table = null;
        $this->filter = null;
    }

    /**
     * Returns Dummify's instance
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Initializes and defines connection
     */
    public static function connectTo($params)
    {
        $instance = static::getInstance();

        if (is_null($instance)) {
            static::initialize();
            $instance = static::getInstance();
        }

        $instance->addConnection($params);

        return $instance;
    }

    /**
     * Adds a connection
     */
    public function addConnection($params)
    {
        $this->capsule->addConnection($params, 'default');

        return $this;
    }

    /**
     * Selects a table and a passes a optional condition
     */
    public function from($table, callable $callable = null)
    {
        $this->table = $table;
        $this->filter = $callable;

        return $this;
    }

    /**
     * Creates a factory using the connection
     */
    protected function getQuery()
    {
        $query = $this->capsule->table($this->table);

        $filter = $this->filter;
        if (!is_null($filter)) {
            $query = $filter($query);
        }

        return $query;
    }

    /**
     * Populates a table with a row generator.
     */
    public function insert(callable $callable, $number = 1)
    {
        while ($number-- > 0) {
            $data = $callable((object) []);
            $this->getQuery()->insert((array) $data);
        }

        return $this;
    }

    /**
     * Iterates over update record
     */
    public function update(callable $callable)
    {
        $data = $this->getQuery()->get();

        $data->each(function ($row) use ($callable) {
            $this->getQuery()
                ->where((array) $row)
                ->update((array) $callable($row));
        });

        return $this;
    }
}
