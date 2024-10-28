<?php
require_once '/config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error = 'Login failed. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        .error { color: red; }
        .container { width: 400px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; }
        button { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo h($error); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
        </form>
        
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>