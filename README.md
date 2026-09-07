# UniShare — Centralized ICT Resource Platform

ICT 1209 Mini Project | Rajarata University of Sri Lanka

## Theme
Student Resource Hub — students can browse, search, and upload academic resources
(notes, slides, documents) organized by subject category.

## Tech Stack
- HTML5, CSS3, Bootstrap 5
- Vanilla JavaScript
- PHP 8
- MySQL (via XAMPP/WAMP)

## Setup Instructions
1. Install XAMPP (or WAMP) and start Apache + MySQL.
2. Copy the `project/` folder into `htdocs/` (XAMPP) or `www/` (WAMP).
3. Open phpMyAdmin → Import → select `database.sql`. This creates the
   `unishare_db` database with `users`, `messages`, and `resources` tables,
   plus 3 sample resource rows.
4. If your MySQL root user has a password, update it in `includes/db.php`.
5. Visit `http://localhost/project/index.php` in your browser.
6. Register a new account, then log in to upload resources via `upload.php`.

## Features Implemented
- User registration & login with bcrypt password hashing (`password_hash`/`password_verify`)
- Session-based authentication with `session_regenerate_id()` on login
- Contact form storing submissions in the `messages` table
- Resource catalog with live search and category filtering
- File upload (PDF/DOCX/PPTX) tied to the logged-in user, stored in `uploads/`
- All database queries use prepared statements (PDO) to prevent SQL injection

## Folder Structure
See `project/` — matches the structure required in the ICT 1209 Mini Project Guide.

## Individual Contribution
[Fill in: who did frontend, who did backend/database, etc.]
