<?php

function autoloader($class) {
    $class_path = str_replace("\\", "/", $class);
    $class_path = preg_replace('/^App\//', '', $class_path);

    $file = __DIR__.'\\'.$class_path.".php";
    if (file_exists($file)) {
        require_once $file;
    } else {
        
    }
}

spl_autoload_register("autoloader");