<?php
session_start(); // Start the session

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    die("Access denied. You need to be logged in as a student.");
}

require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get complaint data from the form
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $issue = $_POST['issue'];

    // Insert complaint into database
    $query = "INSERT INTO complaints (student_name, student_id, issue) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $student_name, $student_id, $issue);
    $stmt->execute();

    echo "Complaint lodged successfully.";
}
?>
<!-- Complaint form -->
<form method="POST">
    <input type="text" name="student_name" placeholder="Your Name" required>
    <input type="text" name="student_id" placeholder="Your Student ID" required>
    <textarea name="issue" placeholder="Describe your issue" required></textarea>
    <button type="submit">Submit Complaint</button>
</form>
