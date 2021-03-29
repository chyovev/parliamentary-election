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

header('HTTP/1.1 404 Not Found');
die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL was not found on this server.</p>
</body></html>
');

?>