<?php

class ConnectionManager {
    
    static $configurations = array();
    protected static $connections = array();
    
    static function &connection($name) {
        if (!isset(self::$connections[$name])) static::establish_connection($name);
        return self::$connections[$name];
    }
    
    protected static function establish_connection($name) {
        if (isset(self::$configurations[$name])) {
            $configuration = self::$configurations[$name];
            $connection_string = $configuration['adapter'].':host='.$configuration['host'].';dbname='.$configuration['database'].';';
            self::$connections[$name] = new PDO($connection_string, $configuration['username'], $configuration['password']);
        } else {
            throw new InvalidArgumentException('Database configuration "'.$name.'" does not exist');
        }
    }
    
}