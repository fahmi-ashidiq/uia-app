<?php
session_start();
require '../../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../module/login/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php?menu=account/profile");
    exit();
}

$firstname = htmlspecialchars($_POST['firstname'] ?? '');
$lastname  = htmlspecialchars($_POST['lastname'] ?? '');
$birthday  = $_POST['birthday'] ?? '';
$gender    = $_POST['gender'] ?? '';
$email     = htmlspecialchars($_POST['email'] ?? '');
$phone     = htmlspecialchars($_POST['phone'] ?? '');
$address   = htmlspecialchars($_POST['address'] ?? '');
$is_active = ($_POST['status'] ?? 'true') === 'true' ? 1 : 0;

// Region fields (soal #3) - codes are the source of truth, names are cached for display
$province_code = htmlspecialchars($_POST['province_code'] ?? '');
$province_name = htmlspecialchars($_POST['province_name'] ?? '');
$city_code     = htmlspecialchars($_POST['city_code'] ?? '');
$city_name     = htmlspecialchars($_POST['city_name'] ?? '');
$district_code = htmlspecialchars($_POST['district_code'] ?? '');
$district_name = htmlspecialchars($_POST['district_name'] ?? '');
$village_code  = htmlspecialchars($_POST['village_code'] ?? '');
$village_name  = htmlspecialchars($_POST['village_name'] ?? '');

$updated_at = date('Y-m-d H:i:s');
$updated_by = $user_id;

// Handle optional photo upload
$imageColumnUpdate = '';
$imageParam = [];
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed, true)) {
        $destDir = __DIR__ . '/../../asset/profile/';
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $filename = $user_id . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destDir . $filename)) {
            $imageColumnUpdate = ', image = ?';
            $imageParam = [$filename];
        }
    }
}

$sql = "UPDATE tb_users SET
            firstname = ?, lastname = ?, birthday = ?, gender = ?, email = ?, phone = ?,
            address = ?, is_active = ?,
            province_code = ?, province_name = ?,
            city_code = ?, city_name = ?,
            district_code = ?, district_name = ?,
            village_code = ?, village_name = ?,
            updated_at = ?, updated_by = ?
            {$imageColumnUpdate}
        WHERE user_id = ?";

$params = array_merge(
    [
        $firstname, $lastname, $birthday, $gender, $email, $phone,
        $address, $is_active,
        $province_code, $province_name,
        $city_code, $city_name,
        $district_code, $district_name,
        $village_code, $village_name,
        $updated_at, $updated_by,
    ],
    $imageParam,
    [$user_id]
);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$messageClass = "success";
$message = "Data has been saved successfully!";
header("Location: ../../index.php?menu=account/profile&message=" . urlencode($message) . "&code=" . urlencode($messageClass));
exit();
