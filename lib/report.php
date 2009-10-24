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
        return Template::render(LAYOUTS_TEMPLATE_DIRECTORY.DS.$this->layout, $this->template_locals());
    }
    
    protected function render_view() {
        return Template::render(VIEWS_TEMPLATE_DIRECTORY.DS.$this->view, $this->template_locals());
    }
    
    protected function run_query() {
        if ($this->database !== false) {
            $this->connection = &ConnectionManager::connection($this->database);
            $this->result = $this->connection->query($this->sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    protected function template_locals() {
        $locals = get_object_vars($this);
        return array_merge(array('report' => $this), array_delete('attributes', $locals), $locals);
    }
    
}