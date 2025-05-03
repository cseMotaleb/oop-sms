<?php
session_start();
include 'db.php';
include 'Student.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['message'] = "❌ Invalid student ID.";
    $_SESSION['type'] = "danger";
    header("Location: index.php");
    exit;
}

$student = Student::find($pdo, $id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $roll = $_POST['roll'];
    $department = $_POST['department'];

    if (Student::update($pdo, $id, $name, $roll, $department)) {
        $_SESSION['message'] = "✅ Student updated successfully!";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['message'] = "❌ Failed to update student.";
        $_SESSION['type'] = "danger";
    }
    header("Location: edit.php?id=" . $id);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['type']); ?>
        <?php endif; ?>

        <div class="card shadow rounded">
            <div class="card-header bg-primary text-white">
                <h4>✏️ Edit Student</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Student Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roll Number</label>
                        <input type="number" name="roll" class="form-control" value="<?= htmlspecialchars($student['roll']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($student['department']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">💾 Update</button>
                    <a href="index.php" class="btn btn-secondary">🔙 Back</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>