<?php
session_start();
include_once('db/connect.php');

$book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
$book_title = isset($_POST['book_title']) ? $_POST['book_title'] : '';
$book_image = isset($_POST['book_image']) ? $_POST['book_image'] : '';
$book_price = isset($_POST['book_price']) ? $_POST['book_price'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Kiểm tra nếu giỏ hàng chưa tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Kiểm tra nếu sản phẩm đã có trong giỏ
if (isset($_SESSION['cart'][$book_id])) {
    $_SESSION['cart'][$book_id]['quantity'] += $quantity;
} else {
    $_SESSION['cart'][$book_id] = [
        'title' => $book_title,
        'image' => $book_image,
        'price' => $book_price,
        'quantity' => $quantity
    ];
}

$_SESSION['message'] = "Thêm sản phẩm thành công!";

header('Location: Chi_tiet_san_pham.php?book_id=' . $book_id);
exit();
?>
