<?php
session_start();
require '../../config/database.php';

$found = false;
$message = "";
$messageClass = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT user_id, password, is_active FROM tb_users WHERE email = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password']) && $user['is_active']) {
        $found = true;
        $user_id = $user['user_id'];
    }

    if ($found) {
        $_SESSION['user_id'] = $user_id;
        header("Location: ../../index.php");
    } else {
        $messageClass = "error";
        $message = "Username and password does not match";
        header("Location: ../../module/login/login.php?message=" . urlencode($message) . "&code=" . $messageClass);
    }
    exit();
}
?>
