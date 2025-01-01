<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main>
    <h1>Add User</h1>
    <form action="/admin/users/add" method="POST">
        <label for="name">Name:</label>
        <input type="name" id="name" name="name" required>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="registered">Registered</option>
            <option value="admin">Admin</option>
        </select>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Add User</button>
    </form>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
