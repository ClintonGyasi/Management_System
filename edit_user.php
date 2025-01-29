<?php
session_start();
require 'db.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Check if 'id' is passed via URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user details from the database
    $query = "SELECT id, username, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        die("User not found.");
    }
    $user = $result->fetch_assoc();

    // Handle the form submission to update the role
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_role = $_POST['role'];

        // Debugging - check if the role is correctly passed
        echo "<pre>";
        var_dump($new_role);  // Check the new role value
        echo "</pre>";

        // Update the user's role in the database
        $update_query = "UPDATE users SET role = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_role, $user_id);
        
        if ($update_stmt->execute()) {
            // Success: Redirect back to management page
            header("Location: management.php");
            exit;
        } else {
            // If the update query fails
            echo "Error updating role: " . $update_stmt->error;
        }
    }
} else {
    die("No user ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>

<h2>Edit User Role</h2>

<form method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled><br><br>

    <label for="role">Role:</label>
    <select name="role" id="role" required>
        <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
        <!-- Add more roles here if needed -->
    </select><br><br>

    <button type="submit">Update Role</button>
</form>

</body>
</html>
