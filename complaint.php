<?php
session_start(); // Start the session

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['student', 'admin'])) {
    die("Access denied. You need to be logged in as a student or an admin.");
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

    // Check for similar complaints
    $query_check = "SELECT COUNT(*) AS similar_count FROM complaints WHERE issue LIKE ?";
    $stmt_check = $conn->prepare($query_check);
    $issue_search = "%" . $issue . "%"; // Partial match for similar issues
    $stmt_check->bind_param("s", $issue_search);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row = $result_check->fetch_assoc();
    
    // If there are more than 5 similar complaints, trigger an alert
    if ($row['similar_count'] > 5) {
        // Trigger an alert (insert into the database)
        $alert_message = "There are more than 5 complaints with a similar issue.";
        
        // Insert the alert into the database
        $query_insert_alert = "INSERT INTO alerts (message) VALUES (?)";
        $stmt_insert_alert = $conn->prepare($query_insert_alert);
        $stmt_insert_alert->bind_param("s", $alert_message);
        $stmt_insert_alert->execute();

        // Send email notification to management
        mail("gyasibannorclinton@gmail.com", "Complaint Alert", $alert_message);
    }

    echo "Complaint lodged successfully. Thank you for your submission!";
}
?>

<!-- Complaint form -->
<form method="POST">
    <input type="text" name="student_name" placeholder="Your Name" required>
    <input type="text" name="student_id" placeholder="Your Student ID" required>
    <textarea name="issue" placeholder="Describe your issue" required></textarea>
    <button type="submit">Submit Complaint</button>
</form>
