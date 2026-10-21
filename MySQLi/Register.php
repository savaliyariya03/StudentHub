<?php
require_once 'db_connect.php';
$message = "";
$messageType = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name       = trim($_POST['full_name'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $dob             = $_POST['dob'] ?? '';
    $gender          = $_POST['gender'] ?? '';
    $enrollment_no   = trim($_POST['enrollment_no'] ?? '');
    $department      = $_POST['department'] ?? '';
    $course          = trim($_POST['course'] ?? '');
    $semester        = (int)($_POST['semester'] ?? 0);
    $password        = $_POST['password'] ?? '';
    $confirm_password= $_POST['confirm_password'] ?? '';
    if (empty($full_name) || empty($email) || empty($enrollment_no) || empty($password)) {
        $message = "Please fill in all required fields.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "error";
    } else {
        $check_sql = "SELECT email, enrollment_no FROM students WHERE email = ? OR enrollment_no = ?";
        $stmt_check = $conn->prepare($check_sql);
        $stmt_check->bind_param("ss", $email, $enrollment_no);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        if ($result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            if ($existing['email'] === $email) {
                $message = "This Email is already registered. Please login or use another email.";
            } else {
                $message = "This Enrollment Number is already registered.";
            }
            $messageType = "error";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO students 
                (full_name, email, phone, dob, gender, enrollment_no, department, course, semester, password_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($insert_sql);
            $stmt_insert->bind_param(
                "ssssssssis", 
                $full_name, 
                $email, 
                $phone, 
                $dob, 
                $gender, 
                $enrollment_no, 
                $department, 
                $course, 
                $semester, 
                $password_hashed
            );
            if ($stmt_insert->execute()) {
                $message = "Registration successful! You can now log in.";
                $messageType = "success";
            } else {
                $message = "Registration failed due to a server error. Please try again.";
                $messageType = "error";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - StudentHub</title>
    <link rel="stylesheet" href="StudentHub.css">
    <style>
        .msg-box {
            max-width: 500px;
            margin: 15px auto;
            padding: 12px;
            border-radius: 4px;
            font-weight: bold;
        }
        .msg-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .msg-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <h1>Student Registration</h1>
    <p>Create your StudentHub account to stay connected with campus activities.</p>
    <?php if (!empty($message)): ?>
        <div class="msg-box <?= $messageType === 'success' ? 'msg-success' : 'msg-error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="Register.php" onsubmit="return validateFrontend();">
        <label>Full Name:</label>
        <input type="text" name="full_name" id="full_name" placeholder="Enter full name" required>
        <br><br>
        <label>Email Address:</label>
        <input type="email" name="email" id="email" placeholder="Enter email" required>
        <br><br>
        <label>Phone Number:</label>
        <input type="text" name="phone" id="phone" placeholder="Enter phone number" required>
        <br><br>
        <label>Date of Birth:</label>
        <input type="date" name="dob" id="dob" required>
        <br><br>
        <label>Gender:</label>
        <input type="radio" name="gender" value="male" required> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="other"> Other
        <br><br>
        <label>Enrollment Number:</label>
        <input type="text" name="enrollment_no" id="enrollment_no" placeholder="Enter enrollment number" required>
        <br><br>
        <label>Department:</label>
        <select name="department" id="department" required>
            <option value="Computer Science">Computer Science</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Commerce">Commerce</option>
            <option value="Management">Management</option>
        </select>
        <br><br>
        <label>Course:</label>
        <input type="text" name="course" id="course" placeholder="e.g., B.Tech, BCA" required>
        <br><br>
        <label>Semester:</label>
        <input type="number" name="semester" id="semester" min="1" max="8" placeholder="Enter semester" required>
        <br><br>
        <label>Password:</label>
        <input type="password" name="password" id="password" placeholder="Create password" required minlength="6">
        <br><br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
        <br><br>
        <button type="submit">Register</button>
        <button type="reset">Clear</button>
        <br><br>
    </form>
    <br><br>
    <footer>StudentHub © 2026</footer>
    <script>
    function validateFrontend() {
        const pass = document.getElementById("password").value;
        const confirmPass = document.getElementById("confirm_password").value;
        if (pass.length < 6) {
            alert("Password must be at least 6 characters long.");
            return false;
        }
        if (pass !== confirmPass) {
            alert("Passwords do not match!");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>