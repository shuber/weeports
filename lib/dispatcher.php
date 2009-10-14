<?php

class Dispatcher {
    
    static function dispatch($url) {
        if (empty($url)) {
            // render index + search
        } else {
            $report = REPORTS_ROOT.DS.$url.'.php';
            if (file_exists($report)) {
                echo $report;
            } else {
                echo '404';
            }
        }
    }
    
}