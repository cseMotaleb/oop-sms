<?php
session_start();
include 'db.php';
include 'Student.php';

$students = Student::all($pdo);

// Add student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $roll = $_POST['roll'];
    $department = $_POST['department'];

    $student = new Student($name, $roll, $department);
    if ($student->save($pdo)) {
        $_SESSION['message'] = "✅ Student added successfully!";
    } else {
        $_SESSION['message'] = "❌ Failed to add student!";
    }
    header("Location: index.php");
    exit;
}

// Search or show all
$searchRoll = $_GET['search'] ?? '';
$students = ($searchRoll !== '') ? Student::search($pdo, $searchRoll) : Student::all($pdo);

$perPage = 5;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;
$total = Student::countAll($pdo);
$totalPages = ceil($total / $perPage);
$students = Student::paginate($pdo, $perPage, $offset);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        .flash {
            padding: 10px;
            margin: 10px 0;
            background: #dff0d8;
            color: #3c763d;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <h2>➕ Add Student</h2>
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="roll" class="form-control" placeholder="Roll" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="department" class="form-control" placeholder="Department" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Add</button>
            </div>
        </form>

        <h2>🔍 Search by Roll</h2>
        <form method="GET">
            <input type="number" name="search" placeholder="Enter Roll">
            <button type="submit">Search</button>
        </form>

        <h2>📋 Student List</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Roll</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['roll']) ?></td>
                        <td><?= htmlspecialchars($s['department']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $s['id'] ?>">Edit</a>
                            <a href="delete.php?id=<?= $s['id'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('⚠️ Are you sure you want to delete this student?');">🗑️ Delete</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>