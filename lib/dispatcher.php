<?php

class Dispatcher {
    
    static function dispatch($url) {
        if (empty($url)) {
            $response = static::render_index();
        } else if (($file = REPORTS_ROOT.DS.$url.'.php') && file_exists($file)) {
            $response = static::render_report($file);
        } else {
            $response = static::render_404();
        }
        return $response;
    }
    
    protected static function render_404() {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        include PUBLIC_ROOT.DS.'404.html';
    }
    
    protected static function render_index() {
        // 
    }
    
    protected static function render_report($report_file) {
        $report = new Report($report_file);
        return $report->render();
    }
    
}