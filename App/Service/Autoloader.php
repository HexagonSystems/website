<?php

function autoload($className) {
    $model      = AppBase.'/Model/'.$className.'.php';
    $controller = AppBase."/Controller/" . $className . ".php";
    $service    = AppBase."/Service/" . $className . ".php";
    $view       = AppBase."/View/" . $className . ".php";
    
    if (is_readable($model)) {
        require $model;
    }elseif (is_readable($controller)) {
        require $controller;
    }elseif (is_readable($service)) {
        require $service;
    }elseif (is_readable($view)) {
        require $view;
    }
}

spl_autoload_register("autoload");
?>