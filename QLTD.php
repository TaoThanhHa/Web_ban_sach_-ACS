<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/QLTD.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
</head>
<body>
    <?php include 'header_admin.php'; ?>
    <div class="container">
        <h1>Quản lý đơn hàng</h1>
        <div class="search-bar">
            <input type="text" id="order-search" placeholder="Nhập mã đơn hàng...">
            <button onclick="searchOrder()"><i class="fas fa-search"></i> Tìm kiếm</button>
        </div>
        <div id="order-list">
            <!-- Danh sách đơn hàng sẽ được hiển thị ở đây -->
        </div>
        <div id="order-details" class="hidden">
            <h2>Chi tiết đơn hàng</h2>
            <div class="order-info">
                <!-- Thông tin đơn hàng -->
            </div>
            <div class="order-items">
                <h3>Sản phẩm</h3>
                <ul>
                    <!-- Danh sách sản phẩm trong đơn hàng -->
                </ul>
            </div>
            <div class="order-actions">
                <!-- Các nút thao tác (ví dụ: Cập nhật trạng thái, Hủy đơn hàng) -->
            </div>
        </div>
    </div>

    <script src="javascript/QLTD.js"></script>
</body>
</html>