<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .date-inputs {
            display: flex;
            gap: 10px;
        }
        .date-inputs input {
            flex: 1;
        }
        .radio-group, .checkbox-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }
        .radio-group label, .checkbox-group label {
            font-weight: normal;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button.cancel {
            background-color: #dc3545;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "student_registration_db";
    
    $student = null;
    $message = "";
    $message_type = "";
    
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $message = "Student ID is required.";
        $message_type = "error";
        echo "<div class='container'><div class='message error'>{$message}</div>";
        echo "<div class='actions'><a href='view.php'><button>Back to Students List</button></a></div></div>";
        exit;
    }
    
    $student_id = intval($_GET['id']);
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("<div class='container'><div class='message error'>Connection failed: " . $conn->connect_error . "</div></div>");
    }
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $first_name = trim($_POST['fname'] ?? '');
        $last_name = trim($_POST['lname'] ?? '');
        $father_name = trim($_POST['father'] ?? '');
        $dob_day = intval($_POST['day'] ?? 0);
        $dob_month = intval($_POST['month'] ?? 0);
        $dob_year = intval($_POST['year'] ?? 0);
        $mobile_no = trim($_POST['mobile'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $gender = $_POST['gender'] ?? '';
        $departments = $_POST['department'] ?? []; 
        $course = $_POST['course'] ?? '';
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        $errors = [];
        if (empty($first_name)) $errors[] = "First name is required.";
        if (empty($last_name)) $errors[] = "Last name is required.";
        if (empty($father_name)) $errors[] = "Father's name is required.";
        if (!checkdate($dob_month, $dob_day, $dob_year)) $errors[] = "Invalid date of birth.";
        if (empty($mobile_no)) $errors[] = "Mobile number is required.";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (empty($gender)) $errors[] = "Gender is required.";
        if (empty($departments)) $errors[] = "At least one Department must be selected.";
        if (empty($course)) $errors[] = "Course is required.";
        if (empty($city)) $errors[] = "City is required.";
        if (empty($address)) $errors[] = "Address is required.";
        
        if (empty($errors)) {
            $check_email = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
            $check_email->bind_param("si", $email, $student_id);
            $check_email->execute();
            $check_email->store_result();
            
            if ($check_email->num_rows > 0) {
                $errors[] = "Email address already exists for another student.";
            } else {
                $departments_str = implode(", ", $departments);
    
                $stmt = $conn->prepare("UPDATE students SET first_name=?, last_name=?, father_name=?, dob_day=?, dob_month=?, dob_year=?, mobile_no=?, email=?, gender=?, department=?, course=?, city=?, address=? WHERE id=?");
                
                if (!$stmt) {
                    $message = "Prepare failed: " . $conn->error;
                    $message_type = "error";
                } else {
                    $stmt->bind_param("sssiissssssssi", $first_name, $last_name, $father_name, $dob_day, $dob_month, $dob_year, $mobile_no, $email, $gender, $departments_str, $course, $city, $address, $student_id);
                    
                    if ($stmt->execute()) {
                        $message = "Student information updated successfully! Redirecting...";
                        $message_type = "success";
                        header("refresh:3;url=view.php");
                    } else {
                        $message = "Error updating student: " . $stmt->error;
                        $message_type = "error";
                    }
                    $stmt->close();
                }
            }
            $check_email->close();
        } else {
            $message = implode("<br>", $errors);
            $message_type = "error";
        }
    }
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        $student_departments = explode(", ", $student['department']);
    } else {
        $message = "Student not found.";
        $message_type = "error";
        echo "<div class='container'><div class='message error'>{$message}</div>";
        echo "<div class='actions'><a href='view.php'><button>Back to Students List</button></a></div></div>";
        exit;
    }
    $stmt->close();
    $conn->close();
    ?>

    <div class="container">
        <h1>Edit Student Information</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="father">Father's Name:</label>
                <input type="text" id="father" name="father" value="<?php echo htmlspecialchars($student['father_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Date of Birth:</label>
                <div class="date-inputs">
                    <input type="number" name="day" placeholder="Day" min="1" max="31" value="<?php echo htmlspecialchars($student['dob_day']); ?>" required>
                    <input type="number" name="month" placeholder="Month" min="1" max="12" value="<?php echo htmlspecialchars($student['dob_month']); ?>" required>
                    <input type="number" name="year" placeholder="Year" min="1900" max="2100" value="<?php echo htmlspecialchars($student['dob_year']); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="mobile">Mobile No.:</label>
                <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student['mobile_no']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Male" <?php echo ($student['gender'] == 'Male') ? 'checked' : ''; ?> required> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?php echo ($student['gender'] == 'Female') ? 'checked' : ''; ?>> Female</label>
                </div>
            </div>
    
            <div class="form-group">
                <label>Department:</label>
                <div class="checkbox-group">
                    <label><input type="checkbox" name="department[]" value="English" <?php echo (in_array('English', $student_departments)) ? 'checked' : ''; ?>> English</label>
                    <label><input type="checkbox" name="department[]" value="Computer" <?php echo (in_array('Computer', $student_departments)) ? 'checked' : ''; ?>> Computer</label>
                    <label><input type="checkbox" name="department[]" value="Business" <?php echo (in_array('Business', $student_departments)) ? 'checked' : ''; ?>> Business</label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="">Select Course</option>
                    <option value="B.A." <?php echo ($student['course'] == 'B.A.') ? 'selected' : ''; ?>>B.A.</option>
                    <option value="B.Sc." <?php echo ($student['course'] == 'B.Sc.') ? 'selected' : ''; ?>>B.Sc.</option>
                    <option value="B.Com." <?php echo ($student['course'] == 'B.Com.') ? 'selected' : ''; ?>>B.Com.</option>
                    <option value="B.E." <?php echo ($student['course'] == 'B.E.') ? 'selected' : ''; ?>>B.E.</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($student['city']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required><?php echo htmlspecialchars($student['address']); ?></textarea>
            </div>
            <div class="actions">
                <button type="submit" name="update">Update</button>
                <a href="view.php">
                    <button type="button" class="cancel">Cancel</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>