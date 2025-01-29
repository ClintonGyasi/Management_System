<?php
session_start(); // Start the session
require 'db.php'; // Include the database connection

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_custom_id = $_POST['username_or_custom_id'];  // Get the entered username or custom ID
    $password = $_POST['password'];

    // Query the users table to find the user by either username or custom_id
    $query = "SELECT * FROM users WHERE username = ? OR custom_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username_or_custom_id, $username_or_custom_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user exists and password matches
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Save user details in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];  // Store the role

            // Redirect to appropriate page based on role
            if ($_SESSION['role'] == 'student') {
                header("Location: complaint.php");
            } elseif ($_SESSION['role'] == 'executive') {
                header("Location: audit.php");
            } elseif ($_SESSION['role'] == 'management') {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!-- Login form -->
<form method="POST">
    <label for="username_or_custom_id">Username or Custom ID:</label>
    <input type="text" name="username_or_custom_id" placeholder="Enter your username or custom ID" required><br><br>
    
    <label for="password">Password:</label>
    <input type="password" name="password" placeholder="Password" required><br><br>
    
    <button type="submit">Login</button>
</form>
