<?php

function array_delete($key, &$array) {
    $value = isset($array[$key]) ? $array[$key] : null;
    unset($array[$key]);
    return $value;
}

function array_join_assoc($assoc_glue, $glue, $array) {
    $result = array();
    foreach ($array as $key => $value) {
        $result[] = $key.$assoc_glue.$value;
    }
    return implode($glue, $result);
}

function content_tag($type, $content, $attrs = array()) {
    $tag = '<'.$type;
    foreach ($attrs as $key => $value) {
        $tag .= ' '.$key.'="'.$value.'"';
    }
    return $tag.'>'.$content.'</'.$type.'>';
}

function cycle($values, $key) {
    static $cycle = array();
    if (!isset($cycle[$key])) $cycle[$key] = $values;
    $value = array_shift($cycle[$key]);
    array_push($cycle[$key], $value);
    return $value;
}

function link_to($label, $url, $attrs = array()) {
    return content_tag('a', $label, array_merge(array('href' => url_for($url)), $attrs));
}

function rglob($pattern, $flags = 0, $path = '') {
    if (!$path && ($dir = dirname($pattern)) != '.') {
        if ($dir == '\\' || $dir == '/') $dir = '';
        return rglob(basename($pattern), $flags, $dir . '/');
    }
    $paths = glob($path . '*', GLOB_ONLYDIR | GLOB_NOSORT);
    $files = glob($path . $pattern, $flags);
    foreach ($paths as $p) $files = array_merge($files, rglob($pattern, $flags, $p . '/'));
    return $files;
}

function url_for($url) {
    if (!preg_match('#^((http[s]?)|javascript):#', $url)) $url = PATH_PREFIX.'/'.preg_replace('#^/#', '', $url);
    return $url;
}