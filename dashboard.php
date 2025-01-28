<?php
session_start(); // Start the session

// Check if the user is logged in and is management
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'management') {
    die("Access denied. You need to be logged in as management.");
}

require 'db.php'; // Database connection

// Fetch complaints, audit reports, and program details
$complaints_query = "SELECT * FROM complaints";
$complaints_result = $conn->query($complaints_query);

$audit_query = "SELECT * FROM audit_reports";
$audit_result = $conn->query($audit_query);

$program_query = "SELECT * FROM programs";
$program_result = $conn->query($program_query);

// Display complaints, audit reports, and programs for management
?>
<h2>Complaints</h2>
<?php while ($row = $complaints_result->fetch_assoc()) { ?>
    <p><?php echo $row['issue']; ?></p>
<?php } ?>

<h2>Audit Reports</h2>
<?php while ($row = $audit_result->fetch_assoc()) { ?>
    <p><?php echo $row['report']; ?></p>
<?php } ?>

<h2>Programs</h2>
<?php while ($row = $program_result->fetch_assoc()) { ?>
    <p><?php echo $row['title']; ?> - <?php echo $row['event_date']; ?></p>
<?php } ?>
