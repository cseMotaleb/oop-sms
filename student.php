<?php
class Student {
    public $name;
    public $roll;
    public $department;

    // constructor to initialize student properties
    public function __construct($name, $roll, $department) {
        $this->name = $name;
        $this->roll = $roll;
        $this->department = $department;
    }

    // Insert student to DB
    public function save($pdo) {
        // Check if roll already exists
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE roll = ?");
        $checkStmt->execute([$this->roll]);
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            return false; // Roll already exists
        }

        // If not exists, then insert
        $stmt = $pdo->prepare("INSERT INTO students (name, roll, department) VALUES (?, ?, ?)");
        return $stmt->execute([$this->name, $this->roll, $this->department]);
    }


    // Static method to get all students
    public static function all($pdo) {
        $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Static method to search by roll
    public static function search($pdo, $roll) {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE roll = ?");
        $stmt->execute([$roll]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete student by ID
    // Static method to delete a student
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Student by ID
    // Static method to find a student by ID
    public static function find($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
   
    public static function update($pdo, $id, $name, $roll, $department) {
        $stmt = $pdo->prepare("UPDATE students SET name=?, roll=?, department=? WHERE id=?");
        return $stmt->execute([$name, $roll, $department, $id]);
    }

    public static function countAll($pdo) {
        return $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    }

    // Static method to paginate students
    public static function paginate($pdo, $limit, $offset) {
        $stmt = $pdo->prepare("SELECT * FROM students ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

