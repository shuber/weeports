<?php

class Report {
    
    public $attributes = array();
    public $connection;
    public $database = 'default';
    public $file;
    public $layout = 'default';
    public $sql;
    public $title;
    public $view = 'default';
    
    function __construct($report) {
        $this->file = $report;
        $this->title = Inflector::humanize(str_replace('.php', '', basename($report)));
    }
    
    function __get($key) {
        return $this->attributes[$key];
    }
    
    function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    function render() {
        $this->parse_report();
        $this->run_query();
        $this->content_for_layout = $this->render_view();
        return $this->render_layout();
    }
    
    protected function parse_report() {
        ob_start();
        include $this->file;
        $this->sql = trim(ob_get_clean());
    }
    
    protected function render_layout() {
        return $this->render_template(LAYOUTS_TEMPLATE_DIRECTORY.DS.$this->layout.'.php');
    }
    
    protected function render_template($template) {
        ob_start();
        include TEMPLATES_ROOT.DS.$template;
        return ob_get_clean();
    }
    
    protected function render_view() {
        return $this->render_template(VIEWS_TEMPLATE_DIRECTORY.DS.$this->view.'.php'); 
    }
    
    protected function run_query() {
        $this->connection = &ConnectionManager::connection($this->database);
        $this->result = $this->connection->query($this->sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
}