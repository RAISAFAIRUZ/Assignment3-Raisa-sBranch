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
            <?php
            require_once 'config.php'; // Updated to use the relative path
            redirectIfNotLoggedIn();
            
            $user = getCurrentUser();
            ?>
            
            <p>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>
            <div class="nav-links">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
