<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main>
    <h1>Manage Grids</h1>
    <p>Admin Panel: View and remove crossword grids.</p>

    <table>
        <thead>
            <tr>
                <th>Grid ID</th>
                <th>Grid Name</th>
                <th>Dimensions</th>
                <th>Difficulty</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($grids as $grid): ?>
                <tr>
                    <td>#<?= htmlspecialchars($grid['id']); ?></td>
                    <td><?= htmlspecialchars($grid['name']); ?></td>
                    <td><?= htmlspecialchars($grid['dimensions']); ?></td>
                    <td><?= htmlspecialchars($grid['difficulty']); ?></td>
                    <td><?= htmlspecialchars($grid['created_at']); ?></td>
                    <td>
                        <a href="/admin/grids/delete?id=<?= $grid['id']; ?>" onclick="return confirm('Are you sure you want to delete this grid?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
