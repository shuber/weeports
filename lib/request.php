<?php

class Request {
    
    public $get;
    public $env;
    public $path_prefix = '';
    public $post;
    public $tld_length = 1;
    
    function __construct($environment, $get, $post) {
        $this->env = $environment;
        $this->get = $get;
        $this->post = $post;
    }
    
    function domain($tld_length = null) {
        if ($this->named_host()) {
            if (is_null($tld_length)) $tld_length = $this->tld_length;
            $hostname_labels = explode('.', $this->host());
            return join('.', array_slice($hostname_labels, count($hostname_labels) - $tld_length - 1, $tld_length + 1));
        }
    }
    
    function forwarded_hosts() {
        return isset($this->env['HTTP_X_FORWARDED_HOST']) ? preg_split('#,\s?#', $this->env['HTTP_X_FORWARDED_HOST']) : array();
    }
    
    function forwarded_uris() {
        return isset($this->env['HTTP_X_FORWARDED_URI']) ? preg_split('#,\s?#', $this->env['HTTP_X_FORWARDED_URI']) : array();
    }
    
    function host() {
        return array_shift(explode(':', $this->raw_host_with_port()));
    }
    
    function host_with_port() {
        return $this->host().$this->port_string();
    }
    
    function named_host() {
        $host = $this->host();
        return !(empty($host) || preg_match('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$#', $host));
    }
    
    function parameters() {
        return array_merge($this->post, $this->get);
    }
    
    function path() {
        return preg_replace('#^'.preg_escape($this->path_prefix).'#', '', $this->request_uri());
    }
    
    function port() {
        if (preg_match('#\:(\d+)$#', $this->raw_host_with_port(), $matches)) {
            $port = (int) strtolower($matches[0]);
        } else {
            $port = $this->standard_port();
        }
        return $port;
    }
    
    function port_string() {
        $port = $this->port();
        return $port == $this->standard_port() ? '' : ':'.$port;
    }
    
    function protocol() {
        return $this->ssl() ? 'https://' : 'http://';
    }
    
    function query_string() {
        return isset($this->env['QUERY_STRING']) ? $this->env['QUERY_STRING'] : array_pop(explode('?', $this->env['REQUEST_URI'], 2));
    }
    
    function raw_host_with_port() {
        if (isset($this->env['HTTP_X_FORWARDED_HOST'])) {
            $raw_host_with_port = array_pop(preg_split('#,\s?#', $this->env['HTTP_X_FORWARDED_HOST']));
        } else if (isset($this->env['HTTP_HOST'])) {
            $raw_host_with_port = $this->env['HTTP_HOST'];
        } else if (isset($this->env['SERVER_NAME'])) {
            $raw_host_with_port = $this->env['SERVER_NAME'];
        } else {
            $raw_host_with_port = $this->env['SERVER_ADDR'].':'.$this->env['SERVER_PORT'];
        }
        return $raw_host_with_port;
    }
    
    function referer() {
        return $this->referrer();
    }
    
    function referrer() {
        return $this->env['HTTP_REFERER'];
    }
    
    function remote_ip() {
        if (isset($this->env['HTTP_CLIENT_IP'])) {
            $remote_ip = $this->env['HTTP_CLIENT_IP'];
        } else if (isset($this->env[''])) {
            $remote_ip = array_shift(preg_split('#,\s?#', $this->env['HTTP_FORWARDED_FOR']));
        } else {
            $remote_ip = $this->env['REMOTE_ADDR'];
        }
        return $remote_ip;
    }
    
    function request_method() {
        // TODO
    }
    
    function request_uri() {
        if (isset($this->env['REQUEST_URI'])) {
            $request_uri = preg_replace('#^\w+\://[^/]+#', '', $this->env['REQUEST_URI']);
        } else {
            $request_uri = $this->env['PATH_INFO'];
            if (preg_match('#[^/]+$#', $this->env['SCRIPT_NAME'])) $request_uri = preg_replace('#'.preg_quote($this->env['SCRIPT_NAME']).'/#', '', $request_uri);
            if (isset($this->env['QUERY_STRING']) && !empty($this->env['QUERY_STRING'])) $request_uri .= '?'.$this->env['QUERY_STRING'];
            $this->env['REQUEST_URI'] = $request_uri;
        }
        return $request_uri;
    }
    
    function server_port() {
        return (int) $this->env['SERVER_PORT'];
    }
    
    function server_software() {
        if (isset($this->env['SERVER_SOFTWARE']) && preg_match('#^([a-zA-Z]+)#', $this->env['SERVER_SOFTWARE'], $matches)) return strtolower($matches[0]);
    }
    
    function ssl() {
        return $this->env['HTTPS'] == 'on' || $this->env['HTTP_X_FORWARDED_PROTO'] == 'https';
    }
    
    function standard_port() {
        return $this->ssl() ? 443 : 80;
    }
    
    function subdomains() {
        return explode('.', preg_replace('#\.?'.preg_quote($this->domain()).'$#', '', $this->host()));
    }
    
    function url() {
        return $this->protocol().$this->host_with_port().$this->request_uri();
    }
    
    function xml() {
        return $this->xml_http_request();
    }
    
    function xml_http_request() {
        return preg_match('#XMLHttpRequest#i', $this->env['HTTP_X_REQUESTED_WITH']);
    }
    
}