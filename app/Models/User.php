<?php
class User {
    public static function authenticate($username, $password) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {

                return $user;
            }

            return false; 
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function register($name, $username, $password) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);

            if ($stmt->fetchColumn() > 0) {
                return false; 
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $db->prepare("INSERT INTO users (name, username, password) VALUES (:name, :username, :password)");
            $stmt->execute([':name' => $name, ':username' => $username, ':password' => $hashedPassword]);

            $userId = $db->lastInsertId();
            return self::findById($userId);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function getAll() {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->query("SELECT id, name, username, role, created_at FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function create($name, $username, $role, $password) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $db->prepare("INSERT INTO users (name, username, role, password) VALUES (:name, :username, :role, :password)");
            return $stmt->execute([
                ':name' => $name,
                ':username' => $username,
                ':role' => $role,
                ':password' => $hashedPassword
            ]);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function findById($id) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->prepare("SELECT id, name, username, role FROM users WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return null;
        }
    }

    public static function update($id, $name, $username, $role) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->prepare("UPDATE users SET name = :name, username = :username, role = :role WHERE id = :id");
            return $stmt->execute([
                ':id' => $id,
                ':name' => $name,
                ':username' => $username,
                ':role' => $role
            ]);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function delete($id) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }
}