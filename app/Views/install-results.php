<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Results</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <main>
        <h1>Installation Results</h1>
        
        <section>
            <h2>Tasks Completed</h2>
            <ul class="tasks-completed">
                <?php foreach ($tasksCompleted as $task): ?>
                    <li><span class="icon success">✔️</span> <?= htmlspecialchars($task); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>

        <?php if (!empty($errors)): ?>
            <section>
                <h2>Errors</h2>
                <ul class="errors">
                    <?php foreach ($errors as $error): ?>
                        <li><span class="icon error">❌</span> <?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
        
        <p><a href="/">Go to Home</a></p>
    </main>
</body>
</html>
