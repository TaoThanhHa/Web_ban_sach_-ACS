<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once('db/connect.php');

// Truy vấn để lấy thông tin tất cả đơn hàng và tên người dùng
$sql = "SELECT 
            o.id_order, 
            o.id_user, 
            o.shipping_address, 
            o.order_status, 
            o.total_amount,
            o.order_date,  -- Giữ nguyên để chuyển đổi trong PHP
            u.name AS user_name  -- Lấy tên người dùng
        FROM tbl_order o
        INNER JOIN tbl_user u ON o.id_user = u.id  -- JOIN với bảng tbl_user
        ORDER BY o.order_date DESC";

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
echo json_encode($orders, JSON_UNESCAPED_UNICODE); //Thêm JSON_UNESCAPED_UNICODE
?>