<?php
$mysqli = new mysqli("localhost", "root", "", "webbansach");

// Kiểm tra kết nối
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['book_title'] ?? '';
    $description = $_POST['book_describe'] ?? '';
    $author = $_POST['book_author'] ?? '';
    $publisher = $_POST['book_publisher'] ?? '';
    $size = $_POST['book_size'] ?? '';
    $price = $_POST['book_original_price'] ?? 0;
    $discount = $_POST['book_discount'] ?? 0;
    $quantity = $_POST['book_quantity'] ?? 0;
    $categories = isset($_POST['category']) ? implode(", ", $_POST['category']) : '';

    // Xử lý upload hình ảnh
    $image = $_FILES['image']['name'] ?? '';
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "File không phải là hình ảnh.";
            $uploadOk = 0;
        }
    }

    if (file_exists($target_file)) {
        $message = "Xin lỗi, file đã tồn tại.";
        $uploadOk = 0;
    }

    if ($_FILES["image"]["size"] > 500000) { 
        $message = "Xin lỗi, file quá lớn.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Xin lỗi, chỉ cho phép các định dạng JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $message = "Xin lỗi, không thể upload file.";
    } else {

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $stmt = $mysqli->prepare("INSERT INTO tbl_book (book_title, book_author, book_original_price, book_discount, book_image, book_category, book_size, book_describe, book_quantity, book_publisher) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssissis", $title, $author, $price, $discount, $target_file, $categories, $size, $description, $quantity, $publisher);

            if ($stmt->execute()) {
                $message = "Dữ liệu đã được lưu thành công!";
            } else {
                $message = "Lỗi: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Xin lỗi, đã có lỗi xảy ra khi upload file.";
        }
    }
}

?>