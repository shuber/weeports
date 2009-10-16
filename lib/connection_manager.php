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
            $configuration['dbname'] = array_delete('database', $configuration);
            $adapter = array_delete('adapter', $configuration);
            $username = array_delete('username', $configuration);
            $password = array_delete('password', $configuration);
            $connection_string = $adapter.':';
            $connection_string .= ($adapter == 'sqlite') ? $configuration['dbname'] : array_join_assoc('=', ';', $configuration);
            self::$connections[$name] = new PDO($connection_string, $username, $password);
        } else {
            throw new InvalidArgumentException('Database configuration "'.$name.'" does not exist');
        }
    }
    
}