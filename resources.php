<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';
require_once 'includes/functions.php';

$search   = clean($_GET['q'] ?? '');
$category = clean($_GET['category'] ?? '');

// u.full_name AS username සහ r.category ලෙස නිවැරදි කර ඇත
$sql = "SELECT r.*, u.full_name AS username 
        FROM resources r 
        LEFT JOIN users u ON r.uploaded_by = u.id 
        WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND r.title LIKE ?";
    $params[] = "%$search%";
}
if ($category !== '') {
    $sql .= " AND r.category = ?";
    $params[] = $category;
}
$sql .= " ORDER BY r.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resources = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Catalog | UniShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0">Resource Catalog</h1>
        <?php if (isLoggedIn()): ?>
            <a href="upload.php" class="btn btn-success">+ Upload Resource</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-outline-success">Login to upload</a>
        <?php endif; ?>
    </div>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-8">
            <input type="text" name="q" class="form-control" placeholder="Search by title..." value="<?= clean($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All categories</option>
                <?php foreach (['Programming','Maths','Web Tech'] as $cat): ?>
                    <option value="<?= $cat ?>" <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-success w-100">Go</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <?php if (empty($resources)): ?>
            <div class="p-4 text-center text-muted">No resources found.</div>
        <?php else: ?>
            <?php foreach ($resources as $r): ?>
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h6 class="mb-1 fw-bold"><?= clean($r['title']) ?></h6>
                        <small class="text-muted">
                            <span class="badge bg-secondary"><?= clean($r['category']) ?></span>
                            • <?= date('d M Y', strtotime($r['created_at'])) ?>
                            <?php if ($r['username']): ?> • uploaded by <?= clean($r['username']) ?><?php endif; ?>
                        </small>
                    </div>
                    <a href="<?= clean($r['file_path']) ?>" class="btn btn-sm btn-outline-success" download>Download</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>