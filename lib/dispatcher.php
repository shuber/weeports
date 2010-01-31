<?php

class Dispatcher {
    
    static function dispatch($request) {
        $arguments = array($request);
        $uri = $request->request_uri();
        
        if (empty($uri) || $uri == '/') {
            $method = 'render_index';
        } else if (($file = REPORTS_ROOT.DS.$uri.'.php') && file_exists($file)) {
            $method = 'render_report';
            array_unshift($arguments, $file);
        } else {
            $method = 'render_404';
        }
        
        return call_user_func_array(array(__CLASS__, $method), $arguments);
    }
    
    protected static function render_404($request) {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        ob_start();
        include PUBLIC_ROOT.DS.'404.html';
        return ob_get_clean();
    }
    
    protected static function render_index($request) {
        $locals = array('request' => $request, 'tags' => Report::tags());
        $locals['content_for_layout'] = Template::render(VIEWS_TEMPLATE_DIRECTORY.DS.'index', $locals);
        return Template::render(LAYOUTS_TEMPLATE_DIRECTORY.DS.'default', $locals);
    }
    
    protected static function render_report($report_file, $request) {
        $report = new Report($report_file);
        $report->run();
        $locals = array('report' => $report, 'request' => $request);
        $locals['content_for_layout'] = Template::render(VIEWS_TEMPLATE_DIRECTORY.DS.$report->view, $locals);
        return ($report->layout) ? Template::render(LAYOUTS_TEMPLATE_DIRECTORY.DS.$report->layout, $locals) : $locals['content_for_layout'];
    }
    
}