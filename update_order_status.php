<?php
session_start();

include_once('db/connect.php');


// Lấy order ID và new status từ tham số POST
$order_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$new_status = isset($_POST['status']) ? $_POST['status'] : '';

if ($order_id <= 0 || empty($new_status)) {
    echo json_encode(array('error' => 'Order ID hoặc trạng thái mới không hợp lệ.'));
    exit();
}

// Cập nhật trạng thái đơn hàng trong cơ sở dữ liệu
$sql = "UPDATE tbl_order SET order_status = ? WHERE id_order = ?";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('error' => 'Lỗi prepare: ' . $mysqli->error));
    exit();
}

$stmt->bind_param("si", $new_status, $order_id);

if ($stmt->execute()) {
    echo json_encode(array('success' => 'Cập nhật trạng thái đơn hàng thành công.'));
} else {
    echo json_encode(array('error' => 'Lỗi execute: ' . $stmt->error));
}

$stmt->close();
?>