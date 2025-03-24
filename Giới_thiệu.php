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
    <link rel="stylesheet" href="css/Liên_hệ.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <title><?php echo isset($book['book_title']) ? htmlspecialchars($book['book_title']) : 'Chi tiết sách'; ?></title>
       
</head>
<body>

    <!-- Header -->
    <?php include 'header.php'; ?>

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
    <?php include 'footer.php'; ?>
    
    <script>
        document.getElementById('cart').addEventListener('click', function() {
            window.location.href = 'Giỏ_hàng.php';
        });
    </script>
    <script src="javascript/Giới_thiệu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>