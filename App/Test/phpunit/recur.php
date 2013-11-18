<?php
$it = new RecursiveDirectoryIterator("../");
$allowed=array("php");
foreach(new RecursiveIteratorIterator($it) as $file) {
    if(in_array(substr($file, strrpos($file, '.') + 1),$allowed)) {
        spl_autoload_register(function ($file) {
            include $file;
        });
    }
}
?>