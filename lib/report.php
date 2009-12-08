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
    
    function __isset($key) {
        return isset($this->attributes[$key]);
    }
    
    function __get($key) {
        return $this->attributes[$key];
    }
    
    function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
    
    function __unset($key) {
        unset($this->attributes[$key]);
    }
    
    function run() {
        $this->parse();
        $this->query();
    }
    
    protected function parse() {
        ob_start();
        include $this->file;
        $this->sql = trim(ob_get_clean());
    }
    
    protected function query() {
        if ($this->database !== false && !empty($this->sql)) {
            $this->connection = &ConnectionManager::connection($this->database);
            $this->result = $this->connection->query($this->sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
    static function all() {
        $reports = glob(REPORTS_ROOT.DS.'*');
        sort($reports);
        foreach ($reports as &$report) {
            preg_match('#'.REPORTS_ROOT.DS.'(.+)\.php$#', $report, $matches);
            $report = $matches[1];
        }
        return $reports;
    }
    
}