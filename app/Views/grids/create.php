<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-creation">
    <h1>Create a New Grid</h1>
    <p>Design your own crossword puzzle grid and share it with others!</p>

    <form id="gridForm" action="/grids/create/submit" method="POST">
        <!-- Input Fields -->
        <div class="form-section">
            <label for="name">Grid Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter grid name" required>

            <label for="dimensions">Dimensions (e.g., 5x5):</label>
            <input type="text" id="dimensions" name="dimensions" placeholder="e.g., 5x5" required>
            
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="expert">Expert</option>
            </select>

            <button type="button" id="generateGrid" class="generate-button">Generate Grid</button>
        </div>

        <!-- Dynamic Grid and Hints -->
        <div class="grid-container">
            <div id="crosswordGrid" class="grid-section">
                <!-- Crossword grid dynamically generated here -->
            </div>
            <div class="hints-section">
                <h2>Hints</h2>
                <div id="hintsRows">
                    <h3>Rows</h3>
                    <ul id="rowHints"></ul>
                </div>
                <div id="hintsCols">
                    <h3>Columns</h3>
                    <ul id="colHints"></ul>
                </div>
            </div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" id="gridData" name="cells">
        <input type="hidden" id="hintData" name="clues">

        <button type="submit" id="submitGrid" class="create-button">Create Grid</button>
    </form>
</main>
<script src="/public/js/scripts.js"></script>
<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
