<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-management">
    <h1>Continue Playing</h1>
    <p>Game: <?= htmlspecialchars($game['name']); ?> (Difficulty: <?= htmlspecialchars($game['difficulty']); ?>)</p>
    <p>Fill in the crossword puzzle below. Use the hints provided!</p>

    <div class="grid-container">
        <!-- Crossword Grid -->
        <div class="crossword-grid">
            <form id="playGridForm" data-game-id="<?= htmlspecialchars($gameId); ?>">
                <table>
                    <?php 
                    // Convert progress JSON into a usable format
                    $progressData = json_decode($attempt['progress'] ?? '[]', true);

                    // Iterate over rows
                    foreach ($game['words'] as $rowIndex => $row): ?>
                        <tr>
                            <?php foreach ($row as $colIndex => $cell): 
                                // Find progress value for the current cell
                                $progressValue = '';
                                foreach ($progressData as $progress) {
                                    if ($progress['row'] == $rowIndex && $progress['col'] == $colIndex) {
                                        $progressValue = $progress['value'];
                                        break;
                                    }
                                }
                            ?>
                                <td>
                                    <input 
                                        type="text" 
                                        maxlength="1"
                                        data-row="<?= $rowIndex; ?>"
                                        data-col="<?= $colIndex; ?>"
                                        value="<?= htmlspecialchars(($progressValue !== '' && $progressValue !== '+') ? $progressValue : ''); ?>"
                                        <?= $cell === "" ? 'style="background-color:black;" disabled' : ""; ?>                                    >
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <button type="button" id="validateGrid" class="validate-button">Validate Answers</button>
                <?php if (Session::has('user')): ?>
                    <button type="button" id="saveProgress" class="save-button">Save Progress</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Clues -->
        <div class="clues">
            <h2>Clues</h2>
            <h3>Across</h3>
            <ul>
                <?php foreach ($hints['hints']['row'] as $rowNum => $clue): ?>
                    <li><?= htmlspecialchars($rowNum); ?>. <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>

            <h3>Down</h3>
            <ul>
                <?php foreach ($hints['hints']['col'] as $colLetter => $clue): ?>
                    <li><?= htmlspecialchars($colLetter); ?>. <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Solved popup -->
    <div id="solvedModal" class="modal hidden">
        <div class="modal-content">
            <img src="/public/imgs/prize.png" alt="Success" class="modal-icon">
            <h2>Congratulations!</h2>
            <p>You successfully solved the crossword puzzle!</p>
            <button id="solvedClose" class="button">Close</button>
        </div>
    </div>

    <!-- Not Solved popup -->
    <div id="notSolvedModal" class="modal hidden">
        <div class="modal-content">
            <img src="/public/imgs/oops.png" alt="Error" class="modal-icon">
            <h2>Oops!</h2>
            <p>Some answers are incorrect. Keep trying!</p>
            <button id="notSolvedClose" class="button">Close</button>
        </div>
    </div>

    <script>
        const userLoggedIn = <?= json_encode(Session::has('user')); ?>;
        const gameId = <?= json_encode($gameId); ?>;
        const savedProgress = <?= json_encode($progressData); ?>;
    </script>
    <script src="/public/js/play.js"></script>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
