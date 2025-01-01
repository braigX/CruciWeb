<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main>
    <h1>My Attempts</h1>
    <?php if (!empty($attempts)): ?>
        <table>
            <thead>
                <tr>
                    <th>Game Name</th>
                    <th>Difficulty</th>
                    <th>Status</th>
                    <th>Started At</th>
                    <th>Finished At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attempts as $attempt): ?>
                    <tr>
                        <td><?= htmlspecialchars($attempt['game_name']); ?></td>
                        <td><?= htmlspecialchars($attempt['difficulty']); ?></td>
                        <td><?= $attempt['completed'] ? 'Completed' : 'In Progress'; ?></td>
                        <td><?= htmlspecialchars($attempt['started_at']); ?></td>
                        <td><?= htmlspecialchars($attempt['finished_at'] ?? 'N/A'); ?></td>
                        <td>
                            <a href="/grids/continiue-play?game=<?= htmlspecialchars($attempt['game_id']); ?>">Resume</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No attempts found. Start playing a game!</p>
    <?php endif; ?>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
