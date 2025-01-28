<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_name = $_POST['student_name'];
    $student_id = $_POST['student_id'];
    $issue = $_POST['issue'];

    $query = "INSERT INTO complaints (student_name, student_id, issue) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $student_name, $student_id, $issue);
    $stmt->execute();

    echo "Complaint lodged successfully.";
}
?>
<form method="POST">
    <input type="text" name="student_name" placeholder="Your Name" required>
    <input type="text" name="student_id" placeholder="Your Student ID" required>
    <textarea name="issue" placeholder="Describe your issue" required></textarea>
    <button type="submit">Submit Complaint</button>
</form>
