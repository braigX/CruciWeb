<?php

require_once ROOT . 'app/Models/User.php';

class UserController {
    public function index() {
        // Fetch all users
        $users = User::getAll();
        require_once ROOT . 'app/Views/admin/users/index.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $name = $_POST['name'] ?? null;
            $role = $_POST['role'] ?? 'registered';
            $password = $_POST['password'] ?? null;

            if (!$username || !$name || !$password) {
                echo "All fields are required.";
                return;
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $result = User::create($name, $username, $role, $hashedPassword);

            if ($result) {
                header("Location: /admin/users");
                exit;
            } else {
                echo "Failed to add user.";
            }
        } else {
            require_once ROOT . 'app/Views/admin/users/add.php';
        }
    }

    public function edit($params) {
        $userId = $params['id'] ?? null;

        if (!$userId) {
            echo "Invalid user ID.";
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? null;
            $name = $_POST['name'] ?? null;
            $role = $_POST['role'] ?? 'registered';
    
            if (!$username || !$name) {
                echo "<p class='error'>All fields are required.</p>";
                return;
            }
            $result = User::update($userId, $name, $username, $role);
    
            if ($result) {
                header("Location: /admin/users");
                exit;
            } else {
                echo "<p class='error'>Failed to update user. Please try again later.</p>";
            }
        } else {
            $userDetails = User::findById($userId);

            //  print_r($user);
            //  exit;

            if (!$userDetails) {
                echo "<p class='error'>User not found.</p>";
                return;
            }
            require_once ROOT . 'app/Views/admin/users/edit.php';
        }
    }
    

    public function delete($params) {
        $userId = $params['id'] ?? null;

        if ($userId && User::delete($userId)) {
            header("Location: /admin/users");
            exit;
        } else {
            echo "Failed to delete user.";
        }
    }
}
