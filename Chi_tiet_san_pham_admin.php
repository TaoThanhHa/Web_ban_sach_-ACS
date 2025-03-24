<?php
include_once('db/connect.php');

// Hàm để xử lý lỗi database
function db_error($mysqli) {
    echo "Lỗi database: " . $mysqli->error;
    exit;
}

// Lấy book_id từ URL
$book_id = isset($_GET['book_id']) ? (int)$_GET['book_id'] : 0;

// Truy vấn để lấy thông tin chi tiết sách
$query = "SELECT * FROM tbl_book WHERE book_id = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    db_error($mysqli);
}

$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem có sách nào không
if ($result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    echo "<p>Không tìm thấy sách.</p>";
    exit();
}


// Truy vấn để lấy sách liên quan (xem thêm)
$query_related = "SELECT * FROM tbl_book ORDER BY RAND() LIMIT 12";
$result_related = $mysqli->query($query_related);

if (!$result_related) {
    db_error($mysqli);
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Chi_tiet_sản_phẩm.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title><?php echo isset($book['book_title']) ? htmlspecialchars($book['book_title']) : 'Chi tiết sách'; ?></title>
       
</head>
<body>

    <!-- Header -->
    <?php include 'header_admin.php'; ?>

    <!-- Story Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="avata">
                    <img src="images/<?php echo isset($book['book_image']) ? htmlspecialchars($book['book_image']) : 'default.jpg'; ?>" alt="<?php echo isset($book['book_title']) ? htmlspecialchars($book['book_title']) : 'Sách'; ?>" class="img-fluid">
                    <div class="discount-badge"><?php echo isset($book['book_discount']) ? htmlspecialchars($book['book_discount']) : '0'; ?>%</div>
                </div>
            </div>

            <div class="col-md-8">
                <h2 class="mb-3"><?php echo isset($book['book_title']) ? htmlspecialchars($book['book_title']) : 'Chưa có tiêu đề'; ?></h2>
                <ul class="list-unstyled">
                    <li>Tác giả: <span><?php echo isset($book['book_author']) ? htmlspecialchars($book['book_author']) : 'Không xác định'; ?></span></li>
                    <li>Nhà xuất bản: <span><?php echo isset($book['book_publisher']) ? htmlspecialchars($book['book_publisher']) : 'Không xác định'; ?></span></li>
                    <li>Kích thước: <span><?php echo isset($book['book_size']) ? htmlspecialchars($book['book_size']) : 'Không xác định'; ?></span></li>
                    <li>
                        Nội dung:
                        <div class="story-content"><?php echo isset($book['book_describe']) ? htmlspecialchars($book['book_describe']) : 'Chưa có mô tả'; ?></div>
                    </li>
                </ul>

                <?php
                $original_price = isset($book['book_original_price']) ? $book['book_original_price'] : 0;
                $discount = isset($book['book_discount']) ? $book['book_discount'] : 0;
                $price = $original_price * (1 - $discount / 100);
                ?>

                <div class="mb-3">
                    <span class="price"><?php echo number_format($price, 0, ',', '.'); ?>đ</span>
                    <del class="original-price"><?php echo number_format($original_price, 0, ',', '.'); ?>đ</del>
                </div>

                <div class="box-counter">
                    <form method="post" action="Xử_lý_sách.php" style="display:inline;">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book['book_id']); ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                    <a href="Sửa_sách.php?book_id=<?php echo htmlspecialchars($book['book_id']); ?>" class="btn btn-primary btn-sm">Sửa</a>
                </div>
            </div>
        </div>

        <!-- Tabbed Content -->
        <div class="mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mota-tab" data-toggle="tab" href="#mota" role="tab" aria-controls="mota" aria-selected="true">Mô tả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="binhluan-tab" data-toggle="tab" href="#binhluan" role="tab" aria-controls="binhluan" aria-selected="false">Bình luận</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="mota" role="tabpanel" aria-labelledby="mota-tab">
                    <p><?php echo isset($book['book_describe']) ? htmlspecialchars($book['book_describe']) : 'Chưa có mô tả'; ?></p>
                </div>
                <div class="tab-pane fade" id="binhluan" role="tabpanel" aria-labelledby="binhluan-tab">
                    <!-- Phần bình luận sẽ được thêm vào sau -->
                    <p>Chức năng bình luận đang được phát triển.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="javascript/Chi_tiet_san_pham.js"></script>
</body>
</html>
<?php
// Đóng kết nối
$mysqli->close();
?>