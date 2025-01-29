<?php
session_start();
require 'db.php'; // Include the database connection

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Fetch all users and their roles from the database
$query = "SELECT id, username, role FROM users";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Handle the form submission to update the role
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $new_role = $_POST['role'];
    $user_id = $_POST['user_id'];

    // Update the user's role in the database
    $update_query = "UPDATE users SET role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $new_role, $user_id);
    
    if ($update_stmt->execute()) {
        // Success: Refresh the page to see updated roles
        header("Location: " . $_SERVER['PHP_SELF']); 
        exit;
    } else {
        // If the update query fails
        echo "Error updating role: " . $update_stmt->error;
    }
}

// Fetch users by role
$roles = ['student', 'executive', 'management', 'admin']; // List of available roles
$users_by_role = [];

foreach ($roles as $role) {
    $role_query = "SELECT id, username FROM users WHERE role = ?";
    $role_stmt = $conn->prepare($role_query);
    $role_stmt->bind_param("s", $role);
    $role_stmt->execute();
    $role_result = $role_stmt->get_result();
    $users_by_role[$role] = $role_result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>

<h2>Manage Users</h2>

<?php foreach ($users_by_role as $role => $users): ?>
    <h3><?php echo ucfirst($role); ?>s</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Current Role</th>
                <th>Update Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <form method="POST">
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo ucfirst($role); ?></td>
                        <td>
                            <select name="role" required>
                                <option value="student" <?php echo ($role == 'student') ? 'selected' : ''; ?>>Student</option>
                                <option value="executive" <?php echo ($role == 'executive') ? 'selected' : ''; ?>>Executive</option>
                                <option value="management" <?php echo ($role == 'management') ? 'selected' : ''; ?>>Management</option>
                                <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit">Update Role</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

</body>
</html>
