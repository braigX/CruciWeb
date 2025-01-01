<?php
require_once ROOT . 'app/Models/Grid.php';
require_once ROOT . 'app/Models/Hint.php';


class GridController {
    public function browse() {
        // Fetch all grids from the database
        $games = Grid::getAllGames();

        // Pass games to the view
        require_once ROOT . 'app/Views/grids/browse.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission (validate and process data)
            $name = $_POST['name'];
            $dimensions = $_POST['dimensions'];
            $difficulty = $_POST['difficulty'];
            $cells = json_decode($_POST['cells'], true);
            $clues = json_decode($_POST['clues'], true);

            // (Process and validate grid data)

            echo "Grid created successfully!";
        } else {
            // Render the create view
            require_once ROOT . 'app/Views/grids/create.php';
        }
    }

    public function play($params) {
        if (isset($params['game'])) {
            $gameId = $params['game'];

            // Fetch game and hints data
            $game = Grid::findById($gameId);
            $hints = Hint::findByGameId($gameId);

            if (!$game || !$hints) {
                echo "Invalid game ID.";
                http_response_code(404);
                return;
            }

            // Pass data to the view
            require_once ROOT . 'app/Views/grids/play.php';
        } else {
            echo "No grid ID provided.";
        }
    }

    public function storeApi() {
        // Ensure the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            http_response_code(405);
            return;
        }

        // Read the incoming JSON payload
        $input = json_decode(file_get_contents('php://input'), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data.']);
            http_response_code(400);
            return;
        }

        // Validate required fields
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

        // Save grid and hints to the database
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

        // Fetch game data
        $game = Grid::findById($gameId);
        if (!$game) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid game ID.']);
            http_response_code(404);
            return;
        }

        $correctAnswers = $game['words'];
        $isValid = true;

        // Validate answers
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
            echo json_encode(['status' => 'success', 'message' => 'All answers are correct!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Some answers are incorrect.']);
        }
    }

    public function adminIndex() {
        // Check if user is an admin
        $user = Session::get('user');
        if (!$user || $user['role'] !== 'admin') {
            echo "Access denied.";
            http_response_code(403);
            return;
        }

        // Fetch all grids
        $grids = Grid::getAll();
        require_once ROOT . 'app/Views/admin/grids/index.php';
    }

    public function delete($params) {
        // Check if user is an admin
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
}
