<?php

require '../../vendor/autoload.php';
require '../../config/database.php';
use Ramsey\Uuid\Uuid;

$message = "";
$messageClass = "";

$userid = Uuid::uuid4()->toString();

// Get POST data and sanitize
$firstname = htmlspecialchars($_POST['firstname'] ?? '');
$lastname  = htmlspecialchars($_POST['lastname'] ?? '');
$gender    = htmlspecialchars($_POST['gender'] ?? '');
$birthday  = htmlspecialchars($_POST['birthday'] ?? '');
$phone     = htmlspecialchars($_POST['phone'] ?? '');
$email     = htmlspecialchars($_POST['email'] ?? '');
$pswd      = $_POST['password'] ?? '';

// Check for duplicate (same first/last name + gender + birthday, same rule as before)
$stmt = $pdo->prepare("SELECT user_id FROM tb_users WHERE firstname = ? AND lastname = ? AND gender = ? AND birthday = ? LIMIT 1");
$stmt->execute([$firstname, $lastname, $gender, $birthday]);
$found = (bool) $stmt->fetch();

// Also guard against duplicate email, since it's used as the login username
if (!$found) {
    $stmt = $pdo->prepare("SELECT user_id FROM tb_users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $found = true;
    }
}

if ($found) {
    $messageClass = "error";
    $message = "Duplicate data found";
} else {
    $hashedPassword = password_hash($pswd, PASSWORD_BCRYPT);
    $now = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare(
        "INSERT INTO tb_users
            (user_id, firstname, lastname, gender, birthday, email, phone, password, is_active, created_at, created_by, updated_at, updated_by)
         VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, 'SYSTEM', ?, 'SYSTEM')"
    );
    $stmt->execute([$userid, $firstname, $lastname, $gender, $birthday, $email, $phone, $hashedPassword, $now, $now]);

    $messageClass = "success";
    $message = "Data submitted successfully!";
}

header("Location: ../../module/login/login.php?message=" . urlencode($message) . "&code=" . urlencode($messageClass));
exit();
