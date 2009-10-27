<?php

class Dispatcher {
    
    public $request;
    
    function __construct($request) {
        $this->request = $request;
    }
    
    function dispatch() {
        $uri = $this->request->request_uri();
        if (empty($uri) || $uri == '/') {
            $response = $this->render_index();
        } else if (($file = REPORTS_ROOT.DS.$uri.'.php') && file_exists($file)) {
            $response = $this->render_report($file);
        } else {
            $response = $this->render_404();
        }
        return $response;
    }
    
    protected function render_404() {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        ob_start();
        include PUBLIC_ROOT.DS.'404.html';
        return ob_get_clean();
    }
    
    protected function render_index() {
        // 
    }
    
    protected function render_report($report_file) {
        $report = new Report($report_file);
        $report->run();
        $locals = array('report' => $report, 'request' => $this->request);
        $locals['content_for_layout'] = Template::render(VIEWS_TEMPLATE_DIRECTORY.DS.$report->view, $locals);
        return ($report->layout) ? Template::render(LAYOUTS_TEMPLATE_DIRECTORY.DS.$report->layout, $locals) : $locals['content_for_layout'];
    }
    
}