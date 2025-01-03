<?php
require_once ROOT . 'app/Models/Grid.php';
require_once ROOT . 'app/Models/Hint.php';
require_once ROOT . 'app/Models/UserAttempt.php';

class GridController {
    public function browse() {

        $games = Grid::getAllGames();

        require_once ROOT . 'app/Views/grids/browse.php';
    }

    public function browse_saved() {
        $useri = Session::get('user');
        if(!$useri){
            header('Location: /login');
            exit;
        }
        $userId = $useri['id'];
        $attempts = UserAttempt::getUserAttempts($userId);

        require_once ROOT . 'app/Views/grids/attempts.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'];
            $dimensions = $_POST['dimensions'];
            $difficulty = $_POST['difficulty'];
            $cells = json_decode($_POST['cells'], true);
            $clues = json_decode($_POST['clues'], true);

            echo "Grid created successfully!";
        } else {

            require_once ROOT . 'app/Views/grids/create.php';
        }
    }

    public function play($params) {
        if(Session::get('user') && Session::get('user')['role'] === 'admin'){
            echo 'You are not allowed to Play Games.';
            exit;
        }
        if (isset($params['game'])) {
            $gameId = $params['game'];

            $game = Grid::findById($gameId);
            $hints = Hint::findByGameId($gameId);

            if (!$game || !$hints) {
                echo "Invalid game ID.";
                http_response_code(404);
                return;
            }

            $attempts = UserAttempt::getSuccessfulAttempts($gameId);

            require_once ROOT . 'app/Views/grids/play.php';
        } else {
            echo "No grid ID provided.";
        }
    }

    public function ContinuePlay($params) {
        $gameId = $params['game'] ?? null;

        if (!$gameId) {
            echo "Game ID is required.";
            return;
        }

        $userId = Session::get('user')['id'] ?? null;

        if (!$userId) {
            header('Location: /login');
            exit;
        }

        $game = Grid::findById($gameId);
        $hints = Hint::findByGameId($gameId);

        if (!$game || !$hints) {
            echo "Game or hints not found.";
            return;
        }

        $attempt = UserAttempt::getUserAttempt($userId, $gameId);

        require_once ROOT . 'app/Views/grids/continue-play.php';
    }

    public function storeApi() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
            http_response_code(400);
            return;
        }

        $name = $input['name'] ?? null;
        $dimensions = $input['dimensions'] ?? null;
        $difficulty = $input['difficulty'] ?? null;
        $gridData = $input['cells'] ?? null;
        $hintsData = $input['clues'] ?? null;
        $creatorId = $input['creator_id'] ?? null;

        if (!$name || !$dimensions || !$difficulty || !$gridData || !$hintsData || !$creatorId) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            http_response_code(400);
            return;
        }

        $gameId = Grid::create($creatorId, $name, $dimensions, $difficulty, $gridData);

        if ($gameId) {
            Hint::create($gameId, $hintsData);
            echo json_encode(['status' => 'success', 'message' => 'Grid created successfully!', 'game_id' => $gameId]);
            http_response_code(201);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save the grid.']);
            http_response_code(500);
        }
    }

    public function validateAnswers() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
            http_response_code(400);
            return;
        }

        $gameId = $input['gameId'] ?? null;
        $answers = $input['answers'] ?? null;

        if (!$gameId || !$answers) {
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
            http_response_code(400);
            return;
        }

        $game = Grid::findById($gameId);
        if (!$game) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid game ID.']);
            http_response_code(404);
            return;
        }

        $correctAnswers = $game['words'];
        $isValid = true;

        foreach ($answers as $answer) {
            $row = $answer['row'];
            $col = $answer['col'];
            $value = $answer['value'];

            if ($correctAnswers[$row][$col] !== $value) {
                $isValid = false;
                break;
            }
        }

        if ($isValid) {
            $userId = Session::get('user')['id'] ?? null;
            $result = UserAttempt::saveCompletion($userId, $gameId);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'All answers are correct and attempt saved as completed!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'All answers are correct, but the attempt could not be saved.']);
                http_response_code(500);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Some answers are incorrect.']);
        }
    }

    public function adminIndex() {

        $user = Session::get('user');
        if (!$user || $user['role'] !== 'admin') {
            echo "Access denied.";
            http_response_code(403);
            return;
        }

        $grids = Grid::getAll();
        require_once ROOT . 'app/Views/admin/grids/index.php';
    }

    public function delete($params) {

        $user = Session::get('user');
        if (!$user || $user['role'] !== 'admin') {
            echo "Access denied.";
            http_response_code(403);
            return;
        }

        $gridId = $params['id'] ?? null;

        if ($gridId && Grid::delete($gridId)) {
            header("Location: /admin/grids");
            exit;
        } else {
            echo "Failed to delete grid.";
        }
    }

    public function saveProgress() {
        if (!Session::has('user')) {
            echo json_encode(['error' => 'Unauthorized']);
            http_response_code(401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $userId = Session::get('user')['id'];
        $gameId = $data['game_id'] ?? null;
        $progress = $data['progress'] ?? null;

        if (!$gameId || !$progress) {
            echo json_encode(['error' => 'Game ID and progress data are required.']);
            http_response_code(400);
            return;
        }

        $result = UserAttempt::saveProgress($userId, $gameId, $progress);

        if ($result) {
            echo json_encode(['message' => 'Progress saved successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to save progress.']);
            http_response_code(500);
        }
    }

}