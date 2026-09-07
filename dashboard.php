<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin();

$stmt = $pdo->prepare("SELECT * FROM resources WHERE uploaded_by = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$myUploads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Dashboard | UniShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Welcome, <?= clean($_SESSION['user_name'] ?? 'User') ?></h1>
        <a href="auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold mb-0">Your Uploads</h2>
        <a href="upload.php" class="btn btn-success btn-sm">+ Upload Resource</a>
    </div>

    <div class="card shadow-sm">
        <?php if (empty($myUploads)): ?>
            <div class="p-4 text-muted text-center">You haven't uploaded anything yet.</div>
        <?php else: ?>
            <?php foreach ($myUploads as $r): ?>
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h6 class="mb-1 fw-bold"><?= clean($r['title']) ?></h6>
                        <small class="text-muted"><?= clean($r['category']) ?> • <?= date('d M Y', strtotime($r['created_at'])) ?></small>
                    </div>
                    <a href="<?= clean($r['file_path']) ?>" class="btn btn-sm btn-outline-success" download>Download</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>