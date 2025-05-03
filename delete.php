<?php
session_start();
include 'db.php';
include 'Student.php';

$id = $_GET['id'] ?? null;

if ($id && Student::delete($pdo, $id)) {
    $_SESSION['message'] = "🗑️ Student deleted successfully!";
} else {
    $_SESSION['message'] = "❌ Failed to delete student!";
}

header("Location: index.php");
exit;
