<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    unset($_SESSION['cart']);
    echo "window.location.href = 'index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Thanh_toán.css">
    <title>Thông tin giao hàng</title>
</head>
<body>
    <header>
        <div class="header">
            <a href="Giỏ_hàng.php">&lt;</a>
        </div>
    </header>

    <div class="container">
    <h1>Thông tin giao hàng</h1>
    <form id="order-form">
        <label for="name">Họ và tên:</label>
        <input type="text" id="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" required>
        <div class="error" id="email-error" style="display: none; color: red;">Email không hợp lệ.</div>

        <label for="address">Địa chỉ:</label>
        <input type="text" id="address" required>

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" required>
        <div class="error" id="phone-error"; style="display: none; color: red;">Số điện thoại không hợp lệ</div>

        <label for="payment-method">Phương thức thanh toán:</label>
        <select id="payment-method" required>
            <option value="">Chọn phương thức</option>
            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
            <option value="online">Thanh toán trực tiếp</option>
            <option value="transfer">Chuyển khoản</option>
        </select>

        <div class="shipping-method" id="shipping-method-container">
            <label for="delivery-method">Phương thức vận chuyển:</label>
            <select id="delivery-method" required>
                <option value="">Chọn phương thức</option>
                <option value="standard">Giao hàng tiêu chuẩn</option>
                <option value="express">Giao hàng nhanh</option>
            </select>
        </div>

        <div id="transfer-details" style="display: none;">
            <label for="transfer-note">Thông tin chuyển khoản<br> Vietcombank <br>1234567890 <br> Nguyễn Văn A</label>

            <label for="transfer-receipt">Tải ảnh biên lai chuyển khoản:</label>
            <input type="file" id="transfer-receipt" accept="image/*">
        </div>

            <div class="product-list">
                <h3>Sản phẩm đã chọn:</h3>
                <?php
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $book_id => $item) {
                        echo '<div class="product">';
                        echo '<span><img src="images/' . htmlspecialchars($item['image']) . '" alt=""></span>';
                        echo '<span>' . htmlspecialchars($item['title']) . '</span>';
                        echo '<span>' . number_format($item['price'] * $item['quantity'], 0, ',', '.') . '₫</span>';
                        echo '</div>';
                    }
                }
                ?>
            </div>

            <div class="total">
                Tổng cộng: <span id="total-amount"><?php echo number_format(array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $_SESSION['cart'])), 0, ',', '.'); ?>₫</span>
            </div>

            <button type="submit" class="button">Hoàn tất đơn hàng</button>
        </form>
    </div>

    <script>
        const paymentMethodSelect = document.getElementById('payment-method');
        const shippingMethodContainer = document.getElementById('shipping-method-container');
        const transferDetails = document.getElementById('transfer-details');
        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('email-error');

    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'online') {
            shippingMethodContainer.style.display = 'none';
            transferDetails.style.display = 'none';
        } else if (this.value === 'transfer') {
            shippingMethodContainer.style.display = 'block';
            transferDetails.style.display = 'block';
        } else {
            shippingMethodContainer.style.display = 'block';
            transferDetails.style.display = 'none';
        }
    });

    document.getElementById('order-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');
        const phoneValue = phoneInput.value;

        // Kiểm tra số điện thoại
        const phoneRegex = /^0\d{9}$/;
        if (!phoneRegex.test(phoneValue)) {
            phoneError.style.display = 'block';
            return;
        } else {
            phoneError.style.display = 'none';
        }

        // Kiểm tra email
        const emailValue = emailInput.value;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!emailRegex.test(emailValue)) {
            emailError.style.display = 'block';
            return;
        } else {
            emailError.style.display = 'none';
        }

        alert('Đơn hàng của bạn đã được hoàn tất!');
    });
</script>
</body>
</html>
