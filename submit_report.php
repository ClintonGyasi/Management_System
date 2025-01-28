session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['report_title'];
    $description = $_POST['report_description'];
    $executive_id = $_SESSION['user_id']; // Assuming executive is logged in

    // Insert the report into the database
    $query = "INSERT INTO reports (title, description, executive_id, submitted_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $title, $description, $executive_id);
    $stmt->execute();

    echo "Report submitted successfully!";
}
