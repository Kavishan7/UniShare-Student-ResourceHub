<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = clean($_POST['full-name'] ?? '');
    $email   = clean($_POST['email'] ?? '');
    $msgType = clean($_POST['msg_type'] ?? 'feedback');
    $message = clean($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $errors[] = "Name, email, and message are required.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, msg_type, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $msgType, $message]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Contact Us | UniShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:700px;">
    <h1 class="h3 fw-bold mb-4 text-center">Get in touch / Report an issue</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Thanks — your message has been received. We'll get back to you soon.</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="full-name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Message type</label>
            <select name="msg_type" class="form-select">
                <option value="missing_link">Missing link</option>
                <option value="request_material">Request material</option>
                <option value="feedback">Feedback</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
</body>
</html>
