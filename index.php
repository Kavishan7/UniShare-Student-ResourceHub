<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM resources ORDER BY created_at DESC LIMIT 3");
$recent = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>UniShare | Centralized ICT Resource Platform</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-md bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="index.php">UniShare</a>
        <div class="ms-auto d-flex gap-3">
            <a href="resources.php" class="nav-link">Catalog</a>
            <a href="contact.php" class="nav-link">Contact</a>
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php" class="nav-link">Hi, <?= clean($_SESSION['username']) ?></a>
                <a href="auth/logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="auth/login.php" class="nav-link">Login / Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="p-5 mb-5 bg-white rounded-3 shadow-sm text-center">
        <h1 class="display-5 fw-bold">Find your notes in seconds</h1>
        <p class="lead text-muted">Centralized ICT resources for university students.</p>
        <a href="resources.php" class="btn btn-success btn-lg">Get started</a>
    </div>

    <h2 class="h5 fw-bold mb-3">Recently Added Resources</h2>
    <div class="card shadow-sm mb-5">
        <?php if (empty($recent)): ?>
            <div class="p-4 text-muted text-center">No resources yet.</div>
        <?php else: ?>
            <?php foreach ($recent as $r): ?>
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div>
                        <h6 class="mb-1 fw-bold"><?= clean($r['title']) ?></h6>
                        <small class="text-muted">
                            <span class="badge bg-secondary"><?= clean($r['subject_category']) ?></span>
                            • <?= date('d M Y', strtotime($r['created_at'])) ?>
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
