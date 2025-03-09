<?php
include_once('db/connect.php');
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Liên_hệ.css" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title><?php echo isset($book['book_title']) ? htmlspecialchars($book['book_title']) : 'Chi tiết sách'; ?></title>
       
</head>
<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <a class="navbar-brand" href="index.php">
                <img src="images/book_haven.jpg" width="50" height="50" class="d-inline-block align-top" alt="Book Haven">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="Trang_chủ.php">Trang Chủ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Sản Phẩm
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                            if ($spl_category) {
                                mysqli_data_seek($spl_category, 0);
                                while ($row_category = $spl_category->fetch_assoc()): ?>
                                    <a class="dropdown-item" href="./Phân_loại.php"><?php echo htmlspecialchars($row_category['category_name']); ?></a>
                                <?php endwhile;
                            } else {
                                echo "Không có category nào.";
                            }
                            ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Liên_hệ.html">Liên Hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Giới_thiệu.php">Giới Thiệu</a>
                    </li>
                </ul>
                <form class="form-inline ml-auto" method="post" action="">
                    <input class="form-control mr-sm-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
            <button id="cart" class="btn btn-danger ml-2">
                <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                Giỏ Hàng
            </button>
            <ul class="navbar-nav ml-2">
                <li class="nav-item">
                    <a class="nav-link" href="./Tai_khoan_khach.html">Tài khoản</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- content -->    
    <section class="wrapper_gt">
        <div class="box_gt">
            <ul>
                <li>
                    <h1>Giới thiệu</h1>
                    <p>Dự án phần mềm website BookHaven sẽ mang đến trải nghiệm mua sắm sách cá nhân hóa và tiện lợi, với giao diện tối giản và thân thiện. Khách hàng có thể dễ dàng tìm kiếm sách qua thanh tìm kiếm thông minh. Các tính năng như giỏ hàng, thanh toán đa dạng, theo dõi đơn hàng sẽ giúp quá trình mua sắm mượt mà và nhận hỗ trợ trực tuyến khi cần thiết.</p>
                </li>
                <li>
                    <h2>Sản phẩm và dịch vụ</h2>
                    <p>Xuất bản, phát hành sách và các ấn phẩm văn hóa.</p>

                </li>
                <li>
                    <h2>Tầm nhìn</h2>
                    <p>Trở thành đơn vị xuất bản chất lượng tại Việt Nam và đối tác tin cậy của các Nhà xuất bản trên thế giới.</p>
                </li>
                <li>
                    <h2>Sứ mệnh</h2>
                    <p>Xuất bản các tác phẩm giá trị với chất lượng cao nhằm góp phần đáp ứng nhu cầu hưởng thụ văn hóa ngày càng cao của độc giả cả nước, góp phần xây dựng và phát triển một nền văn hóa đọc lành mạnh, phong phú và tiên tiến.</p>
                </li>
                <li>
                    <h1>Giá trị cốt lõi</h1>
                    <p>Xây dựng, phát triển mô hình kinh doanh bền vững trên nền tảng đảm bảo phục vụ tốt nhất các quyền lợi của khách hàng, nhân viên và các cổ đông.
                    </p>
                </li>
            </ul>
                
               
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
                    <li>Facebook: </li>
                </ul></li>
            </ul>
        </div>
    </footer>
    <script>
        document.getElementById('cart').addEventListener('click', function() {
            window.location.href = 'Giỏ_hàng.php';
        });
    </script>
</body>

</html>