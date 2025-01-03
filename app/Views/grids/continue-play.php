<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-management">
    <h1>Continue Playing</h1>
    <p>Game: <strong> <?= htmlspecialchars($game['name']); ?> </strong> (Difficulty: <strong> <?= htmlspecialchars($game['difficulty']); ?></strong>)</p>
    <p>Fill in the crossword puzzle below. Use the hints provided!</p>

    <div class="grid-container">
        <div class="crossword-grid">
            <form id="playGridForm" data-game-id="<?= htmlspecialchars($gameId); ?>">
                <table>
                    <tr>
                        <td></td>
                        <?php foreach (range('A', chr(65 + count($game['words'][0]) - 1)) as $colHeader): ?>
                            <th><?= $colHeader; ?></th>
                        <?php endforeach; ?>
                    </tr>

                    <?php 
                    $progressData = json_decode($attempt['progress'] ?? '[]', true);

                    foreach ($game['words'] as $rowIndex => $row): ?>
                        <tr>
                            <th><?= $rowIndex + 1; ?></th>
                            
                            <?php foreach ($row as $colIndex => $cell): 
                                $progressValue = '';
                                foreach ($progressData as $progress) {
                                    if ($progress['row'] == $rowIndex && $progress['col'] == $colIndex) {
                                        $progressValue = $progress['value'];
                                        break;
                                    }
                                }

                                $encodedValue = base64_encode($cell);
                            ?>
                                <td>
                                    <input 
                                        type="text" 
                                        maxlength="1"
                                        data-row="<?= $rowIndex; ?>"
                                        data-col="<?= $colIndex; ?>"
                                        data-value="<?= htmlspecialchars($encodedValue); ?>" 
                                        value="<?= htmlspecialchars(($progressValue !== '' && $progressValue !== '+') ? $progressValue : ''); ?>"
                                        <?= $cell === "" ? 'style="background-color:black;" disabled' : ""; ?>
                                    >
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
        <div class="clues">
            <h2>Clues</h2>
            <h3>Across</h3>
            <ul>
                <?php foreach ($hints['hints']['row'] as $rowNum => $clue): ?>
                    <li><strong> <?= htmlspecialchars($rowNum); ?>.</strong>  <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>

            <h3>Down</h3>
            <ul>
                <?php foreach ($hints['hints']['col'] as $colLetter => $clue): ?>
                    <li><strong> <?= htmlspecialchars($colLetter); ?>.</strong>  <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div id="solvedModal" class="modal hidden">
        <div class="modal-content">
            <img src="/public/imgs/prize.png" alt="Success" class="modal-icon">
            <h2>Congratulations!</h2>
            <p>You successfully solved the crossword puzzle!</p>
            <button id="solvedClose" class="button">Close</button>
        </div>
    </div>

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
