<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once('db/connect.php');

// Kiểm tra xem user đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('error' => 'Bạn cần đăng nhập để xem đơn hàng.'));
    exit();
}

$user_id = $_SESSION['user_id'];

// Truy vấn để lấy thông tin đơn hàng của người dùng hiện tại
$sql = "SELECT id_order, order_date, shipping_address, order_status, total_amount
        FROM tbl_order
        WHERE id_user = ?
        ORDER BY order_date DESC";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('error' => 'Lỗi prepare: ' . $mysqli->error));
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = array();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($orders);
?>