<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. You need to be logged in to submit a report.");
}

require 'db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $report_title = $_POST['report_title'];
    $report_description = $_POST['report_description'];

    // Insert the report into the database
    $query = "INSERT INTO submitted_reports (user_id, report_title, report_description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $report_title, $report_description);
    $stmt->execute();

    echo "Report submitted successfully.";
}
?>

<!-- Report submission form -->
<form method="POST">
    <input type="text" name="report_title" placeholder="Report Title" required>
    <textarea name="report_description" placeholder="Describe your report" required></textarea>
    <button type="submit">Submit Report</button>
</form>
