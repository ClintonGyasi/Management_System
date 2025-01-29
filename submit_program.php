session_start();
require 'db.php';

if ($_SESSION['role'] != 'management') {
    echo "You do not have access to submit programs.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['program_name'];
    $description = $_POST['program_description'];
    $date = $_POST['program_date'];

    // Insert the program into the database
    $query = "INSERT INTO programs (name, description, date, status) VALUES (?, ?, ?, 'upcoming')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $description, $date);
    $stmt->execute();

    echo "Program submitted successfully!";
}
