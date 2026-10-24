<?php
require_once __DIR__ . '/../src/db.php';

echo "Dang ket noi toi CSDL...\n";
try {
    $pdo = getDbConnection();
    $sqlCreateTable = "
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_code VARCHAR(50) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        major VARCHAR(100) NOT NULL,
        year_level INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sqlCreateTable);
    echo "-> Da tao/kiem tra bang 'students' thanh cong.\n";

    $stmt = $pdo->query("SELECT COUNT(*) FROM students");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $sqlInsert = "
        INSERT INTO students (student_code, full_name, email, major, year_level) VALUES
        ('DC001', 'Trần Văn Bình', 'vanbinh@gmail.com', 'Cơ khí ô tô', 2),
        ('DC002', 'Thạch Võ Trọng Tín', 'trongtin@gmail.com', 'Công nghệ thông tin', 3),
        ('DC003', 'Nguyễn Lương Minh Anh', 'minhanh@gmail.com', 'Điều dưỡng', 4);";
        $pdo->exec($sqlInsert);
        echo "-> Da chen 3 ban ghi du lieu mau vao CSDL.\n";
    } else {
        echo "-> Bang 'students' da co san ($count ban ghi).\n";
    }
    echo "====== KHOI TAO BANG VA DU LIEU MAU TREN UPSUN THANH CONG!\n";
} catch (PDOException $e) {
    echo "LOI KHONG THE KHOI TAO CSDL: " . $e->getMessage() . "\n";
    exit(1);
}
?>
