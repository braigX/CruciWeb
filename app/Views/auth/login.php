<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>

<main class="auth-page">
    <h1>Login</h1>
    <form action="/login/submit" method="POST" class="auth-form">
        <?php
            
        ?>
        <?php $errors = Session::get('errors') ?? [];
            if (!empty($errors)): ?>
            <div class="message error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php 
            Session::remove('errors'); 
            ?>
        <?php endif; ?>


        <label for="username">Username or Email:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username or email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit" class="auth-button">Login</button>
    </form>
    <p>Don't have an account? <a href="/signup">Sign up here</a></p>
</main>

<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
