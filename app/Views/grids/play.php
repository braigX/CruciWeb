<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-management">
    <h1>Play Grid</h1>
    <p>Fill in the crossword puzzle below. Use the hints provided!</p>

    <div class="grid-container">
        <!-- Crossword Grid -->
        <div class="crossword-grid">
            <form id="playGridForm">
                <table>
                    <?php foreach ($game['words'] as $rowIndex => $row): ?>
                        <tr>
                            <?php foreach ($row as $colIndex => $cell): ?>
                                <td>
                                    <input type="text" 
                                           maxlength="1"
                                           data-row="<?= $rowIndex; ?>"
                                           data-col="<?= $colIndex; ?>"
                                           data-value="<?= htmlspecialchars($cell); ?>"
                                           <?= $cell === "" ? 'style="background-color:black;" disabled' : 'required'; ?>>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <button type="button" id="validateGrid" class="validate-button">Validate Answers</button>
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
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
