<?php
// db.php — Database Connection File

$host = "localhost";        // Your database host
$user = "root";             // Your MySQL username
$pass = "";                 // Your MySQL password
$dbname = "habit_tracker_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Database Connection Failed: " . $conn->connect_error);
}

// Optional: set charset to utf8
$conn->set_charset("utf8mb4");

// Debug message (optional, comment out later)
// echo "✅ Database Connected Successfully!";
?>
