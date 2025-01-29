<?php
session_start();
require 'db.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Fetch all users for management (admins only)
$query = "SELECT id, username, role FROM users";  // Removed email from the SELECT query
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);  // If there's a query execution error
}
?>

<!-- Display users management table -->
<table border="1">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Role</th> <!-- Removed the email column -->
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Fetch and display users
        while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td> <!-- Removed the email column -->
            <td>
                <!-- You can add actions like edit, delete, or change role here -->
                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
