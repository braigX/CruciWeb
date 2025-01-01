<?php
require_once ROOT . 'config/config.php';

class Grid {
    public static function getAllGames() {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            // Fetch all games
            $stmt = $db->query("SELECT g.id, g.name, g.difficulty, u.name AS creator
                                FROM games g
                                JOIN users u ON g.creator_id = u.id
                                ORDER BY g.created_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function create($creatorId, $name, $dimensions, $difficulty, $gridData) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            // Insert game metadata
            $stmt = $db->prepare("INSERT INTO games (creator_id, name, dimensions, difficulty, words)
                                  VALUES (:creator_id, :name, :dimensions, :difficulty, :words)");
            $user = Session::get('user');
            $stmt->execute([
                ':creator_id' => $user['id'],
                ':name' => $name,
                ':dimensions' => $dimensions,
                ':difficulty' => $difficulty,
                ':words' => json_encode($gridData),
            ]);

            return $db->lastInsertId(); // Return the new game ID
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function findById($gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT * FROM games WHERE id = :id");
            $stmt->execute([':id' => $gameId]);

            $game = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($game) {
                $game['words'] = json_decode($game['words'], true); // Decode the grid structure
            }

            return $game;
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return null;
        }
    }

    public static function getAll() {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->query("SELECT id, name, dimensions, difficulty, created_at FROM games");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function delete($id) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
    
            // Delete related hints
            $stmt = $db->prepare("DELETE FROM hints WHERE game_id = :game_id");
            $stmt->execute([':game_id' => $id]);
    
            // Delete the game
            $stmt = $db->prepare("DELETE FROM games WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }
    
}
