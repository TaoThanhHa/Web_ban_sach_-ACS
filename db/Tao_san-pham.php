<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "webbansach");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
$message_type = 'error';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($mysqli, $_POST['book_title'] ?? '');
    $description = mysqli_real_escape_string($mysqli, $_POST['book_describe'] ?? '');
    $author = mysqli_real_escape_string($mysqli, $_POST['book_author'] ?? '');
    $publisher = mysqli_real_escape_string($mysqli, $_POST['book_publisher'] ?? ''); 
    $size = mysqli_real_escape_string($mysqli, $_POST['book_size'] ?? '');
    $price = (float)$_POST['book_original_price'] ?? 0.0;
    $discount = (float)$_POST['book_discount'] ?? 0.0;
    $quantity = (int)$_POST['book_quantity'] ?? 0;
    $category = (int)$_POST['book_category'] ?? 0;

    $image = $_FILES['image']['name'] ?? '';
    $target_dir = "../images/";
    $image_name = uniqid() . "_" . basename($image);
    $target_file = $target_dir . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Lỗi upload file: " . $_FILES["image"]["error"];
        $uploadOk = 0;
    }

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "File không phải là hình ảnh.";
            $uploadOk = 0;
        }
    }

    if ($_FILES["image"]["size"] > 5000000) {
        $message = "Xin lỗi, file quá lớn.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Xin lỗi, chỉ cho phép các định dạng JPG, JPEG, PNG & GIF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = "../images/" . $image_name;

            $stmt = $mysqli->prepare("INSERT INTO tbl_book (book_title, book_author, book_original_price, book_discount, book_image, book_category, book_size, book_describe, book_quantity, book_publisher) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssddsisssi", $title, $author, $price, $discount, $image_path, $category, $size, $description, $quantity, $publisher);

            if ($stmt->execute()) {
                $message = "Dữ liệu đã được lưu thành công!";
                $message_type = 'success';
            } else {
                $message = "Lỗi: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Xin lỗi, đã có lỗi xảy ra khi tải lên file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đang chuyển hướng...</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type == 'success' ? 'success' : 'danger'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <p>Đang chuyển hướng đến trang quản trị...</p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "../Admin.php";
        }, 1500);
    </script>
</body>
</html>