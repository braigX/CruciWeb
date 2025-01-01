<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main>
    <h1>User Management</h1>
    <a href="/admin/users/add" class="button">Add User</a>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']); ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                    <td><?= htmlspecialchars($user['role']); ?></td>
                    <td>
                        <a href="/admin/users/edit?id=<?= $user['id']; ?>">Edit</a>
                        <a href="/admin/users/delete?id=<?= $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
