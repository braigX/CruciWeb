<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CruciWeb</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>CruciWeb</h1>
        <nav>
            <ul class="menu">
                <li><a href="/">Home</a></li>
                <li><a href="/grids">Browse Grids</a></li>

                <?php 
                $user = Session::get('user'); 
                $role = $user['role'] ?? 'anonymous';
                ?>

                <?php if ($role === 'registered'): ?>
                    <li><a href="/saved-grids">Saved Grids</a></li>
                    <li><a href="/grids/create">Add Grid</a></li>
                <?php endif; ?>

                <?php if ($role === 'admin'): ?>
                    <li><a href="/admin/users">Manage Users</a></li>
                    <li><a href="/admin/grids">Manage Grids</a></li>
                <?php endif; ?>

                <?php if (Session::has('user')): ?>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/signup">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
