<?php
session_start();

include_once('db/connect.php');

// Truy vấn để lấy thông tin tất cả đơn hàng
$sql = "SELECT id_order, id_user, order_date, shipping_address, order_status, total_amount
        FROM tbl_order
        ORDER BY order_date DESC";

$result = $mysqli->query($sql);

$orders = array();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($orders);
?>