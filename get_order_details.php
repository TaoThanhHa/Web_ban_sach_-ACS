<?php
session_start();

include_once('db/connect.php');

// Kiểm tra xem user đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('error' => 'Bạn cần đăng nhập để xem chi tiết đơn hàng.'));
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy order ID từ tham số GET
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    echo json_encode(array('error' => 'Order ID không hợp lệ.'));
    exit();
}

// Truy vấn để lấy thông tin đơn hàng
$sql_order = "SELECT id_order, 
              order_date,
              shipping_address, 
              order_status, 
              total_amount, 
              shipping_method, 
              payment_method, 
              shipping_fee
              FROM tbl_order
              WHERE id_order = ? AND id_user = ?";

$stmt_order = $mysqli->prepare($sql_order);

if ($stmt_order === false) {
    echo json_encode(array('error' => 'Lỗi prepare (tbl_order): ' . $mysqli->error));
    exit();
}

$stmt_order->bind_param("ii", $order_id, $user_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

if ($result_order->num_rows === 0) {
    echo json_encode(array('error' => 'Không tìm thấy đơn hàng.'));
    exit();
}

$order = $result_order->fetch_assoc();

// Chuyển đổi múi giờ trong PHP
$order_date = new DateTime($order['order_date'], new DateTimeZone('UTC'));
$order_date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
$order['order_date'] = $order_date->format('Y-m-d H:i:s'); // Định dạng lại nếu cần

$stmt_order->close();

// Truy vấn để lấy thông tin các sản phẩm trong đơn hàng
$sql_items = "SELECT b.book_id, b.book_title, oi.quantity, oi.price 
              FROM tbl_order_item oi
              JOIN tbl_book b ON oi.book_id = b.book_id
              WHERE oi.id_order = ?";

$stmt_items = $mysqli->prepare($sql_items);

if ($stmt_items === false) {
    echo json_encode(array('error' => 'Lỗi prepare (tbl_order_item): ' . $mysqli->error));
    exit();
}

$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

$items = array();
while ($row = $result_items->fetch_assoc()) {
    $items[] = $row;
}
$stmt_items->close();

// Kết hợp thông tin đơn hàng và thông tin sản phẩm
$order['items'] = $items;

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($order);