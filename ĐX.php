<?php
session_start();

// Xóa tất cả các biến session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng đến trang đăng nhập
header("Location: Trang_chủ.php"); // Hoặc trang chủ, tùy theo ý bạn
exit();
?>