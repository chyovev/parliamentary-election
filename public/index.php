<?php
require_once('../src/core/bootstrap.php');

if ($params = Router::getCurrentRequestParams()) {
    $controller      = $params['controller'];
    $action          = $params['action'];
    $controllerClass = ucfirst($controller) . 'Controller';

    if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
        try {
            $smarty = Initializer::smarty();
            $class  = new $controllerClass($smarty);
            $class->{$action}();
            die;
        }
        catch (Exception $e) {
            throw $e;
        }
    }
}

die('404');
?>