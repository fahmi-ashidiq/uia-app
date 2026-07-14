<?php
/**
 * AJAX endpoint used by profile.php to populate the cascading
 * Provinsi -> Kab/Kota -> Kecamatan -> Kelurahan/Desa dropdowns.
 *
 * Usage:
 *   get-region.php?level=1                 -> list all provinces
 *   get-region.php?parent_code=XX          -> list children of a region code
 */

header('Content-Type: application/json; charset=utf-8');
require '../../config/database.php';

$level = isset($_GET['level']) ? (int) $_GET['level'] : null;
$parentCode = isset($_GET['parent_code']) ? trim($_GET['parent_code']) : '';

if ($level === 1) {
    $stmt = $pdo->prepare(
        "SELECT code, name FROM tb_region WHERE level = 1 AND is_active = 1 ORDER BY name ASC"
    );
    $stmt->execute();
} elseif ($parentCode !== '') {
    $stmt = $pdo->prepare(
        "SELECT code, name FROM tb_region WHERE parent_code = ? AND is_active = 1 ORDER BY name ASC"
    );
    $stmt->execute([$parentCode]);
} else {
    echo json_encode([]);
    exit;
}

echo json_encode($stmt->fetchAll());
