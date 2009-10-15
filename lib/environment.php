<?php

class Environment {
    
    static function append_include_path($path) {
        return self::set_include_paths(array_merge(self::include_paths(), array($path)));
    }
    
    static function autoload($class) {
        $namespaces = preg_split('#::|\\\\#', $class);
        $parts = array();
        foreach ($namespaces as $namespace) {
            array_push($parts, strtolower(preg_replace('/[^A-Z^a-z^0-9]+/', '_', preg_replace('/([a-z\d])([A-Z])/', '\1_\2', preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2', $namespace)))));
        }
        require_once join(DS, $parts).'.php';
    }
    
    static function include_paths() {
        $paths = explode(PS, get_include_path());
        foreach ($paths as $key => $path) { 
            if (empty($path)) unset($paths[$key]);
        }
        return $paths;
    }
    
    static function prepend_include_path($path) {
        return self::set_include_paths(array_merge(array($path), self::include_paths()));
    }
    
    static function remove_include_path($path) {
        $paths = array();
        foreach (self::include_paths() as $include_path) {
            if ($include_path != $path) array_push($paths, $include_path);
        }
        self::set_include_paths($paths);
    }
    
    static function set_error_log($path) {
        ini_set('error_log', $path);
    }
    
    static function set_include_paths($paths) {
        return set_include_path(join(PS, $paths));
    }
    
}