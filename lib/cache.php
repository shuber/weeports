<?php

abstract class Cache {
    
    static function exists($path) {
        return file_exists(TMP_ROOT.DS.$path);
    }
    
    static function read($path) {
        if (self::exists($path)) {
            return file_get_contents(TMP_ROOT.DS.$path);
        } else {
            return false;
        }
    }
    
    static function write($path, $content) {
        return file_put_contents(TMP_ROOT.DS.$path, $content);
    }
    
}

?>