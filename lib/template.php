<?php

class Template {
    
    static function render($template, $locals = array()) {
        extract($locals);
        ob_start();
        include TEMPLATES_ROOT.DS.$template.'.php';
        return ob_get_clean();
    }
    
}