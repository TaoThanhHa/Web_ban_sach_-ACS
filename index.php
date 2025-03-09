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
    <link rel="stylesheet" href="css/Trang_chu.css" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

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
        // Truy vấn để lấy category
        $spl_category = mysqli_query($mysqli, 'SELECT * FROM tbl_category ORDER BY category_id DESC');
            
        // Truy vấn để lấy slider
        $sql = "SELECT slider_image FROM tbl_slider WHERE slider_active = 1";
        $result = $mysqli->query($sql);
        $slides = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $slides[] = $row['slider_image'];
            }
        }

        // Thiết lập số lượng sách hiển thị trên mỗi trang
        $books_per_page = 12;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $books_per_page;

        // Phần tìm kiếm
        $search_keyword = isset($_POST['search']) ? $_POST['search'] : '';
        $query = "SELECT * FROM tbl_book";
        
        if (!empty($search_keyword)) {
            $query .= " WHERE book_title LIKE ?";
        }
        
        $stmt = $mysqli->prepare($query);
        
        if (!empty($search_keyword)) {
            $search_param = "%" . $search_keyword . "%";
            $stmt->bind_param("s", $search_param);
        }
        
        $stmt->execute();
        $results = $stmt->get_result();
        $total_books = $results->num_rows;

        // Tính toán số trang
        $total_pages = ceil($total_books / $books_per_page);

        // Lấy dữ liệu cho trang hiện tại
        $stmt->close();
        
        if (!empty($search_keyword)) {
            $query .= " LIMIT ? OFFSET ?";
            $stmt = $mysqli->prepare($query);
            if ($stmt) {
                $stmt->bind_param("sii", $search_param, $books_per_page, $offset);
            }
        } else {
            $query .= " LIMIT ? OFFSET ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ii", $books_per_page, $offset);
        }
        
        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        $mysqli->close();
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
        <section class="wrapper">
            <h2>Truyện tranh</h2>
            <div class="row">
                <?php
                    if ($results->num_rows > 0) {
                        while ($row = $results->fetch_assoc()) {
                            $original_price = $row['book_original_price'];
                            $discount = $row['book_discount'];
                            $price = $original_price * (1 - $discount / 100);

                            echo '<div class="col-md-3 mb-4">';
                            echo '<div class="card">';
                            echo '<a href="ĐN.php">';
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
                        echo "<div class='col-12'><p>Không có sách nào trong cơ sở dữ liệu.</p></div>";
                    }
                ?>
            </div>
        </section>

        <!-- Pagination -->
        <div class="box_pagination">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo ($current_page - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>

                    <?php
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $start_page + 4);

                    for ($i = $start_page; $i <= $end_page; $i++) {
                        $active = ($i == $current_page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }
                    ?>

                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo ($current_page + 1); ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- footer -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="./javascript/index.js"></script>
</body>
</html>
