<?php
include_once('db/connect.php');
?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="Trang_chủ.php">
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
                <li class="nav-item">
                    <?php
                        $sql_category_first = "SELECT category_id FROM tbl_category WHERE category_name = 'Linh dị'";
                        $query_category_first = mysqli_query($mysqli,$sql_category_first);
                        $row_category_first = mysqli_fetch_array($query_category_first);
                        $category_id_first = $row_category_first['category_id'];
                    ?>
                    <a class="nav-link" href="Phân_loại.php?category_id=<?php echo $category_id_first ?>">Thể loại</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./Liên_hệ.html">Liên Hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Giới_thiệu.php">Giới Thiệu</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="Tìm_kiếm.php" method="GET" style="text-align: right">
                <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="keyword">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>
        </div>
        <button id="cart" class="btn btn-danger ml-2">
            <i class="fa fa-shopping-basket" aria-hidden="true"></i>
            Giỏ Hàng
        </button>
        <ul class="navbar-nav ml-2">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Tài khoản
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="ĐX.php">Đăng xuất</a>
                    <a class="dropdown-item" href="Theo_dõi.html">Theo dõi đơn hàng</a> 
                </div>
            </li>
        </ul>
    </nav>
</header>