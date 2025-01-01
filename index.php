<?php
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

// Load configurations and required files
$routes = require_once ROOT . 'config/routes.php';
require_once ROOT . 'app/Logger.php';
require_once ROOT . 'config/config.php';
require_once ROOT . 'app/Session.php';

$url = isset($_GET['url']) ? '/' . rtrim($_GET['url'], '/') : '/';

// Remove the `url` parameter from `$_GET` before passing
$params = $_GET;
unset($params['url']);

try {
    // Check if the route matches
    if (array_key_exists($url, $routes)) {
        [$controllerName, $method] = $routes[$url];
        $controllerPath = ROOT . "app/Controllers/$controllerName.php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();

            if (method_exists($controller, $method)) {
                Logger::info("Routing to $controllerName::$method with params: " . json_encode($params));
                call_user_func_array([$controller, $method], [$params]); // Pass params as argument
                exit;
            } else {
                throw new Exception("Method '$method' not found in $controllerName.", 404);
            }
        } else {
            throw new Exception("Controller '$controllerName' not found.", 404);
        }
    } else {
        throw new Exception("Route '$url' not found.", 404);
    }
} catch (Exception $e) {
    Logger::error($e->getMessage());

    require_once ROOT . 'app/Controllers/ErrorController.php';
    $errorController = new ErrorController();

    switch ($e->getCode()) {
        case 404:
            $errorController->show404();
            break;
        case 500:
            $errorController->show500();
            break;
        default:
            $errorController->showCustom($e->getCode(), $e->getMessage());
            break;
    }
    exit;
}


