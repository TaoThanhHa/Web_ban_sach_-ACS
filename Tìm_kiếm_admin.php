<?php
include_once('db/connect.php');

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

if (empty($keyword)) {
    echo "<p>Vui lòng nhập từ khóa tìm kiếm.</p>";
    exit; 
}

$sql = "SELECT * FROM tbl_book WHERE book_title LIKE '%" . $keyword . "%' ORDER BY book_id DESC";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm: <?php echo htmlspecialchars($keyword); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/Trang_chu.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <style>
        .container h1{
            padding-top: 50px;
            margin-bottom: 25px;
        }
        
    </style>
</head>
<body>

    <?php include 'header_admin.php'; ?>

    <div class="container mt-5">
        <h1>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h1>

        <?php
        if ($result->num_rows > 0) {
            echo '<div class="row">';
            while ($row = $result->fetch_assoc()) {
                $original_price = $row['book_original_price'];
                $discount = $row['book_discount'];
                $price = $original_price * (1 - $discount / 100);

                echo '<div class="col-md-3 mb-4">';
                echo '<div class="card">';
                echo '<a href="Chi_tiet_san_pham_admin.php?book_id=' . $row['book_id'] . '">';
                echo '<img src="images/' . htmlspecialchars($row['book_image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['book_title']) . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['book_title']) . '</h5>';
                echo '<p class="card-text">';
                echo '<span class="font-weight-bold">' . number_format($price, 0, ',', '.') . 'đ</span><br>';
                echo '<del class="text-muted">' . number_format($row['book_original_price'], 0, ',', '.') . 'đ</del> ';
                echo '<span class="discount">-' . htmlspecialchars($row['book_discount']) . '%</span>';
                echo '</p>';
                echo '</div>';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "<p>Không tìm thấy kết quả nào cho từ khóa: \"" . htmlspecialchars($keyword) . "\"</p>";
        }
        ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$mysqli->close();
?>