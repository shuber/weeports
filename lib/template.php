<?php

class Template {
    
    static $extension = '.php';
    
    static function render($template, $locals = array()) {
        extract($locals);
        ob_start();
        include TEMPLATES_ROOT.DS.$template.self::$extension;
        return ob_get_clean();
    }
    
}