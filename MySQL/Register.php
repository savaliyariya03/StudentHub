<?php
require_once 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName     = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $dob          = $_POST['dob'] ?? '';
    $gender       = $_POST['gender'] ?? '';
    $enrollmentNo = trim($_POST['enrollment_no'] ?? '');
    $department   = $_POST['department'] ?? '';
    $course       = trim($_POST['course'] ?? '');
    $semester     = (int)($_POST['semester'] ?? 0);
    $password     = $_POST['password'] ?? '';
    $confirmPass  = $_POST['confirm_password'] ?? '';
    if ($password !== $confirmPass) {
        $message = "Passwords do not match.";
    } elseif (empty($fullName) || empty($email) || empty($enrollmentNo) || empty($password)) {
        $message = "Please fill in all required fields.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO students (full_name, email, phone, dob, gender, enrollment_no, department, course, semester, password_hash)
                VALUES (:full_name, :email, :phone, :dob, :gender, :enrollment_no, :department, :course, :semester, :password_hash)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':full_name'     => $fullName,
                ':email'         => $email,
                ':phone'         => $phone,
                ':dob'           => $dob,
                ':gender'        => $gender,
                ':enrollment_no' => $enrollmentNo,
                ':department'    => $department,
                ':course'        => $course,
                ':semester'      => $semester,
                ':password_hash' => $hashedPassword
            ]);
            $message = "Registration successful! You can now log in.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Error: Email or Enrollment Number already registered.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - StudentHub</title>
    <link rel="stylesheet" href="StudentHub.css">
</head>
<body>
    <h1>Student Registration</h1>
    <p>Create your StudentHub account to stay connected with campus activities.</p>
    <?php if (!empty($message)): ?>
        <p><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>
    <form method="POST" action="Register.php">
        <label>Full Name:</label>
        <input type="text" name="full_name" placeholder="Enter full name" required>
        <br><br>
        <label>Email Address:</label>
        <input type="email" name="email" placeholder="Enter email" required>
        <br><br>
        <label>Phone Number:</label>
        <input type="text" name="phone" placeholder="Enter phone number" required>
        <br><br>
        <label>Date of Birth:</label>
        <input type="date" name="dob" required>
        <br><br>
        <label>Gender:</label>
        <input type="radio" name="gender" value="male" required> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="other"> Other
        <br><br>
        <label>Enrollment Number:</label>
        <input type="text" name="enrollment_no" placeholder="Enter enrollment number" required>
        <br><br>
        <label>Department:</label>
        <select name="department" required>
            <option value="Computer Science">Computer Science</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Commerce">Commerce</option>
            <option value="Management">Management</option>
        </select>
        <br><br>
        <label>Course:</label>
        <input type="text" name="course" placeholder="e.g., B.Tech, BCA" required>
        <br><br>
        <label>Semester:</label>
        <input type="number" name="semester" min="1" max="8" placeholder="Enter semester" required>
        <br><br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Create password" required>
        <br><br>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Re-enter password" required>
        <br><br>
        <button type="submit">Register</button>
        <button type="reset">Clear</button>
        <br><br>
    </form>
    <br><br>
    <footer>StudentHub © 2026</footer>
</body>
</html>