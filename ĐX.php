<?php
session_start();

// Xóa tất cả các biến session bằng cách gán một array rỗng
$_SESSION = array();

// Hủy session
session_destroy();

// Xóa cookie (nếu có, và nếu bạn đang sử dụng chúng)
if (isset($_COOKIE['remember_me'])) {
    $remember_me_cookie = (string)$_COOKIE['remember_me']; // Ép kiểu thành string
    unset($_COOKIE['remember_me']);
    setcookie('remember_me', '', time() - 3600, '/'); // Xóa cookie bằng cách đặt thời gian hết hạn trong quá khứ và giá trị là string rỗng
}

// Chuyển hướng đến trang chủ
header("Location: ĐN.php"); // Hoặc trang đăng nhập, tùy theo ý bạn
exit();
?>