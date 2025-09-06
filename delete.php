<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Student ID is missing.";
    exit;
}

$student_id = intval($_GET['id']);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_registration_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $student_id);
    
    if ($stmt->execute()) {
        echo "Student with ID " . htmlspecialchars($student_id) . " deleted successfully!";
        header("refresh:2;url=view.php");
    } else {
        echo "Error deleting student: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}

?>
