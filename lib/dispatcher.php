<?php

class Dispatcher {
    
    static function dispatch($url) {
        if (empty($url) || $url == '/') {
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
        ob_start();
        include PUBLIC_ROOT.DS.'404.html';
        return ob_get_clean();
    }
    
    protected static function render_index() {
        // 
    }
    
    protected static function render_report($report_file) {
        $report = new Report($report_file);
        $report->run();
        $locals = array('report' => $report);
        $locals['content_for_layout'] = Template::render(VIEWS_TEMPLATE_DIRECTORY.DS.$report->view, $locals);
        return ($report->layout) ? Template::render(LAYOUTS_TEMPLATE_DIRECTORY.DS.$report->layout, $locals) : $locals['content_for_layout'];
    }
    
}