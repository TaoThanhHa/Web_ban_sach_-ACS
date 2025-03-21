<?php
include_once('db/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$book = null;
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

if ($book_id > 0) {
    $query = "SELECT * FROM tbl_book WHERE book_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy sách.";
        exit;
    }
    $stmt->close();
} else {
    echo "ID sách không hợp lệ.";
    exit;
}

// Xử lý form sửa thông tin sách
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['book_title']) ? $mysqli->real_escape_string($_POST['book_title']) : '';
    $description = isset($_POST['book_describe']) ? $mysqli->real_escape_string($_POST['book_describe']) : '';
    $author = isset($_POST['book_author']) ? $mysqli->real_escape_string($_POST['book_author']) : '';
    $publisher = isset($_POST['book_publisher']) ? $mysqli->real_escape_string($_POST['book_publisher']) : '';
    $size = isset($_POST['book_size']) ? $mysqli->real_escape_string($_POST['book_size']) : '';
    $price = isset($_POST['book_original_price']) ? (float)$_POST['book_original_price'] : 0;
    $discount = isset($_POST['book_discount']) ? (int)$_POST['book_discount'] : 0;
    $quantity = isset($_POST['book_quantity']) ? (int)$_POST['book_quantity'] : 0;
    $category = isset($_POST['book_category']) ? (int)$_POST['book_category'] : 0;

    // Cập nhật thông tin sách
    $query = "UPDATE tbl_book SET book_title = ?, book_describe = ?, book_author = ?, book_publisher = ?, book_size = ?, book_original_price = ?, book_discount = ?, book_quantity = ?, book_category = ? WHERE book_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssdiiii", $title, $description, $author, $publisher, $size, $price, $discount, $quantity, $category, $book_id);

    if ($stmt->execute()) {
        $message = "Thông tin sách đã được cập nhật thành công.";
        header("Location: Quản_lý_sách.php?message=" . urlencode($message));
        exit;
    } else {
        $message = "Lỗi: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Tao_san_pham.css" type="text/css">
    <title>Chỉnh sửa sách</title>
</head>
<body>
    <div class="container">
        <h1>Chỉnh sửa sách</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($book): ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="book_title">Tiêu đề:</label>
                    <input type="text" class="form-control" id="book_title" name="book_title" value="<?php echo htmlspecialchars($book['book_title']); ?>">
                </div>

                <div class="form-group">
                    <label for="book_describe">Mô tả:</label>
                    <textarea class="form-control" id="book_describe" name="book_describe"><?php echo htmlspecialchars($book['book_describe']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="book_author">Tác giả:</label>
                    <input type="text" class="form-control" id="book_author" name="book_author" value="<?php echo htmlspecialchars($book['book_author']); ?>">
                </div>

                <div class="form-group">
                    <label for="book_publisher">Nhà xuất bản:</label>
                    <select class="form-control" id="book_publisher" name="book_publisher">
                        <option value="Nhà xuất bản Kim Đồng" <?php echo ($book['book_publisher'] == 'Nhà xuất bản Kim Đồng') ? 'selected' : ''; ?>>Nhà xuất bản Kim Đồng</option>
                        <option value="Nhà xuất bản Trẻ" <?php echo ($book['book_publisher'] == 'Nhà xuất bản Trẻ') ? 'selected' : ''; ?>>Nhà xuất bản Trẻ</option>
                        <option value="Nhà xuất bản IPM" <?php echo ($book['book_publisher'] == 'Nhà xuất bản IPM') ? 'selected' : ''; ?>>Nhà xuất bản IPM</option>
                        <option value="Nhà xuất bản Amak" <?php echo ($book['book_publisher'] == 'Nhà xuất bản Amak') ? 'selected' : ''; ?>>Nhà xuất bản Amak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="book_size">Kích thước:</label>
                    <select class="form-control" id="book_size" name="book_size">
                        <option value="11.3x17.6 cm" <?php echo ($book['book_size'] == '11.3x17.6 cm') ? 'selected' : ''; ?>>11.3x17.6 cm</option>
                        <option value="13x18 cm" <?php echo ($book['book_size'] == '13x18 cm') ? 'selected' : ''; ?>>13x18 cm</option>
                        <option value="15.7x24 cm" <?php echo ($book['book_size'] == '15.7x24 cm') ? 'selected' : ''; ?>>15.7x24 cm</option>
                        <option value="14.5x20.5 cm" <?php echo ($book['book_size'] == '14.5x20.5 cm') ? 'selected' : ''; ?>>14.5x20.5 cm</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="book_original_price">Số tiền:</label>
                    <input type="number" class="form-control" id="book_original_price" name="book_original_price" value="<?php echo htmlspecialchars($book['book_original_price']); ?>">
                </div>

                <div class="form-group">
                    <label for="book_discount">Phần trăm giảm:</label>
                    <input type="number" class="form-control" id="book_discount" name="book_discount" value="<?php echo htmlspecialchars($book['book_discount']); ?>">
                </div>

                <div class="form-group">
                    <label for="book_quantity">Số lượng:</label>
                    <input type="number" class="form-control" id="book_quantity" name="book_quantity" value="<?php echo htmlspecialchars($book['book_quantity']); ?>">
                </div>

                <div class="form-group">
                    <label for="book_category">Thể loại:</label>
                    <select class="form-control" id="book_category" name="book_category">
                        <?php
                            $mysqli2 = new mysqli("localhost", "root", "", "webbansach");

                            $sql_category = "SELECT * FROM tbl_category ORDER BY category_id DESC";
                            $spl_category = $mysqli2->query($sql_category);

                            if ($spl_category) {
                                while ($category = $spl_category->fetch_assoc()) {
                                    $selected = ($book['book_category'] == $category['category_id']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($category['category_id']) . '" ' . $selected . '>' . htmlspecialchars($category['category_name']) . '</option>';
                                }
                            }
                            $mysqli2->close();
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="Quản_lý_sách.php" class="btn btn-secondary">Hủy</a>
            </form>
        <?php else: ?>
            <p>Không tìm thấy sách.</p>
        <?php endif; ?>
    </div>
</body>
</html>