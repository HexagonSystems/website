<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
    require dirname(__DIR__) . '/config/bootstrap.php';

    $request = new \nx\core\Request();
    $dispatcher = new \nx\core\Dispatcher();
    $dispatcher->handle($request, \app\config\Routes::get_routes());
?>
