<?php

class ErrorController {
    public function show404() {
        http_response_code(404);
        require_once ROOT . 'app/Views/errors/404.php';
    }

    public function show500() {
        http_response_code(500);
        require_once ROOT . 'app/Views/errors/500.php';
    }

    public function showCustom($errorCode, $message = '') {
        http_response_code($errorCode);
        require_once ROOT . 'app/Views/errors/custom.php';
    }
}
