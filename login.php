<?php
session_start(); // Start the session
require 'db.php'; // Include the database connection

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the users table to find the user
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
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
                header("Location: complaints.php");
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
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
