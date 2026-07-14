<?php
// Included from index.php, which already started the session and loaded config/database.php
if (!isset($pdo)) {
    require __DIR__ . '/../../config/database.php';
}

$stmt = $pdo->query("SELECT nim, name, photo, contribution FROM tb_team ORDER BY sort_order ASC, id ASC LIMIT 2");
$team = $stmt->fetchAll();
?>

<div class="head-title">
    <div class="left">
        <h1>Team</h1>
        <ul class="breadcrumb">
            <li>
                <a href="#">Team</a>
            </li>
            <li><i class='bx bx-chevron-right'></i></li>
            <li>
                <a class="active" href="index.php">Home</a>
            </li>
        </ul>
    </div>
</div>

<div class="form-style">
    <div class="section">
        <span>1</span>&nbsp;&nbsp;Tim yang Mengerjakan UAS
    </div>
    <div class="inner-wrap">
        <div class="team-grid">
            <?php foreach ($team as $member): ?>
                <?php
                $photoSrc = (!empty($member['photo']) && file_exists(__DIR__ . '/../../asset/profile/' . $member['photo']))
                    ? 'asset/profile/' . $member['photo']
                    : 'https://placehold.co/200x200/png?text=' . urlencode(explode(' ', $member['name'])[0]);
                ?>
                <div class="team-card">
                    <img src="<?php echo $photoSrc; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                    <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                    <p class="nim">NIM: <?php echo htmlspecialchars($member['nim']); ?></p>
                    <p class="contribution"><?php echo htmlspecialchars($member['contribution']); ?></p>
                </div>
            <?php endforeach; ?>

            <?php if (empty($team)): ?>
                <p>Belum ada data tim. Tambahkan data pada tabel <code>tb_team</code>.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
