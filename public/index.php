<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/db.php';

try {
    $pdo = getDbConnection();
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
    $students = $stmt->fetchAll();
} catch (Exception $e) {
    die("Loi ket noi CSDL: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <style>
        body { font-family: sans-serif; background: #0f172a; color: #fff; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #1e293b; }
        th, td { padding: 12px; border: 1px solid #334155; text-align: left; }
        th { background: #334155; }
    </style>
</head>
<body>
    <h1>Student Portal</h1>
    <h2>Danh Sách Sinh Viên</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Mã SV</th>
            <th>Họ và Tên</th>
            <th>Email</th>
            <th>Ngành học</th>
            <th>Năm</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student['id']) ?></td>
            <td><?= htmlspecialchars($student['student_code']) ?></td>
            <td><?= htmlspecialchars($student['full_name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= htmlspecialchars($student['major']) ?></td>
            <td>Năm <?= htmlspecialchars($student['year_level']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
