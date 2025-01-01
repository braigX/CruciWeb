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
            http_response_code(404);
            Logger::error("Method '$method' not found in $controllerName.");
            echo "Error 404: Method not found.";
            exit;
        }
    } else {
        http_response_code(404);
        Logger::error("Controller '$controllerName' not found.");
        echo "Error 404: Controller not found.";
        exit;
    }
} else {
    http_response_code(404);
    Logger::error("Route '$url' not found.");
    echo "Error 404: Route not found.";
    exit;
}
