<?php
session_start(); // Start the session

// Check if the user is logged in and is an executive
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'executive') {
    die("Access denied. You need to be logged in as an executive.");
}

require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get audit report data from the form
    $executive_name = $_POST['executive_name'];
    $report = $_POST['report'];

    // Insert audit report into database
    $query = "INSERT INTO audit_reports (executive_name, report) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $executive_name, $report);
    $stmt->execute();

    echo "Audit report submitted successfully.";
}
?>
<!-- Audit report form -->
<form method="POST">
    <input type="text" name="executive_name" placeholder="Your Name" required>
    <textarea name="report" placeholder="Weekly Report" required></textarea>
    <button type="submit">Submit Report</button>
</form>
