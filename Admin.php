<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/Admin.css">
</head>

<body>
    <header>
        <div class="header-content">
            <div class="header-left">
                <img src="images/book_haven.jpg" alt="Logo" class="logo">
            </div>
            <div class="header-right">
                <h1>Trang Quản Trị</h1>
                <a href="ĐN.php"><button id="logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button></a>
            </div>
        </div>
    </header>    

    <div class="admin-container">
        <!-- Sidebar -->
        <nav class="sidebar">
            <ul>
                <li><a href="Quản_lý_sách.php"><i class="fas fa-book"></i> Quản lý sách</a></li>
                <li><a href="Quản_lý_người_dùng.php"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
                <li><a href="QLTD.html"><i class="fas fa-box"></i> Quản lý đơn hàng</a></li>
                <li><a href="Hỗ_trợ.php"><i class="fas fa-headset"></i> Hỗ trợ khách hàng</a></li>
            </ul>
        </nav>

        <!-- Main content -->
        <div class="main-content">
            <section id="manage-books">
                <h2>Quản lý sách</h2>
                <p>Danh sách sách và các chức năng liên quan.</p>
            </section>
            <section id="manage-users">
                <h2>Quản lý người dùng</h2>
                <p>Danh sách người dùng và các chức năng quản lý người dùng.</p>
            </section>
            <section id="manage-orders">
                <h2>Quản lý đơn hàng</h2>
                <p>Danh sách đơn hàng và các chức năng liên quan.</p>
            </section>
            <section id="manage-support">
                <h2>Hỗ trợ khách hàng</h2>
                <p>Quản lý các yêu cầu hỗ trợ từ khách hàng.</p>
            </section>
        </div>
    </div>

    <script src="javascript/Admin.js"></script>
</body>

</html>