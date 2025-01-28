<?php
session_start(); // Start the session

// Check if the user is logged in and is a management role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'management') {
    die("Access denied. You need to be logged in as a management member.");
}

require 'db.php'; // Database connection

// Fetch all alerts from the database
$query = "SELECT * FROM alerts ORDER BY created_at DESC";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resolve_alert'])) {
    // Mark an alert as resolved
    $alert_id = $_POST['alert_id'];
    $query_update = "UPDATE alerts SET resolved = TRUE WHERE id = ?";
    $stmt = $conn->prepare($query_update);
    $stmt->bind_param("i", $alert_id);
    $stmt->execute();
    echo "Alert marked as resolved.";
}
?>

<!-- Alert table -->
<table border="1">
    <thead>
        <tr>
            <th>Alert Message</th>
            <th>Created At</th>
            <th>Resolved</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['message']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['resolved'] ? 'Yes' : 'No'; ?></td>
            <td>
                <?php if (!$row['resolved']): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="alert_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="resolve_alert">Resolve</button>
                    </form>
                <?php else: ?>
                    Resolved
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
