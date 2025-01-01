<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="grid-browse">
    <h1>Browse Grids</h1>
    <p>Explore publicly available grids created by users.</p>
    <div class="grid-list">
        <?php if (!empty($games)): ?>
            <?php foreach ($games as $game): ?>
                <div class="grid-item">
                    <h2><?= htmlspecialchars($game['name']); ?></h2>
                    <p><strong>Difficulty:</strong> <?= htmlspecialchars($game['difficulty']); ?></p>
                    <p><strong>Created by:</strong> <?= htmlspecialchars($game['creator']); ?></p>
                    <a href="/grids/play?game=<?= htmlspecialchars($game['id']); ?>" class="button">Play</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No grids available. Be the first to create one!</p>
        <?php endif; ?>
    </div>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
