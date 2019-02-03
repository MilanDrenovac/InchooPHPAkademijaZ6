<?php
//define base path, root path of our application
define('BP', __DIR__ . '/');
//enable error_reporting to see php errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
//set include path, this is where included classes will be found
$includePaths = implode(PATH_SEPARATOR, array(
    BP . 'app/model',
    BP . 'app/model/entity',
    BP . 'app/controller',
));
//  var_dump($includePaths);
set_include_path($includePaths);
//register autoloader, to auto-include classes when needed
spl_autoload_register(function ($class) {
    $classPath = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
    if ($file = stream_resolve_include_path($classPath)) {
        include $file;
        return true;
    }
    return false;
});
//start app
App::start();