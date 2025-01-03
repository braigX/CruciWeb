<?php

require_once ROOT . 'app/Models/User.php';

class UserController {
    public function index() {

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

            if (!$userDetails) {
                echo "<p class='error'>User not found.</p>";
                return;
            }
            require_once ROOT . 'app/Views/admin/users/edit.php';
        }
    }

    public function delete($params) {
        $userId = $params['id'] ?? null;

        if (!$userId) {
            echo "User ID is required.";
            return;
        }

        try {

            $db = new PDO(DSN, DB_USER, DB_PASS);
            $db->beginTransaction();

            $stmt = $db->prepare("SELECT id FROM games WHERE creator_id = :userId");
            $stmt->execute([':userId' => $userId]);
            $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($games) {
                $gameIds = array_column($games, 'id');
                $inClause = implode(',', array_fill(0, count($gameIds), '?'));

                $deleteHints = $db->prepare("DELETE FROM hints WHERE game_id IN ($inClause)");
                $deleteHints->execute($gameIds);

                $deleteGames = $db->prepare("DELETE FROM games WHERE id IN ($inClause)");
                $deleteGames->execute($gameIds);
            }

            $deleteAttempts = $db->prepare("DELETE FROM user_attempts WHERE user_id = :userId");
            $deleteAttempts->execute([':userId' => $userId]);

            $deleteUser = $db->prepare("DELETE FROM users WHERE id = :userId");
            $deleteUser->execute([':userId' => $userId]);

            $db->commit();

            header("Location: /admin/users");
            exit;
        } catch (PDOException $e) {
            $db->rollBack();
            Logger::error("Error deleting user: " . $e->getMessage());
            echo "Failed to delete user and related data.";
        }
    }

}