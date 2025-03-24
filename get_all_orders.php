<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once('db/connect.php');

$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

try {
    // Truy vấn để lấy thông tin tất cả đơn hàng và tên người dùng
    $sql = "SELECT 
                o.id_order, 
                o.id_user, 
                o.shipping_address, 
                o.order_status, 
                o.total_amount,
                o.order_date,
                u.name AS user_name  -- Lấy tên người dùng
            FROM tbl_order o
            INNER JOIN tbl_user u ON o.id_user = u.id  -- JOIN với bảng tbl_user
            WHERE o.id_order LIKE ? OR u.name LIKE ?  -- Thêm mệnh đề WHERE để lọc theo ID đơn hàng hoặc tên người dùng
            ORDER BY o.order_date DESC";

    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        throw new Exception("Lỗi prepare: " . $mysqli->error);
    }

    // Thêm ký tự % vào searchTerm để tìm kiếm gần đúng
    $searchTermLike = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $searchTermLike, $searchTermLike);  // "ss" vì searchTerm là chuỗi cho cả hai điều kiện

    if (!$stmt->execute()) {
        throw new Exception("Lỗi execute: " . $stmt->error);
    }

    $result = $stmt->get_result();

    $orders = array();
    while ($row = $result->fetch_assoc()) {
        // Chuyển đổi múi giờ trong PHP
        $order_date = new DateTime($row['order_date'], new DateTimeZone('UTC'));
        $order_date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
        $row['order_date'] = $order_date->format('Y-m-d H:i:s'); // Định dạng lại nếu cần
        $orders[] = $row;
    }

    $stmt->close();

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($orders, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Xử lý lỗi
    $error = array('error' => $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode($error, JSON_UNESCAPED_UNICODE);
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>