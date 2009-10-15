<?php

class Dispatcher {
    
    static function dispatch($url) {
        if (empty($url)) {
            $response = self::render_index();
        } else if (($file = REPORTS_ROOT.DS.$url.'.php') && file_exists($file)) {
            $response = self::render_report($file);
        } else {
            $response = self::render_404();
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