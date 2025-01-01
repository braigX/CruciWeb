<?php

return [
    '/' => ['HomeController', 'index'],
    // grids
    '/grids' => ['GridController', 'browse'],
    '/saved-grids' => ['GridController', 'browse_saved'],
    '/grids/create' => ['GridController', 'create'],
    '/grids/play' => ['GridController', 'play'],
    '/grids/continiue-play' => ['GridController', 'ContinuePlay'],
    // auth
    '/login' => ['AuthController', 'login'],
    '/login/submit' => ['AuthController', 'authenticate'],
    '/signup' => ['AuthController', 'signup'],
    '/signup/submit' => ['AuthController', 'register'],
    '/logout' => ['AuthController', 'logout'],
    // admin
    '/admin/users' => ['UserController', 'index'],
    '/admin/users/add' => ['UserController', 'add'],
    '/admin/users/edit' => ['UserController', 'edit'],
    '/admin/users/delete' => ['UserController', 'delete'],
    '/admin/grids' => ['GridController', 'adminIndex'],
    '/admin/grids/delete' => ['GridController', 'delete'],
    // APIs
    '/api/grids/create' => ['GridController', 'storeApi'],
    '/api/grids/validate' => ['GridController', 'validateAnswers'],
    '/api/grids/saveProgress' => ['GridController', 'saveProgress'],
    //install
    '/install' => ['InstallController', 'showForm'],
    '/install/submit' => ['InstallController', 'submitForm'],
];