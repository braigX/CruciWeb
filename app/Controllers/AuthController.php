<?php
require_once ROOT . 'app/Models/User.php';
require_once ROOT . 'app/Session.php';

class AuthController {
    public function login() {

        require_once ROOT . 'app/Views/auth/login.php';
    }
    public function authenticate() {

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        $errors = [];

        if (!$username) {
            $errors[] = "Username is required.";
        }

        if (!$password) {
            $errors[] = "Password is required.";
        }

        if (!empty($errors)) {

            Session::set('errors', $errors);
            header('Location: /login');
            exit;
        }

        $user = User::authenticate($username, $password);

        if ($user) {

            Session::set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);
            header('Location: /');
            exit;
        } else {

            Session::set('errors', ['Invalid username or password.']);
            header('Location: /login');
            exit;
        }
    }

    public function signup() {

        require_once ROOT . 'app/Views/auth/signup.php';
    }

    public function register() {

        $name = $_POST['name'] ?? null;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirm_password'] ?? null;

        if (!$name || !$username || !$password || !$confirmPassword) {
            echo "All fields are required.";
            return;
        }

        if ($password !== $confirmPassword) {
            echo "Passwords do not match.";
            return;
        }

        $user = User::register($name, $username, $password);

        if ($user) {

            Session::set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);

            header('Location: /');
            exit;
        } else {
            echo "Registration failed. Username or email may already be taken.";
        }
    }

    public function logout() {

        Session::destroy();
        header('Location: /login');
        exit;
    }
}