<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $executive_name = $_POST['executive_name'];
    $report = $_POST['report'];

    $query = "INSERT INTO audit_reports (executive_name, report) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $executive_name, $report);
    $stmt->execute();

    echo "Audit report submitted successfully.";
}
?>
<form method="POST">
    <input type="text" name="executive_name" placeholder="Your Name" required>
    <textarea name="report" placeholder="Weekly Report" required></textarea>
    <button type="submit">Submit Report</button>
</form>
