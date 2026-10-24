<?php
require_once __DIR__ . '/../src/db.php';

$pdo = getDbConnection();
$editStudent = null;

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: index.php");
    exit();
}

// Handle Form Submit (Add / Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['student_code'] ?? '';
    $name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $major = $_POST['major'] ?? '';
    $year = $_POST['year_level'] ?? 1;
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Update
        $stmt = $pdo->prepare("UPDATE students SET student_code=?, full_name=?, email=?, major=?, year_level=? WHERE id=?");
        $stmt->execute([$code, $name, $email, $major, $year, $id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO students (student_code, full_name, email, major, year_level) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$code, $name, $email, $major, $year]);
    }
    header("Location: index.php");
    exit();
}

// Get student data for edit form
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editStudent = $stmt->fetch();
}

// Fetch all students
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll();
$totalStudents = count($students);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Student Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div>
            <h1>Student Portal</h1>
            <p style="color: var(--text-secondary); margin: 5px 0 0 0;">Hệ thống quản lý thông tin sinh viên theo thời gian thực</p>
        </div>
        <div class="badge-total">
            📊 Tổng sinh viên: <?= $totalStudents ?>
        </div>
    </div>

    <div class="container">
        <!-- Form Thêm / Sửa Sinh Viên -->
        <div class="card">
            <h2>👤 <?= $editStudent ? 'Sửa Sinh Viên' : 'Thêm Sinh Viên Mới' ?></h2>
            <form method="POST" action="index.php">
                <?php if ($editStudent): ?>
                    <input type="hidden" name="id" value="<?= $editStudent['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>MÃ SINH VIÊN</label>
                    <input type="text" name="student_code" required value="<?= htmlspecialchars($editStudent['student_code'] ?? '') ?>" placeholder="Ví dụ: DC001">
                </div>

                <div class="form-group">
                    <label>HỌ VÀ TÊN</label>
                    <input type="text" name="full_name" required value="<?= htmlspecialchars($editStudent['full_name'] ?? '') ?>" placeholder="Ví dụ: Nguyễn Văn A">
                </div>

                <div class="form-group">
                    <label>ĐỊA CHỈ EMAIL</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($editStudent['email'] ?? '') ?>" placeholder="nguyenvana@gmail.com">
                </div>

                <div class="form-group">
                    <label>NGÀNH HỌC</label>
                    <input type="text" name="major" required value="<?= htmlspecialchars($editStudent['major'] ?? '') ?>" placeholder="Ví dụ: Công nghệ thông tin">
                </div>

                <div class="form-group">
                    <label>NĂM HỌC</label>
                    <select name="year_level">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($editStudent['year_level'] ?? 1) == $i ? 'selected' : '' ?>>Năm thứ <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="btn"><?= $editStudent ? 'Cập Nhật Thông Tin' : 'Hoàn Tất Thêm Mới' ?></button>
                <?php if ($editStudent): ?>
                    <a href="index.php" style="display:block; text-align:center; margin-top:10px; color:var(--text-secondary); text-decoration:none;">Hủy sửa</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Danh Sách Sinh Viên -->
        <div class="card">
            <h2>📄 Danh Sách Sinh Viên</h2>
            <table>
                <thead>
                    <tr>
                        <th>SINH VIÊN</th>
                        <th>NGÀNH HỌC</th>
                        <th>NĂM</th>
                        <th>HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td>
                            <strong style="color: #67e8f9;"><?= htmlspecialchars($student['student_code']) ?></strong> - <?= htmlspecialchars($student['full_name']) ?><br>
                            <small style="color: var(--text-secondary);"><?= htmlspecialchars($student['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($student['major']) ?></td>
                        <td><span style="background:#1e293b; padding:3px 8px; border-radius:4px; font-size:12px; color:#38bdf8;">Năm <?= $student['year_level'] ?></span></td>
                        <td>
                            <a href="index.php?action=edit&id=<?= $student['id'] ?>" class="btn-action">✏️</a>
                            <a href="index.php?action=delete&id=<?= $student['id'] ?>" class="btn-action delete" onclick="return confirm('Bạn có chắc muốn xóa sinh viên này?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
