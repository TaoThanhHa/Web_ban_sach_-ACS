<?php
include_once('db/connect.php');
?>

<!DOCTYPE html>
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

        // Truy vấn để lấy category_name
        $category_name = '';
        if($category_id > 0){
            $sql_category_name = "SELECT category_name FROM tbl_category WHERE category_id = $category_id";
            $query_category_name = mysqli_query($mysqli,$sql_category_name);
            $row_category_name = mysqli_fetch_array($query_category_name);
            $category_name = $row_category_name['category_name'];
        }

        // Phần tìm kiếm
        $search_keyword = isset($_POST['search']) ? $mysqli->real_escape_string($_POST['search']) : '';

        // Truy vấn chính (Bỏ LIMIT và OFFSET)
        $query = "SELECT * FROM tbl_book WHERE 1=1";

        if ($category_id > 0) {
            $query .= " AND book_category = $category_id"; 
        }

        if (!empty($search_keyword)) {
            $query .= " AND book_title LIKE '%$search_keyword%'";
        }

        $query .= " ORDER BY book_id DESC"; //Sắp xếp theo ID giảm dần

        $results = $mysqli->query($query);
    ?>

    <!-- header -->
    <?php include 'header.php'; ?>

    <section class="cartegory">
        <div class="container">
            <div class="cartegory-top row">
                <p>Trang chủ <span>→</span> Thể loại <span>→</span> <?php echo htmlspecialchars($category_name); ?></p>
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
                                <li class="cartegory-left-li <?php if ($category_id == $row_category['category_id']) echo 'active'; ?>">
                                    <a href="Phân_loại.php?category_id=<?php echo $row_category['category_id']; ?>">
                                        <?php echo htmlspecialchars($row_category['category_name']); ?>
                                    </a>
                                </li>
                            <?php endwhile;
                        } else {
                            echo "Không có category nào.";
                        }
                        ?>
                    </ul>
                </div>

                <div class="cartegory-right">
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
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <?php include 'footer.php'; ?>
    <script src="javascript/Phân_loại.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$mysqli->close();
?>