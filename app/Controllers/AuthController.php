<?php
require_once ROOT . 'app/Models/User.php';
require_once ROOT . 'app/Session.php';

class AuthController {
    public function login() {
        // Render the login view
        require_once ROOT . 'app/Views/auth/login.php';
    }

    public function authenticate() {
        // Handle form submission
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            echo "Both username and password are required.";
            return;
        }

        // Authenticate the user
        $user = User::authenticate($username, $password);

        if ($user) {
            // Set user session
            Session::set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);
            header('Location: /');
        } else {
            // Login failed, show error
            echo "Invalid username or password.";
        }
    }

    public function signup() {
        // Render the signup view
        require_once ROOT . 'app/Views/auth/signup.php';
    }

    public function register() {
        // Handle form submission
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
    
        // Register the user
        $user = User::register($name, $username, $password);
    
        if ($user) {
            // Set session for the new user
            Session::set('user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
            ]);
    
            // Redirect to home page
            header('Location: /');
            exit;
        } else {
            echo "Registration failed. Username or email may already be taken.";
        }
    }
    


    public function logout() {
        // Destroy the session and redirect to login page
        Session::destroy();
        header('Location: /login');
        exit;
    }
}
