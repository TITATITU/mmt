<?php
// Lấy thông tin cấu hình kết nối từ biến môi trường của Platform.sh / Upsun
$databaseConfig = json_decode(base64_decode($_ENV['PLATFORM_RELATIONSHIPS']), true);
$db = $databaseConfig['database'][0];

$servername = $db['host'];
$username = $db['username'];
$password = $db['password'];
$dbname = $db['path']; // Tên database được cấp (wordpress)

// Kết nối CSDL sử dụng MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

echo "<h2>Kết nối thành công tới Database WordPress trên Đám mây!</h2>";

// Truy vấn hoặc khởi tạo bảng taikhoan(msv, hoten, ngaysinh, diachi)
$sql_create = "CREATE TABLE IF NOT EXISTS taikhoan (
    msv VARCHAR(50) PRIMARY KEY,
    hoten VARCHAR(100),
    ngaysinh DATE,
    diachi VARCHAR(255)
)";
$conn->query($sql_create);
<$sql = "SELECT msv, hoten, ngaysinh, diachi FROM taikhoan";
$result = $conn->query($sql);

echo "<h3>Danh sách bảng taikhoan:</h3>";
if ($result && $result->num_rows > 0) {
    echo "<table border='1'><tr><th>MSV</th><th>Họ tên</th><th>Ngày sinh</th><th>Địa chỉ</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["msv"]."</td><td>".$row["hoten"]."</td><td>".$row["ngaysinh"]."</td><td>".$row["diachi"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "Chưa có dữ liệu trong bảng taikhoan.";
}

$conn->close();
?>
