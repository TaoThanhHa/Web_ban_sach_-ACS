<?php
session_start(); // Thêm session_start() ở đầu file
include_once('db/connect.php');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để gửi liên hệ.";
    exit(); // Hoặc chuyển hướng đến trang đăng nhập
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

// 2. Lấy dữ liệu từ form
$name = $_POST["name"];
$email = $_POST["email"];
$comment = $_POST["comment"];

// 3. Chuẩn bị và thực thi câu lệnh SQL
// Sử dụng prepared statement để tránh SQL injection
$sql = "INSERT INTO contacts (user_id, name, email, comment) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("isss", $user_id, $name, $email, $comment);  // "isss" biểu thị integer, string, string, string

if ($stmt->execute()) {
    echo "Gửi liên hệ thành công!";
    // Chuyển hướng người dùng về trang liên hệ sau khi gửi thành công
    header("Location: liên_hệ.php?success=1");
    exit();
} else {
    echo "Lỗi: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>