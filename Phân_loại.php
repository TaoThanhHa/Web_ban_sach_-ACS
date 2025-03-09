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
    <link rel="stylesheet" href="css/Phân_loại.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/reponsive.css" />
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

        // Phần tìm kiếm
        $search_keyword = isset($_POST['search']) ? $_POST['search'] : '';
        $query = "SELECT * FROM tbl_book WHERE 1=1";

        if ($category_id > 0) {
            $query .= " AND category_id = ?";
        }

        if (!empty($search_keyword)) {
            $query .= " AND book_title LIKE ?";
        }

        $stmt = $mysqli->prepare($query);

        if ($category_id > 0 && !empty($search_keyword)) {
            $search_param = "%" . $search_keyword . "%";
            $stmt->bind_param("is", $category_id, $search_param);
        } elseif ($category_id > 0) {
            $stmt->bind_param("i", $category_id);
        } elseif (!empty($search_keyword)) {
            $search_param = "%" . $search_keyword . "%";
            $stmt->bind_param("s", $search_param);
        }

        $stmt->execute();
        $results = $stmt->get_result();
        $total_books = $results->num_rows;

        $total_pages = ceil($total_books / $books_per_page);

        $stmt->close();
        
        $query .= " LIMIT ? OFFSET ?";
        $stmt = $mysqli->prepare($query);

        if ($category_id > 0 && !empty($search_keyword)) {
            $stmt->bind_param("isi", $category_id, $books_per_page, $offset);
        } elseif ($category_id > 0) {
            $stmt->bind_param("ii", $books_per_page, $offset);
        } elseif (!empty($search_keyword)) {
            $search_param = "%" . $search_keyword . "%";
            $stmt->bind_param("si", $search_param, $books_per_page, $offset);
        } else {
            $stmt->bind_param("ii", $books_per_page, $offset);
        }

        $stmt->execute();
        $results = $stmt->get_result();
        $stmt->close();

        $mysqli->close();
    ?>

    <!-- header -->
    <header>
        <nav>
            <div class="content-nav">
                <div class="img-nav">
                    <img src="images/book_haven.jpg" width="50px" height="50px" />
                </div>
                
                <ul>
                    <li><a href="index.php">Trang Chủ</a></li>
                    <li><a href="#">Sản Phẩm</a>
                        <ul>
                            <?php while($row_category = mysqli_fetch_array($spl_category)): ?>
                                <li><a href="Phân_loại.php?category_id=<?php echo $row_category['category_id']; ?>"><?php echo $row_category['category_name']; ?></a></li>
                            <?php endwhile; ?>
                        </ul>
                    </li>
                    <li><a href="">Liên Hệ</a></li>
                    <li><a href="Giới_thiệu.php">Giới Thiệu</a></li>
                </ul>
                <form method="post" action="">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." />
                    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
            <button id="cart">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                Giỏ Hàng
            </button>
            <ul class="login">
                <li><a href="">Đăng nhập</a></li>
                <li><a href="">Đăng ký</a></li>
            </ul>
        </nav>
    </header>

    <section class="cartegory">
        <div class="container">
            <div class="cartegory-top row">
                <p>Trang chủ <span>&#8594;</span> <p>Sản phẩm </p><span>&#8594;</span><p>Trinh thám</p></p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="cartegory-left">
                    <ul>
                        <li class="cartegory-left-li"><a href="">Linh dị</a></li>
                        <li class="cartegory-left-li"><a href="">Manga & Comic</a></li>
                        <li class="cartegory-left-li"><a href="">Light Novel</a></li>
                        <li class="cartegory-left-li"><a href="">Truyện tranh</a></li>
                        <li class="cartegory-left-li"><a href="">BoyLove & GirlLove</a></li>
                        <li class="cartegory-left-li"><a href="">Văn học kinh điển</a></li>
                        <li class="cartegory-left-li"><a href="">Ngôn tình</a></li>
                        <li class="cartegory-left-li"><a href="">Trình thám - Kinh dị</a></li>
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
                <div class="box_pagination">
                            <div class="pagination">
                                <a href="?page=1">&laquo;</a> 
                                <?php
                                $start_page = max(1, $current_page - 2);
                                $end_page = min($total_pages, $start_page + 4);

                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    $active = ($i == $current_page) ? 'active' : '';
                                    echo '<a href="?page=' . $i . '" class="' . $active . '">' . $i . '</a>';
                                }
                                ?>
                                <a href="?page=<?php echo $total_pages; ?>">&raquo;</a> 
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- footer -->
    <footer>
        <div>
            <ul class="end">
                <li>
                    <ul>
                        <img src="images/Book Haven (2).png" width="130px" height="130px">
                    </ul>
                </li>
                <li><ul>
                    <li class="tieu_de">Dịch vụ</li>
                    <li><a href="">Điều khoản sử dụng</a></li>
                    <li><a href="">Liên hệ</a></li>
                    <li><a href="">Hệ thống nhà sách</a></li>
                </ul></li>
                <li><ul>
                    <li class="tieu_de">Hỗ trợ</li>
                    <li><a href="">Chính sách đổi trả - hoàn tiền</a></li>
                    <li><a href="">Phương thức vận chuyển</a></li>
                    <li><a href="">Phương thức thanh toán</a></li>
                </ul></li>
                <li><ul>
                    <li class="tieu_de">Nhà sách bán lẻ</li>
                    <li>Giám đốc: Tào Thanh Hà | Mai Phương Anh</li>
                    <li>Địa chỉ: Đại học Phenikaa</li>
                    <li>Số điện thoại: </li>
                    <li>Email: </li>
                </ul></li>
            </ul>
        </div>
    </footer>
</body>
</html>
