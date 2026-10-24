<?php
function getDbConnection() {
    // Đọc thông tin kết nối từ biến môi trường của Upsun / Platform.sh
    $relationships = json_decode(base64_decode(getenv('PLATFORM_RELATIONSHIPS')), true);
    
    if ($relationships && isset($relationships['database'])) {
        $db = $relationships['database'][0];
        $host = $db['host'];
        $port = $db['port'];
        $dbname = $db['path'];
        $username = $db['username'];
        $password = $db['password'];
    } else {
        // Cấu hình chạy trên máy cục bộ (Localhost)
        $host = '127.0.0.1';
        $port = 3306;
        $dbname = 'student_management';
        $username = 'trongtin';
        $password = 'trongtin';
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}
?>
