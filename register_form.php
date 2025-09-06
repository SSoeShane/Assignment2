<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Registration Form</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #e9ecef;
            --card-background: #ffffff;
            --text-color: #343a40;
            --border-color: #ced4da;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 40px 20px;
            color: var(--text-color);
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: var(--card-background);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-top: 5px solid var(--primary-color);
        }
        h1 {
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-color);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: inherit;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .date-inputs {
            display: flex;
            gap: 15px;
        }
        .date-inputs input {
            flex: 1;
            text-align: center;
        }
        .date-hint {
            font-size: 14px;
            color: var(--secondary-color);
            margin-top: 5px;
            text-align: right;
        }
        
        .radio-group, .checkbox-group {
            display: flex;
            gap: 25px;
            margin-top: 10px;
            flex-wrap: wrap;
        }
        .radio-group label, .checkbox-group label {
            font-weight: normal;
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 8px;
        }
        input[type="radio"], input[type="checkbox"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            transition: all 0.2s ease-in-out;
            position: relative;
            cursor: pointer;
        }
        input[type="radio"]:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        input[type="radio"]:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        input[type="checkbox"] {
            border-radius: 4px;
        }
        input[type="checkbox"]:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        input[type="checkbox"]:checked::after {
            content: '✓';
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
        }
        
        .name-group {
            display: flex;
            gap: 20px;
        }
        .name-group .form-group {
            flex: 1;
        }

        .mobile-input {
            display: flex;
        }
        .mobile-input span {
            background-color: var(--border-color);
            color: var(--text-color);
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: 8px 0 0 8px;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        .mobile-input input {
            border-radius: 0 8px 8px 0;
        }

        button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button[type="submit"] {
            background-color: var(--success-color);
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        button.cancel {
            background-color: var(--danger-color);
            color: white;
        }
        button.cancel:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }
        
        .actions {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .name-group, .date-inputs {
                flex-direction: column;
                gap: 0;
            }
            .name-group .form-group {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Registration Form</h1>
        <form action="process.php" method="POST">

            <div class="name-group">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" placeholder="First Name" required />
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" placeholder="Last Name" required />
                </div>
            </div>

            <div class="form-group">
                <label for="father">Father's Name:</label>
                <input type="text" id="father" name="father" required />
            </div>

            <div class="form-group">
                <label>Date of Birth:</label>
                <div class="date-inputs">
                    <input type="number" name="day" placeholder="Day" min="1" max="31" required>
                    <input type="number" name="month" placeholder="Month" min="1" max="12" required>
                    <input type="number" name="year" placeholder="Year" min="1800" max="2100" required>
                </div>
                <div class="date-hint">(DD-MM-YYYY)</div>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile No.:</label>
                <div class="mobile-input">
                    <span>+95 -</span>
                    <input type="text" id="mobile" name="mobile" required />
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" id="male" name="gender" value="Male" required />
                        Male
                    </label>
                    <label>
                        <input type="radio" id="female" name="gender" value="Female" />
                        Female
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Department:</label>
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="english" name="department[]" value="English" />
                        English
                    </label>
                    <label>
                        <input type="checkbox" id="computer" name="department[]" value="Computer" />
                        Computer
                    </label>
                    <label>
                        <input type="checkbox" id="business" name="department[]" value="Business" />
                        Business
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="course">Course:</label>
                <select id="course" name="course" required>
                    <option value="">Select Course</option>
                    <option value="B.A.">B.A.</option>
                    <option value="B.Sc.">B.Sc.</option>
                    <option value="B.Com.">B.Com.</option>
                    <option value="B.E.">B.E.</option>
                </select>
            </div>

            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required />
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>

            <div class="actions">
                <button type="submit" name="register">Register</button>
                <a href="view.php">
                    <button type="button" class="cancel">View Students</button>
                </a>
            </div>
        </form>
    </div>
</body>
</html>