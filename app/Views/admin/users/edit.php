<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main>
    <h1>Edit User</h1>
    <form action="/admin/users/edit?id=<?= $userDetails['id']; ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($userDetails['username']); ?>" required>

        <label for="name">Name:</label>
        <input type="name" id="name" name="name" value="<?= htmlspecialchars($userDetails['name']); ?>" required>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="registered" <?= $userDetails['role'] === 'registered' ? 'selected' : ''; ?>>Registered</option>
            <option value="admin" <?= $userDetails['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>

        <button type="submit">Update User</button>
    </form>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
