<?php

class Report {
    
    public $attributes = array();
    public $file;
    public $title;
    
    function __construct($report) {
        $this->file = $report;
        $this->title = basename($report);
        $this->sql = $this->parse_sql();
    }
    
    function __get($key) {
        return $this->attributes[$key];
    }
    
    function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    function render() {
        return $this->sql;
    }
    
    protected function parse_sql() {
        ob_start();
        include $this->file;
        return trim(ob_get_clean());
    }
    
}