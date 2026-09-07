<?php
// Session start (Header redirect)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/db.php';
require_once '../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = clean($_POST['full-name'] ?? '');
    $email    = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm-password'] ?? '';

    // Server-side validation
    if ($fullName === '' || $email === '' || $password === '' || $confirm === '') {
        $errors[] = "All fields are required.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check email isn't already registered
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "An account with this email already exists.";
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        
        // Database එකේ ඇති full_name Column එකට $fullName අගය Insert කිරීම
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$fullName, $email, $hashed]);

        // Session Variables සකස් කිරීම
        $_SESSION['user_id']   = $pdo->lastInsertId();
        $_SESSION['user_name'] = $fullName;
        
        // Redirect to Dashboard
        redirect('../dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Register | UniShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:520px;">
    <h1 class="h3 fw-bold mb-4 text-center">Create your UniShare account</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="full-name" class="form-control" value="<?= htmlspecialchars($_POST['full-name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Student email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input type="password" name="confirm-password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Register</button>
        <p class="text-center small mt-3 mb-0">Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
</body>
</html>