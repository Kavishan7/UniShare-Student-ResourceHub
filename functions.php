<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clean any user-submitted string before using/displaying it
function clean($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Basic email format check
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Is someone logged in?
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect helper
function redirect($path) {
    header("Location: $path");
    exit;
}

// Call this at the top of any page that requires login (e.g. dashboard.php, upload.php)
function requireLogin() {
    if (!isLoggedIn()) {
        // Project root folder /unishare/
        redirect('/unishare/auth/login.php');
    }
}