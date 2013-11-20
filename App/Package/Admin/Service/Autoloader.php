<?php
namespace Admin;
function autoload($className) {
    //Sub string the package name off the class in this case "Task\"
	echo $className."     ";
    $className = substr($className, 6);
    echo $className."<br/><br/>";
    

    $router     = AppBase.'/'.Base.'/'.$className.'.php';
    $model      = AppBase.'/'.Base.'/Model/'.$className.'.php';
    $controller = AppBase.'/'.Base."/Controller/" . $className . ".php";
    $service    = AppBase.'/'.Base."/Service/" . $className . ".php";
    $view       = AppBase.'/'.Base."/View/" . $className . ".php";
    
    if (is_readable($router)) {
        require $router;
    }elseif (is_readable($model)) {
        require $model;
    }elseif (is_readable($controller)) {
        require $controller;
    }elseif (is_readable($service)) {
        require $service;
    }elseif (is_readable($view)) {
        require $view;
    }
}

spl_autoload_register("Admin\autoload");
?>