<?php

class ConnectionManager {
    
    static $configurations = array();
    protected static $connections = array();
    
    static function &connection($name) {
        if (!isset(self::$connections[$name])) self::establish_connection($name);
        return self::$connections[$name];
    }
    
    protected static function establish_connection($name) {
        if (isset(self::$configurations[$name])) {
            $configuration = self::$configurations[$name];
            $adapter = array_delete('adapter', $configuration);
            $username = array_delete('username', $configuration);
            $password = array_delete('password', $configuration);
            $configuration['dbname'] = array_delete('database', $configuration);
            self::$connections[$name] = new PDO($adapter.':'.array_join_assoc('=', ';', $configuration), $username, $password);
        } else {
            throw new InvalidArgumentException('Database configuration "'.$name.'" does not exist');
        }
    }
    
}