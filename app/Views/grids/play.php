<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-management">
    <h1>Play Grid</h1>
    <p>Fill in the crossword puzzle below. Use the hints provided!</p>

    <div class="grid-container">
        <div class="crossword-grid">
            <form id="playGridForm">
                <table>
                    <tr>
                        <td></td>
                        <?php foreach (range('A', chr(65 + count($game['words'][0]) - 1)) as $colHeader): ?>
                            <th><?= $colHeader; ?></th>
                        <?php endforeach; ?>
                    </tr>

                    <?php foreach ($game['words'] as $rowIndex => $row): ?>
                        <tr>
                            <th><?= $rowIndex + 1; ?></th>
                            
                            <?php foreach ($row as $colIndex => $cell): 
                                $encodedValue = base64_encode($cell);
                            ?>
                                <td>
                                    <input 
                                        type="text" 
                                        maxlength="1"
                                        data-row="<?= $rowIndex; ?>"
                                        data-col="<?= $colIndex; ?>"
                                        data-value="<?= htmlspecialchars($encodedValue); ?>" 
                                        <?= $cell === "" ? 'style="background-color:black;" disabled' : 'required'; ?>
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

        <!-- Clues -->
        <div class="clues">
            <h2>Clues</h2>
            <h3>Across</h3>
            <ul>
                <?php foreach ($hints['hints']['row'] as $rowNum => $clue): ?>
                    <li> <strong> <?= htmlspecialchars($rowNum); ?>.</strong> <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>

            <h3>Down</h3>
            <ul>
                <?php foreach ($hints['hints']['col'] as $colLetter => $clue): ?>
                    <li><strong> <?= htmlspecialchars($colLetter); ?>.</strong> <?= htmlspecialchars($clue); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <section class="attempts">
        <h2>Successful Attempts</h2>
        <?php if (!empty($attempts)): ?>
            <table class="attempts-table">
                <thead>
                    <tr>
                        <th>Player</th>
                        <th>Completion Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attempts as $attempt): ?>
                        <tr>
                            <td><?= htmlspecialchars($attempt['username'] ?? 'Anonymous'); ?></td>
                            <td><?= htmlspecialchars($attempt['finished_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No successful attempts yet. Be the first to complete this grid!</p>
        <?php endif; ?>
    </section>
    <div id="loginModal" class="modal hidden">
        <div class="modal-content">
            <h2>You Are Not Logged In</h2>
            <p>To fully enjoy CruciWeb, please log in or play as an anonymous user.</p>
            <div class="modal-buttons">
                <button id="playAnonymous" class="button">Play as Anonymous</button>
                <button id="goToLogin" class="button">Login</button>
            </div>
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
    </script>
    <script src="/public/js/play.js"></script>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
