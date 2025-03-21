<?php
include_once('db/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($book_id > 0 && $action == 'delete') {
        // Xóa sách
        $query = "DELETE FROM tbl_book WHERE book_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $book_id);

        if ($stmt->execute()) {
            $message = "Sách đã được xóa thành công.";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Yêu cầu không hợp lệ.";
    }
} else {
    $message = "Yêu cầu không hợp lệ.";
}

// Chuyển hướng trở lại trang Quản_lý_sách.php
header("Location: Quản_lý_sách.php?message=" . urlencode($message));
exit;
?>