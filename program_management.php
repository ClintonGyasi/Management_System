<?php
session_start();
require 'db.php';  // Ensure this connects to your database

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admin access required.");
}

// Fetch all programs
$query = "SELECT * FROM programs";
$result = $conn->query($query);

// Handle new program addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_program'])) {
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $scheduled_date = $_POST['scheduled_date'];

    $stmt = $conn->prepare("INSERT INTO programs (program_name, description, scheduled_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $program_name, $description, $scheduled_date);
    
    if ($stmt->execute()) {
        // Set a session variable to indicate success
        $_SESSION['program_added'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']); // Redirect to prevent re-submission
        exit;
    } else {
        echo "Error adding program: " . $conn->error;
    }
}

// Handle program deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_program'])) {
    $program_id = $_POST['program_id'];
    
    $stmt = $conn->prepare("DELETE FROM programs WHERE id = ?");
    $stmt->bind_param("i", $program_id);
    
    if ($stmt->execute()) {
        echo "Program deleted successfully!";
    } else {
        echo "Error deleting program: " . $conn->error;
    }
}

// Handle program update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_program'])) {
    $program_id = $_POST['program_id'];
    $program_name = $_POST['program_name'];
    $description = $_POST['description'];
    $scheduled_date = $_POST['scheduled_date'];

    $stmt = $conn->prepare("UPDATE programs SET program_name = ?, description = ?, scheduled_date = ? WHERE id = ?");
    $stmt->bind_param("sssi", $program_name, $description, $scheduled_date, $program_id);
    
    if ($stmt->execute()) {
        echo "Program updated successfully!";
    } else {
        echo "Error updating program: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Management</title>
</head>
<body>
    <h1>Program Management</h1>

    <h2>Add New Program</h2>
    <form method="POST">
        <label>Program Name:</label>
        <input type="text" name="program_name" required>
        <br>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <br>
        <label>Scheduled Date:</label>
        <input type="date" name="scheduled_date" required>
        <br>
        <button type="submit" name="add_program">Add Program</button>
    </form>

    <?php
    // Check if the program was added and show a success message
    if (isset($_SESSION['program_added']) && $_SESSION['program_added'] === true) {
        echo "<p>Program added successfully!</p>";
        unset($_SESSION['program_added']); // Clear the session variable after displaying the message
    }
    ?>

    <h2>Existing Programs</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Program Name</th>
                <th>Description</th>
                <th>Scheduled Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['program_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['scheduled_date']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="program_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_program">Delete</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="program_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="program_name" value="<?php echo htmlspecialchars($row['program_name']); ?>" required>
                            <input type="text" name="description" value="<?php echo htmlspecialchars($row['description']); ?>">
                            <input type="date" name="scheduled_date" value="<?php echo htmlspecialchars($row['scheduled_date']); ?>" required>
                            <button type="submit" name="edit_program">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
