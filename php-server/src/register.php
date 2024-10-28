<?php
require_once '/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($password)) {
        $error = 'All fields are required';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if username exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'Username already exists';
            } else {
                // Create new user
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt->execute([$username, $hashed_password]);
                
                $success = 'Registration successful! You can now login.';
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        .error { color: red; }
        .success { color: green; }
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
        <h2>Register</h2>
        
        <?php if ($error): ?>
            <p class="error"><?php echo h($error); ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="success"><?php echo h($success); ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required value="<?php echo h($username ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>