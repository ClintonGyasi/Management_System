<?php
session_start();
require 'db.php';

// Check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Fetch all submitted reports
$query = "SELECT sr.id, sr.report_title, sr.report_description, sr.status, sr.submitted_at, u.username 
          FROM submitted_reports sr 
          JOIN users u ON sr.user_id = u.id 
          ORDER BY sr.submitted_at DESC";
$result = $conn->query($query);

// Handle report status updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_review'])) {
    // Toggle between Reviewed and Not Reviewed
    $report_id = $_POST['report_id'];
    $current_status = $_POST['current_status'];

    // Determine the new status
    $new_status = ($current_status === 'Reviewed') ? 'Pending' : 'Reviewed';

    // Update the report's status in the database
    $query_update = "UPDATE submitted_reports SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param("si", $new_status, $report_id);
    $stmt->execute();

    echo "Report status updated to $new_status.";
}
?>

<!-- Display submitted reports -->
<table border="1">
    <thead>
        <tr>
            <th>Report Title</th>
            <th>Report Description</th>
            <th>Submitted By</th>
            <th>Submitted At</th>
            <th>Review Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['report_title']); ?></td>
            <td><?php echo htmlspecialchars($row['report_description']); ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo $row['submitted_at']; ?></td>
            <td><?php echo ($row['status'] == 'Reviewed') ? 'Reviewed' : 'Not Reviewed'; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                    <button type="submit" name="toggle_review">
                        <?php echo ($row['status'] == 'Reviewed') ? 'Unmark as Reviewed' : 'Mark as Reviewed'; ?>
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
