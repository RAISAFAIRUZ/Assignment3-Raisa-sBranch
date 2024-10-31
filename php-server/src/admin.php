<?php
require_once 'config.php';

// Ensure only admins can access this page
redirectIfNotAdmin();

// Initialize message
$message = '';

// Handle delete user action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['delete_user'];

    try {
        $pdo = getDBConnection();

        // Check if user exists and is not an admin
        $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && !$user['is_admin']) { // Only delete if the user is not an admin
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $message = "User has been deleted successfully.";
        } else {
            $message = "Cannot delete admin or non-existent user.";
        }
    } catch (PDOException $e) {
        error_log("Admin delete user error: " . $e->getMessage());
        $message = "Error deleting user.";
    }
}

// Fetch all users
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, username, is_admin, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Admin fetch users error: " . $e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        .container { width: 800px; margin: 50px auto; }
        .success { color: green; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .delete-btn { background-color: #ff4444; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .delete-btn:hover { background-color: #cc0000; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        
        <?php if ($message): ?>
            <p class="success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        
        <h3>Registered Users</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Admin Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (!$user['is_admin']): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="delete_user" value="<?php echo htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="delete-btn" 
                                        onclick="return confirm('Are you sure you want to delete this user?')">
                                    Delete
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <p><a href="index.php">Back to Home</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
