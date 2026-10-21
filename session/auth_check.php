<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connect.php';
$timeout_duration = 900;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
    header("Location: Login.php?msg=Session+timed+out");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT student_id, full_name, email, role, remember_token FROM students WHERE remember_token IS NOT NULL");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if (password_verify($token, $row['remember_token'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['student_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];
            break;
        }
    }
    $stmt->close();
}
function checkAccess($requiredRole = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: Login.php?msg=Please+login+first");
        exit();
    }
    if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
        header("Location: dashboard.php?msg=Unauthorized+access");
        exit();
    }
}
?>