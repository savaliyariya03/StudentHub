<?php
require_once 'db_connect.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("UPDATE students SET remember_token = NULL WHERE student_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    addAuditLog($conn, $_SESSION['user_id'], $_SESSION['role'], "User Logged Out");
}
session_unset();
session_destroy();
setcookie("remember_token", "", time() - 3600, "/");
header("Location: Login.php?msg=Logged+out+successfully");
exit();
?>