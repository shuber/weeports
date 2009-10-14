<?php

class Report {
    
    function __construct($report) {
        ob_start();
        include $report;
        $this->sql = trim(ob_get_clean());
    }
    
    function render() {
        return $this->sql;
    }
    
}