<?php
require_once 'config.php'; // Start PHP code before any HTML output

// Ensure the user is logged in
redirectIfNotLoggedIn();

// Fetch the current user information
$user = getCurrentUser();

// Redirect to admin panel if the user is an admin
if (isAdmin()) {
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My PHP Web Page</title>
    <style>
        .container { width: 800px; margin: 50px auto; }
        .welcome-box { background-color: #f4f4f4; padding: 20px; border-radius: 5px; }
        .nav-links { margin-top: 20px; }
        .nav-links a { margin-right: 15px; }
    </style>
</head>
<body>
    <h1>Welcome to my PHP Web Page!</h1>
    <div class="container">
        <div class="welcome-box">
            <p>Welcome, <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>!</p>
            <div class="nav-links">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
