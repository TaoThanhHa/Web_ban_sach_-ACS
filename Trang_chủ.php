<?php
include_once('db/connect.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping-cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Trang_chu.css" type="text/css">
    <link rel="stylesheet" href="css/header.css" type="text/css">
    

</head>
<style>
.card {
    height: 500px;
}
img.card-img-top {
    height: 350px;
}

.card-title {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    overflow: hidden;
    -webkit-line-clamp: 2; 
    text-overflow: ellipsis;
    line-clamp: 2; 
}
</style>
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

    <!-- header -->
    <?php include 'header.php'; ?>

    <!--Slidershow-->
    <section id="slider">
        <div class="aspect-ratio-169">
            <?php foreach ($slides as $slide): ?>
                <img class="slide" src="images/<?php echo htmlspecialchars($slide); ?>" alt="Slider Image">
            <?php endforeach; ?>
        </div>
    </section>

    <!-- content -->
    <div class="container mt-5">
        <?php
        // Lấy danh sách các category
        $sql_category = "SELECT * FROM tbl_category ORDER BY category_id DESC";
        $spl_category = $mysqli->query($sql_category);

        if ($spl_category) {
            while ($category = $spl_category->fetch_assoc()) {
                $category_id = $category['category_id'];
                $category_name = $category['category_name'];

                // Thiết lập số lượng sách hiển thị trên mỗi trang cho mỗi category
                $books_per_page = 12;
                $current_page = isset($_GET['page'][$category_id]) ? (int)$_GET['page'][$category_id] : 1;
                $offset = ($current_page - 1) * $books_per_page;

                // Truy vấn để lấy sách theo category
                $query = "SELECT * FROM tbl_book WHERE book_category = ? LIMIT ? OFFSET ?";
                $stmt = $mysqli->prepare($query);

                if ($stmt) {
                    $stmt->bind_param("iii", $category_id, $books_per_page, $offset);
                    $stmt->execute();
                    $results = $stmt->get_result();

                    // Truy vấn để đếm tổng số sách trong category
                    $count_query = "SELECT COUNT(*) AS total FROM tbl_book WHERE book_category = ?";
                    $count_stmt = $mysqli->prepare($count_query);
                    $count_stmt->bind_param("i", $category_id);
                    $count_stmt->execute();
                    $count_result = $count_stmt->get_result();
                    $total_books = $count_result->fetch_assoc()['total'];
                    $count_stmt->close();

                    // Tính toán số trang cho category
                    $total_pages = ceil($total_books / $books_per_page);

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
                            echo '<a href="Chi_tiet_san_pham.php?book_id=' . $row['book_id'] . '">';
                            echo '<img src="images/' . htmlspecialchars($row['book_image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['book_title']) . '">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . htmlspecialchars($row['book_title']) . '</h5>';
                            echo '<p class="card-text">';
                            echo '<span class="font-weight-bold">' . number_format($price, 0, ',', '.') . 'đ</span><br>';
                            echo '<del class="text-muted">' . number_format($row['book_original_price'], 0, ',', '.') . 'đ</del> ';
                            echo '<span class="text-danger">-' . htmlspecialchars($row['book_discount']) . '%</span>';
                            echo '</p>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<div class='col-12'><p>Không có sách nào trong danh mục này.</p></div>";
                    }

                    echo '</div>';
                    echo '</section>';

                    // Pagination
                    echo '<div class="box_pagination">';
                    echo '<nav aria-label="Page navigation">';
                    echo '<ul class="pagination justify-content-center">';
                    echo '<li class="page-item ' . ($current_page <= 1 ? 'disabled' : '') . '">';
                    echo '<a class="page-link" href="?page[' . $category_id . ']=' . ($current_page - 1) . '" aria-label="Previous">';
                    echo '<span aria-hidden="true">«</span>';
                    echo '</a>';
                    echo '</li>';

                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $start_page + 4);

                    for ($i = $start_page; $i <= $end_page; $i++) {
                        $active = ($i == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page[' . $category_id . ']=' . $i . '">' . $i . '</a></li>';
                    }

                    echo '<li class="page-item ' . ($current_page >= $total_pages ? 'disabled' : '') . '">';
                    echo '<a class="page-link" href="?page[' . $category_id . ']=' . ($current_page + 1) . '" aria-label="Next">';
                    echo '<span aria-hidden="true">»</span>';
                    echo '</a>';
                    echo '</li>';
                    echo '</ul>';
                    echo '</nav>';
                    echo '</div>';

                    $results->close();
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

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="javascript/Trang_chủ.js"></script>
</body>
</html>