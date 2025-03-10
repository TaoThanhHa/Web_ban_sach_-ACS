<?php
include_once('db/connect.php');

// 2. Lấy ID từ URL
$id = $_GET["id"];

// Kiểm tra xem form đã được submit chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Xử lý dữ liệu form
    $reply = $_POST["reply"];

    // Cập nhật trạng thái và reply trong bảng `contacts`
    $sql = "UPDATE contacts SET status = 'replied', reply = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $reply, $id);  // 's' cho reply (string), 'i' cho id (integer)

    if ($stmt->execute()) {
        echo "Đã trả lời yêu cầu!";
        // Chuyển hướng về trang Hỗ trợ.php
        header("Location: Hỗ_trợ.php");
        exit();
    } else {
        echo "Lỗi cập nhật: " . $stmt->error;
    }

    $stmt->close();
}

// 3. Lấy thông tin yêu cầu từ database để hiển thị
$sql = "SELECT * FROM contacts WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Trả lời yêu cầu</title>
</head>
<body>
    <h1>Trả lời yêu cầu hỗ trợ</h1>
    <p><strong>Tên:</strong> <?php echo htmlspecialchars($row["name"]); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($row["email"]); ?></p>
    <p><strong>Nội dung:</strong> <?php echo htmlspecialchars($row["comment"]); ?></p>

    <form method="POST">
        <label for="reply">Câu trả lời:</label><br>
        <textarea id="reply" name="reply" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Gửi trả lời</button>
    </form>
</body>
</html>
<?php
} else {
    echo "Không tìm thấy yêu cầu.";
}

$stmt->close();
$mysqli->close();
?>