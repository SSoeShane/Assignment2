<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    $first_name = trim($_POST['fname'] ?? '');
    $last_name = trim($_POST['lname'] ?? '');
    $father_name = trim($_POST['father'] ?? '');
    
    $dob_day = isset($_POST['day']) ? (int)$_POST['day'] : 0;
    $dob_month = isset($_POST['month']) ? (int)$_POST['month'] : 0;
    $dob_year = isset($_POST['year']) ? (int)$_POST['year'] : 0;
    
    $mobile_no = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $departments = $_POST['department'] ?? [];
    $course = $_POST['course'] ?? '';
    $city = trim($_POST['city'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($first_name === '') $errors[] = "First Name is required.";
    if ($last_name === '') $errors[] = "Last Name is required.";
    if ($father_name === '') $errors[] = "Father's Name is required.";
    
    if ($dob_day === 0 || $dob_month === 0 || $dob_year === 0) {
        $errors[] = "Complete Date of Birth is required.";
    } elseif (!checkdate($dob_month, $dob_day, $dob_year)) {
        $errors[] = "Invalid Date of Birth.";
    } else {
        $currentYear = date('Y');
        if ($dob_year < 1900 || $dob_year > $currentYear) {
            $errors[] = "Year must be between 1900 and $currentYear.";
        }
    }
    
    if ($mobile_no === '') {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match('/^[0-9]{7,15}$/', $mobile_no)) {
        $errors[] = "Mobile number should contain only numbers and be 7-15 digits long.";
    }
    
    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email format.";
    }
    
    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    
    if ($gender === '') $errors[] = "Gender is required.";
    if (empty($departments)) $errors[] = "At least one Department must be selected.";
    if ($course === '') $errors[] = "Course is required.";
    if ($city === '') $errors[] = "City is required.";
    if ($address === '') $errors[] = "Address is required.";

    echo '<!DOCTYPE html><html><head><title>Registration Result</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; }
        .success { background: #4CAF50; color: white; padding: 20px; border-radius: 8px; }
        .error { background: #f44336; color: white; padding: 20px; border-radius: 8px; }
        .info { background: #2196F3; color: white; padding: 20px; border-radius: 8px; }
        .error p, .success p, .info p { margin: 10px 0; }
        .back-btn { display: inline-block; margin-top: 15px; padding: 10px 15px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
        pre { background: #eee; padding: 10px; border-radius: 4px; overflow: auto; }
    </style>
    </head><body><div class="container">';

    if (!empty($errors)) {
        echo '<div class="error"><h2>Form Errors:</h2>';
        foreach ($errors as $error) {
            echo '<p>' . htmlspecialchars($error) . '</p>';
        }
        echo '<a href="javascript:history.back()" class="back-btn">Go Back</a>';
        echo '</div></div></body></html>';
        exit;
    }

    $servername = "localhost";
    $username = "root";
    $password_db = ""; 
    $dbname = "student_registration_db";

    try {
        $conn = new mysqli($servername, $username, $password_db, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error . 
                ". Please check your database credentials and make sure MySQL is running.");
        }
        
        $check_email = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        
        if ($check_email->num_rows > 0) {
            throw new Exception("Email address already registered.");
        }
        $check_email->close();

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $departments_str = implode(", ", $departments);

        $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, father_name, dob_day, dob_month, dob_year, mobile_no, email, password, gender, department, course, city, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssiisssssssss", $first_name, $last_name, $father_name, $dob_day, $dob_month, $dob_year, $mobile_no, $email, $hashed_password, $gender, $departments_str, $course, $city, $address);

        if ($stmt->execute()) {
            echo '<div class="success">
                <h1>Registration Successful!</h1>
                <p>Your details have been saved to the database.</p>
                <p><strong>Full Name:</strong> ' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . '</p>
                <p><strong>Father\'s Name:</strong> ' . htmlspecialchars($father_name) . '</p>
                <p><strong>Date of Birth:</strong> ' . sprintf('%02d-%02d-%04d', $dob_day, $dob_month, $dob_year) . '</p>
                <p><strong>Mobile:</strong> +95-' . htmlspecialchars($mobile_no) . '</p>
                <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                <p><strong>Gender:</strong> ' . htmlspecialchars($gender) . '</p>
                <p><strong>Departments:</strong> ' . htmlspecialchars($departments_str) . '</p>
                <p><strong>Course:</strong> ' . htmlspecialchars($course) . '</p>
                <p><strong>City:</strong> ' . htmlspecialchars($city) . '</p>
                <p><strong>Address:</strong> ' . nl2br(htmlspecialchars($address)) . '</p>
                <a href="javascript:history.back()" class="back-btn">Register Another Student</a>
                <a href="view.php" class="back-btn">View all student</a>
            </div>';
        } else {
            throw new Exception("Error: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo '<div class="error"><h2>Database Error:</h2><p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>Please check your database configuration.</p>';
        echo '<a href="javascript:history.back()" class="back-btn">Go Back</a></div>';
    }

    echo '</div></body></html>';

} else {
    header("Location: register_form.php");
    exit;
}
?>