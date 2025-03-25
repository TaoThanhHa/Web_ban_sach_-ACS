<?php
include_once('db/connect.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Trang_chu.css?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">

</head>
<body>
    <?php
        // Truy vấn để lấy slider
        $sql = "SELECT slider_image FROM tbl_slider WHERE slider_active = 1";
        $result = $mysqli->query($sql);
        $slides = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $slides[] = $row['slider_image'];
            }
        }
    ?>

    <?php include 'header_admin.php'; ?>

    <div class="container mt-5 admin">
        <?php
        $sql_category = "SELECT * FROM tbl_category ORDER BY category_id DESC";
        $spl_category = $mysqli->query($sql_category);

        if ($spl_category) {
            while ($category = $spl_category->fetch_assoc()) {
                $category_id = $category['category_id'];
                $category_name = $category['category_name'];

                $books_per_page = 8;

                $query = "SELECT * FROM tbl_book WHERE book_category = ? ORDER BY book_id DESC LIMIT ?";
                $stmt = $mysqli->prepare($query);

                if ($stmt) {
                    $stmt->bind_param("ii", $category_id, $books_per_page);
                    $stmt->execute();
                    $results = $stmt->get_result();

                    echo '<section class="wrapper">';
                    echo '<h2>' . htmlspecialchars($category_name) . '</h2>';
                    echo '<div class="row">';

                    if ($results->num_rows > 0) {
                        while ($row = $results->fetch_assoc()) {
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
                        if ($results->num_rows == $books_per_page) {
                            echo '<div class="col-12 text-center mt-4">';
                            echo '<a href="Phân_loại_admin.php?category_id=' . htmlspecialchars($category_id) . '" class="btn btn-primary">Xem thêm</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<div class='col-12'><p>Không có sách nào trong danh mục này.</p></div>";
                    }

                    echo '</div>';
                    echo '</section>';

                    $stmt->close();
                } else {
                    echo "Lỗi prepare statement: " . $mysqli->error;
                }
            }
        } else {
            echo "Không có category nào.";
        }

        $mysqli->close();
        ?>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="javascript/Trang_chủ.js"></script>
</body>
</html>