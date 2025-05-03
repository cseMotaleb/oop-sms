<?php
$host = 'localhost';
$user = 'root'; // তোমার ইউজার যদি আলাদা হয় সেটাও দাও
$pass = '';     // XAMPP হলে পাসওয়ার্ড সাধারণত ফাঁকা
$dbname = 'student_db';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
