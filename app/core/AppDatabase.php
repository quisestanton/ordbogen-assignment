<?php

use Pecee\Pixie\Connection;

class AppDatabase
{
    /**
     * @var Connection[]
     */
    protected static $connection = [];
    
    public static function __callStatic($name, $arguments)
    {
        $queryBuilder = self::connect()->getQueryBuilder();
        if (method_exists($queryBuilder, $name)) {
            return call_user_func_array([$queryBuilder, $name], $arguments);
        }
        else throw new Exception("Unknown query builder method");
    }
    
    public static function connect($db = '')
    {
        if (!$db) {
            $db = Config::getValue('database.default');
        }
        if(empty(self::$connection[$db]))
        {
            new self($db);
        }
        return self::$connection[$db];
    }
    
    private function __construct($db)
    {
        $connection = new Connection(Config::getValue("database.connections.$db.driver"), Config::getValue("database.connections.$db"));
        if ($connection) {
            self::$connection[$db] = $connection;
        }
    }
}
