<?php
session_start();
include_once('db/connect.php');

// Nhận mảng các thay đổi từ AJAX
$changes = json_decode(file_get_contents("php://input"), true);

if ($changes === null && json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
    exit();
}

// Duyệt qua các thay đổi và thực hiện cập nhật
foreach ($changes as $change) {
    $id = intval($change['id']);
    $field = $change['field'];
    $value = trim($change['value']);

    // Kiểm tra input hợp lệ (LOẠI BỎ EMAIL VÀ PHONE)
    if (!in_array($field, ['name', 'status'])) {
        echo json_encode(['status' => 'error', 'message' => 'Trường không hợp lệ: ' . $field]);
        exit();
    }

    if ($field === 'status' && !in_array($value, ['0', '1'])) {
        echo json_encode(['status' => 'error', 'message' => 'Giá trị trạng thái không hợp lệ: ' . $value]);
        exit();
    }

    // Chuẩn bị và thực thi truy vấn cập nhật
    $stmt = $mysqli->prepare("UPDATE tbl_user SET $field = ? WHERE id = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi SQL: ' . $mysqli->error]);
        exit();
    }

    $stmt->bind_param('si', $value, $id);

    if ($stmt->execute()) {
        // Cập nhật thành công, không cần echo gì ở đây
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật ID ' . $id . ': ' . $stmt->error]);
        exit();
    }

    $stmt->close();
}

// Tất cả các cập nhật đã thành công
echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công!']);

$mysqli->close();
exit();
?>