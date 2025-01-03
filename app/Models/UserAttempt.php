<?php

class UserAttempt {

    public static function getSuccessfulAttempts($gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("
                SELECT ua.*, u.name AS username
                FROM user_attempts ua
                LEFT JOIN users u ON ua.user_id = u.id
                WHERE ua.game_id = :game_id AND ua.completed = 1
                ORDER BY ua.finished_at DESC
            ");
            $stmt->execute([':game_id' => $gameId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function saveCompletion($userId, $gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT id FROM user_attempts WHERE game_id = :game_id AND user_id = :user_id");
            $stmt->execute([':game_id' => $gameId, ':user_id' => $userId]);
            $attemptId = $stmt->fetchColumn();

            if ($attemptId) {

                $stmt = $db->prepare("UPDATE user_attempts SET completed = 1, finished_at = NOW() WHERE id = :id");
                return $stmt->execute([':id' => $attemptId]);
            } else {

                $stmt = $db->prepare("INSERT INTO user_attempts (user_id, game_id, progress, completed, finished_at) 
                                      VALUES (:user_id, :game_id, '[]', 1, NOW())");
                return $stmt->execute([
                    ':user_id' => $userId,
                    ':game_id' => $gameId,
                ]);
            }
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function getUserAttempt($userId, $gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("
                SELECT progress, completed
                FROM user_attempts
                WHERE user_id = :user_id AND game_id = :game_id
            ");
            $stmt->execute([':user_id' => $userId, ':game_id' => $gameId]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return null;
        }
    }

    public static function getUserAttempts($userId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("
                SELECT 
                    ua.id AS attempt_id,
                    g.id AS game_id,
                    g.name AS game_name,
                    g.difficulty,
                    ua.progress,
                    ua.completed,
                    ua.started_at,
                    ua.finished_at
                FROM user_attempts ua
                JOIN games g ON ua.game_id = g.id
                WHERE ua.user_id = :user_id
                ORDER BY ua.started_at DESC
            ");
            $stmt->execute([':user_id' => $userId]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return [];
        }
    }

    public static function saveProgress($userId, $gameId, $progress, $completed = 0) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);

            $stmt = $db->prepare("SELECT id FROM user_attempts WHERE user_id = :user_id AND game_id = :game_id");
            $stmt->execute([':user_id' => $userId, ':game_id' => $gameId]);

            $attemptId = $stmt->fetchColumn();

            if ($attemptId) {

                $stmt = $db->prepare("UPDATE user_attempts 
                                      SET progress = :progress, 
                                          completed = :completed,
                                          finished_at = CASE WHEN :completed = 1 THEN NOW() ELSE NULL END
                                      WHERE id = :id");
                return $stmt->execute([
                    ':progress' => json_encode($progress),
                    ':completed' => $completed,
                    ':id' => $attemptId
                ]);
            } else {

                $stmt = $db->prepare("INSERT INTO user_attempts (user_id, game_id, progress, completed) 
                                      VALUES (:user_id, :game_id, :progress, :completed)");
                return $stmt->execute([
                    ':user_id' => $userId,
                    ':game_id' => $gameId,
                    ':progress' => json_encode($progress),
                    ':completed' => $completed
                ]);
            }
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return false;
        }
    }

    public static function getProgress($userId, $gameId) {
        try {
            $db = new PDO(DSN, DB_USER, DB_PASS);
            $stmt = $db->prepare("SELECT progress FROM user_attempts WHERE user_id = :user_id AND game_id = :game_id");
            $stmt->execute([':user_id' => $userId, ':game_id' => $gameId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? json_decode($result['progress'], true) : null;
        } catch (PDOException $e) {
            Logger::error("Database error: " . $e->getMessage());
            return null;
        }
    }
}