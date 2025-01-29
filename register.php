<?php
require 'db.php';  // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $custom_id = $_POST['custom_id'];  // Get the user-provided custom ID
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before saving it to the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user with the provided custom_id and hashed password into the database
    $query = "INSERT INTO users (custom_id, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $custom_id, $username, $hashed_password);  // 'sss' means string type for all params

    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!-- Registration Form -->
<form method="POST">
    <label for="custom_id">Custom User ID:</label>
    <input type="text" name="custom_id" placeholder="Enter your custom ID (letters and numbers)" required><br><br>

    <label for="username">Username:</label>
    <input type="text" name="username" placeholder="Username" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit">Register</button>
</form>
