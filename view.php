<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        .no-data {
            text-align: center;
            color: #666;
            padding: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registered Students</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile No.</th>
                    <th>Gender</th>
                    <th>Department(s)</th>
                    <th>Course</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);

                $servername = "localhost";
                $username = "root"; 
                $password = ""; 
                $dbname = "student_registration_db";

                try {
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        throw new Exception("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, first_name, last_name, mobile_no, email, gender, department, course, city FROM students";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["first_name"]) . " " . htmlspecialchars($row["last_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["mobile_no"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["department"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["course"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["city"]) . "</td>";
                            echo "<td class='action-links'><a href='edit.php?id=" . htmlspecialchars($row["id"]) . "'>Edit</a> | <a href='delete.php?id=" . htmlspecialchars($row["id"]) . "' onclick=\"return confirm('Are you sure you want to delete this student?');\">Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='no-data'>No registered students found.</td></tr>";
                    }

                    $conn->close();

                } catch (Exception $e) {
                    echo "<tr><td colspan='9' class='no-data'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="register_form.php" class="back-link">Register New Student</a>
    </div>
</body>
</html>