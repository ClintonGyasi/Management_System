<?php
// db.php - Database connection

$servername = "localhost";  // Your database server (usually 'localhost')
$username = "root";         // Your MySQL username (default in XAMPP is 'root')
$password = "";             // Your MySQL password (default in XAMPP is an empty string '')
$dbname = "eleesa_db";      // The name of your database (replace with your actual DB name)

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
