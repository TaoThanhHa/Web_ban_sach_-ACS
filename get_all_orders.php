<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once('db/connect.php');

// Truy vấn để lấy thông tin tất cả đơn hàng
$sql = "SELECT id_order, 
               id_user, 
               shipping_address, 
               order_status, 
               total_amount,
               order_date -- Giữ nguyên để chuyển đổi trong PHP
        FROM tbl_order
        ORDER BY order_date DESC";

$result = $mysqli->query($sql);

$orders = array();
while ($row = $result->fetch_assoc()) {
    // Chuyển đổi múi giờ trong PHP
    $order_date = new DateTime($row['order_date'], new DateTimeZone('UTC'));
    $order_date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
    $row['order_date'] = $order_date->format('Y-m-d H:i:s'); // Định dạng lại nếu cần
    $orders[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($orders);
?>