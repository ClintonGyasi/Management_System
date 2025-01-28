<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];

    $query = "INSERT INTO programs (title, description, event_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $title, $description, $event_date);
    $stmt->execute();

    echo "Program added successfully.";
}
?>
<form method="POST">
    <input type="text" name="title" placeholder="Program Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="date" name="event_date" required>
    <button type="submit">Add Program</button>
</form>
