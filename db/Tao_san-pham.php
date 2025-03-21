<?php
$mysqli = new mysqli("localhost", "root", "", "webbansach");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['book_title'] ?? '';
    $description = $_POST['book_describe'] ?? '';
    $author = $_POST['book_author'] ?? '';
    $publisher = $_POST['book_publisher'] ?? '';
    $size = $_POST['book_size'] ?? '';
    $price = $_POST['book_original_price'] ?? 0;
    $discount = $_POST['book_discount'] ?? 0;
    $quantity = $_POST['book_quantity'] ?? 0;
    $category = $_POST['book_category'] ?? 0;

    // Xử lý upload hình ảnh
    $image = $_FILES['image']['name'] ?? '';
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra xem file có phải là ảnh không
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "File không phải là hình ảnh.";
            $uploadOk = 0;
        }
    }

    // Kiểm tra xem file đã tồn tại chưa
    if (file_exists($target_file)) {
        $message = "Xin lỗi, file đã tồn tại.";
        $uploadOk = 0;
    }

    // Kiểm tra kích thước file
    if ($_FILES["image"]["size"] > 500000) {
        $message = "Xin lỗi, file quá lớn.";
        $uploadOk = 0;
    }

    // Kiểm tra định dạng file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Xin lỗi, chỉ cho phép các định dạng JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        // Di chuyển file đã tải lên vào thư mục đích
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Sử dụng prepared statements để tránh SQL injection
            $stmt = $mysqli->prepare("INSERT INTO tbl_book (book_title, book_author, book_original_price, book_discount, book_image, book_category, book_size, book_describe, book_quantity, book_publisher) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssissis", $title, $author, $price, $discount, $target_file, $category, $size, $description, $quantity, $publisher);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                $message = "Dữ liệu đã được lưu thành công!";
            } else {
                $message = "Lỗi: " . $stmt->error;
            }

            // Đóng statement
            $stmt->close();
        } else {
            $message = "Xin lỗi, đã có lỗi xảy ra khi upload file.";
        }
    }
}

header("Location: ../Quản_lý_sách.php?message=" . urlencode($message));
exit;

?>