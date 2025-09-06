<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if a student ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Student ID is missing.";
    exit;
}

$student_id = intval($_GET['id']);

$servername = "localhost";
$username = "root";
$password = ""; // Your database password is empty
$dbname = "student_registration_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check database connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare a DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    
    // Check if the prepare statement was successful
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Bind the student ID parameter
    $stmt->bind_param("i", $student_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "Student with ID " . htmlspecialchars($student_id) . " deleted successfully!";
        // Redirect back to the view page after a short delay
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