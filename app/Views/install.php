<!-- <?php require_once ROOT . 'app/Views/layouts/header.php'; ?> -->
<style>
    /* Import Ubuntu Fonts */
    @font-face {
    font-family: "Ubuntu";
    src: url("/public/fonts/Ubuntu/Ubuntu-Regular.ttf") format("truetype");
    font-weight: normal;
    font-style: normal;
    }

    @font-face {
    font-family: "Ubuntu";
    src: url("/public/fonts/Ubuntu/Ubuntu-Bold.ttf") format("truetype");
    font-weight: bold;
    font-style: normal;
    }

    @font-face {
    font-family: "Ubuntu";
    src: url("/public/fonts/Ubuntu/Ubuntu-Italic.ttf") format("truetype");
    font-weight: normal;
    font-style: italic;
    }

    @font-face {
    font-family: "Ubuntu";
    src: url("/public/fonts/Ubuntu/Ubuntu-BoldItalic.ttf") format("truetype");
    font-weight: bold;
    font-style: italic;
    }

    /* Import Roboto Condensed Fonts */
    @font-face {
    font-family: "Roboto Condensed";
    src: url("/public/fonts/Roboto_Condensed/static/RobotoCondensed-Regular.ttf")
        format("truetype");
    font-weight: normal;
    font-style: normal;
    }

    @font-face {
    font-family: "Roboto Condensed";
    src: url("/public/fonts/Roboto_Condensed/static/RobotoCondensed-Bold.ttf")
        format("truetype");
    font-weight: bold;
    font-style: normal;
    }

    @font-face {
    font-family: "Roboto Condensed";
    src: url("/public/fonts/Roboto_Condensed/static/RobotoCondensed-Italic.ttf")
        format("truetype");
    font-weight: normal;
    font-style: italic;
    }

    @font-face {
    font-family: "Roboto Condensed";
    src: url("/public/fonts/Roboto_Condensed/static/RobotoCondensed-BoldItalic.ttf")
        format("truetype");
    font-weight: bold;
    font-style: italic;
    }

    /* Universal styles */
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Ubuntu", "Roboto Condensed", sans-serif;
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
    }

    /* Body styles */
    body {
    line-height: 1.6;
    color: #333;
    background-color: #f4f4f9;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    }
    button {
        background-color: #0056a8;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1rem;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .install-page {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .install-page h1 {
        text-align: center;
        color: #0056a8;
        margin-bottom: 20px;
    }

    .install-page .form-group {
        margin-bottom: 15px;
    }

    .install-page .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .install-page .form-group input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .install-page .errors {
        margin-top: 20px;
        background: #f8d7da;
        color: #842029;
        padding: 15px;
        border-radius: 5px;
    }

    .install-page .errors ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }
</style>
<main class="install-page">
    <h1>Install CruciWeb</h1>
    <form action="/install/submit" method="POST">
        <div class="form-group">
            <label for="db_host">Database Host:</label>
            <input type="text" id="db_host" name="db_host" required>
        </div>

        <div class="form-group">
            <label for="db_name">Database Name:</label>
            <input type="text" id="db_name" name="db_name" required>
        </div>

        <div class="form-group">
            <label for="db_user">Database User:</label>
            <input type="text" id="db_user" name="db_user" required>
        </div>

        <div class="form-group">
            <label for="db_pass">Database Password:</label>
            <input type="password" id="db_pass" name="db_pass">
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <h2>Installation Errors:</h2>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <button type="submit" class="button">Install</button>
    </form>
</main>

<!-- <?php require_once ROOT . 'app/Views/layouts/footer.php'; ?> -->
