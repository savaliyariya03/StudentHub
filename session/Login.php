<?php
require_once 'db_connect.php';
session_start();
$error = $_GET['msg'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT student_id, full_name, email, password_hash, role FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['student_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['LAST_ACTIVITY'] = time();
                if ($remember) {
                    $raw_token = bin2hex(random_bytes(32));
                    $hashed_token = password_hash($raw_token, PASSWORD_DEFAULT);
                    $upStmt = $conn->prepare("UPDATE students SET remember_token = ? WHERE student_id = ?");
                    $upStmt->bind_param("si", $hashed_token, $user['student_id']);
                    $upStmt->execute();
                    $upStmt->close();
                    setcookie("remember_token", $raw_token, time() + (86400 * 7), "/", "", false, true);
                }
                addAuditLog($conn, $user['student_id'], $user['role'], "User Logged In");
                if ($user['role'] === 'admin') {
                    header("Location: Admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that email.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - StudentHub</title>
    <link rel="stylesheet" href="StudentHub.css">
</head>
<body>
    <h1>🔑 Student Login</h1>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><strong><?= htmlspecialchars($error) ?></strong></p>
    <?php endif; ?>
    <form class="register-form" method="POST" action="Login.php">
        <label>Email Address:</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        <br><br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <br><br>
        <label><input type="checkbox" name="remember_me"> Remember Me (7 days)</label>
        <br><br>
        <button type="submit">Login</button>
        <br><br>
    </form>
    <footer>StudentHub © 2026</footer>
</body>
</html>