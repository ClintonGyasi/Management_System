<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "<p>Welcome, " . $_SESSION['role'] . "!</p>";

if ($_SESSION['role'] == 'student') {
    echo "<a href='complaint.php'>Lodge Complaint</a>";
} elseif ($_SESSION['role'] == 'executive') {
    echo "<a href='audit.php'>Submit Audit Report</a>";
} elseif ($_SESSION['role'] == 'management') {
    echo "<a href='dashboard.php'>Dashboard</a>";
}
?>
