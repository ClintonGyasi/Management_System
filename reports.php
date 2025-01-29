<?php
session_start();
require 'db.php';  // Ensure this file contains valid database connection settings

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Fetch all reports from the database
$query = "SELECT sr.id, sr.report_title, sr.report_description, sr.status, sr.submitted_at, u.username 
          FROM submitted_reports sr 
          JOIN users u ON sr.user_id = u.id 
          ORDER BY sr.submitted_at DESC"; // Adjust as necessary for your table structure
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);  // Display any query errors
}

// Handle report status update (e.g., mark as reviewed)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_reviewed'])) {
    $report_id = $_POST['report_id'];
    $query_update = "UPDATE submitted_reports SET status = 'Reviewed' WHERE id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param("i", $report_id);
    $stmt->execute();
    echo "Report marked as reviewed.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports Management</title>
</head>
<body>
    <h1>Submitted Reports</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Report Title</th>
                <th>Description</th>
                <th>Submitted By</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Fetch and display all reports
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['report_title']); ?></td>
                    <td><?php echo htmlspecialchars($row['report_description']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['submitted_at']; ?></td>
                    <td>
                        <!-- Mark as reviewed if the report is pending -->
                        <?php if ($row['status'] == 'Pending'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="mark_reviewed">Mark as Reviewed</button>
                            </form>
                        <?php else: ?>
                            Reviewed
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
