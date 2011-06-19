<?php

function testAutoLoad($className) 
{
    $paths[] =  '../' . $className . '.php';

    foreach ($paths as $path) {
        if (file_exists($path)) {
            include_once $path;
            return;
        }
    }
};

spl_autoload_register('testAutoLoad');
