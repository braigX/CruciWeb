<?php

require_once ROOT . 'config/config.php';

class Hint {
    public static function create($gameId, $hintsData) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("INSERT INTO hints (game_id, hints)
                                  VALUES (:game_id, :hints)");
            $stmt->execute([
                ':game_id' => $gameId,
                ':hints' => json_encode($hintsData),
            ]);

            return true;
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function findByGameId($gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT * FROM hints WHERE game_id = :game_id");
            $stmt->execute([':game_id' => $gameId]);

            $hints = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($hints) {
                $hints['hints'] = json_decode($hints['hints'], true); 
            }

            return $hints;
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return null;
        }
    }
}