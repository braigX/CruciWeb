<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="auth-page">
    <h1>Sign Up</h1>
    <form action="/signup/submit" method="POST" class="auth-form">
        <label for="name">Full name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="username">Username/Email:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username or email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>

        <button type="submit" class="auth-button">Register</button>
    </form>
    <p>Already have an account? <a href="/login">Login here</a></p>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
