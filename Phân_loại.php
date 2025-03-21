<?php
include_once('db/connect.php');
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping-cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Phân_loại.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
</head>

<body>
    <?php
        // Truy vấn để lấy category
        $spl_category = mysqli_query($mysqli, 'SELECT * FROM tbl_category ORDER BY category_id DESC');
        $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

        // Thiết lập số lượng sách hiển thị trên mỗi trang
        $books_per_page = 12;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $books_per_page;

        // Truy vấn để lấy category_name
        $category_name = '';
        if($category_id > 0){
            $sql_category_name = "SELECT category_name FROM tbl_category WHERE category_id = $category_id";
            $query_category_name = mysqli_query($mysqli,$sql_category_name);
            $row_category_name = mysqli_fetch_array($query_category_name);
            $category_name = $row_category_name['category_name'];
        }

        // Phần tìm kiếm
        $search_keyword = isset($_POST['search']) ? $_POST['search'] : '';
        $query = "SELECT * FROM tbl_book WHERE 1=1";

        if ($category_id > 0) {
            $query .= " AND book_category = $category_id"; //sửa category_id thành book_category
        }

        if (!empty($search_keyword)) {
            $query .= " AND book_title LIKE ?";
        }

        $query .= " LIMIT $books_per_page OFFSET $offset";
        $results = $mysqli->query($query);

      

    ?>

    <!-- header -->
    <?php include 'header.php'; ?>

    <section class="cartegory">
        <div class="container">
            <div class="cartegory-top row">
                <p>Trang chủ <span>→</span> <p>Thể loại </p><span>→</span><p><?php echo $category_name ?></p></p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                 <div class="cartegory-left">
                    <ul>
                         <?php
                            if ($spl_category) {
                                mysqli_data_seek($spl_category, 0);
                                while ($row_category = $spl_category->fetch_assoc()): ?>
                                    <li class="cartegory-left-li"><a href="Phân_loại.php?category_id=<?php echo $row_category['category_id']; ?>"><?php echo htmlspecialchars($row_category['category_name']); ?></a></li>
                                <?php endwhile;
                            } else {
                                echo "Không có category nào.";
                            }
                            ?>
                    </ul>
                </div>

                <div class="cartegory-right row">
                <section class="wrapper">
                    <div class="box">
                        <?php
                            if ($results->num_rows > 0) {
                                while ($row = $results->fetch_assoc()) {
                                    $original_price = $row['book_original_price'];
                                    $discount = $row['book_discount'];
                                    $price = $original_price * (1 - $discount / 100);

                                    echo '<div class="card">';
                                    echo '<a href="Chi_tiet_san_pham.php?book_id=' . $row['book_id'] . '">';
                                    echo '<img src="images/' . htmlspecialchars($row['book_image']) . '" alt="' . htmlspecialchars($row['book_title']) . '">';
                                    echo '<div class="discount">' . htmlspecialchars($row['book_discount']) . '%</div>';
                                    echo '<div class="title">' . htmlspecialchars($row['book_title']) . '</div>';
                                    echo '<div class="price">' . number_format($price, 0, ',', '.') . 'đ</div>';
                                    echo '<div class="original-price">' . number_format($row['book_original_price'], 0, ',', '.') . 'đ</div>';
                                    echo '</a>';
                                    echo '</div>';
                                }
                            } else {
                                echo "<p>Không có sách nào trong cơ sở dữ liệu.</p>";
                            }
                        ?>
                    </div>
                </section>
                   <!--   <div class="box_pagination">
                            <div class="pagination">
                                <a href="?page=1">«</a> 
                                <?php
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $start_page + 4);

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active = ($i == $current_page) ? 'active' : '';
                                    echo '<a href="?page=' . $i . '" class="' . $active . '">' . $i . '</a>';
                                }
                                ?>
                                <a href="?page=<?php echo $total_pages; ?>">»</a> 
                            </div>
                        </div> -->
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
 $mysqli->close();
?>