<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
requireLogin(); // must be logged in to upload

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = clean($_POST['title'] ?? '');
    $desc     = clean($_POST['description'] ?? '');
    $category = clean($_POST['subject_category'] ?? '');
    $semester = clean($_POST['semester'] ?? 'Semester 1'); // Form default as 'Semester 1'

    if ($title === '' || $category === '') {
        $errors[] = "Title and category are required.";
    }

    if (empty($_FILES['file']['name'])) {
        $errors[] = "Please choose a file to upload.";
    } else {
        $allowed = ['pdf', 'docx', 'pptx'];
        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Only PDF, DOCX, and PPTX files are allowed.";
        }
    }

    if (empty($errors)) {
        // File Size (KB/MB) 
        $bytes = $_FILES['file']['size'];
        $fileSize = ($bytes >= 1048576) ? round($bytes / 1048576, 1) . ' MB' : round($bytes / 1024, 1) . ' KB';

        $safeName = uniqid('res_') . '.' . $ext;
        $target = 'uploads/' . $safeName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
            // 8 ?'s for 8 columns
            $stmt = $pdo->prepare("INSERT INTO resources (title, category, semester, description, file_type, file_size, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            
            
            $stmt->execute([
                $title,
                $category,
                $semester,
                $desc,
                strtoupper($ext),
                $fileSize,
                $target,
                $_SESSION['user_id']
            ]);
            $success = true;
        } else {
            $errors[] = "File upload failed. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Upload Resource | UniShare</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:600px;">
    <h1 class="h3 fw-bold mb-4">Upload a Resource</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Uploaded successfully! <a href="resources.php">View in Catalog</a></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Semester</label>
            <select name="semester" class="form-select" required>
                <option value="Semester 1">Semester 1</option>
                <option value="Semester 2">Semester 2</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="subject_category" class="form-select" required>
                <option value="Programming">Programming</option>
                <option value="Maths">Maths</option>
                <option value="Web Tech">Web Tech</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">File (PDF, DOCX, or PPTX)</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.docx,.pptx" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Upload</button>
    </form>
</div>
</body>
</html>