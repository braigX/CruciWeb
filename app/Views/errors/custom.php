<?php require_once ROOT . 'app/Views/layouts/header.php'; ?>
<main class="error-page">
    <h1>Error <?= htmlspecialchars($errorCode); ?></h1>
    <p><?= htmlspecialchars($message); ?></p>
    <a href="/" class="button">Go to Home</a>
</main>
<?php require_once ROOT . 'app/Views/layouts/footer.php'; ?>
