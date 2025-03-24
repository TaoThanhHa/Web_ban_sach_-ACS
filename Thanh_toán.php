<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

// Kiểm tra xem user đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Bạn cần đăng nhập để thanh toán.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

include_once('db/connect.php');

// Lấy thông tin người dùng từ database
$sql_user = "SELECT name, email FROM tbl_user WHERE id = ?";
$stmt_user = $mysqli->prepare($sql_user);

if ($stmt_user === false) {
    die("Lỗi prepare (tbl_user): " . $mysqli->error);
}

$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_name = htmlspecialchars($user_data['name']);
    $user_email = htmlspecialchars($user_data['email']);
} else {
    echo "<script>alert('Không tìm thấy thông tin người dùng.'); window.location.href = 'Trang_chủ.php';</script>";
    exit();
}

$stmt_user->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $name = $user_name; // Lấy từ thông tin người dùng
    $email = $user_email; // Lấy từ thông tin người dùng
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment-method'];
    $shipping_method = $_POST['delivery-method'];

    // Tính tổng tiền và phí vận chuyển
    $total_amount = 0;
    $shipping_fee = ($shipping_method === 'express') ? 50000 : 30000;

    if (isset($_SESSION['cart'][$user_id])) {
        foreach ($_SESSION['cart'][$user_id] as $item) {
            $price = isset($item['price']) ? (float)$item['price'] : 0.0;
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $total_amount += $price * $quantity;
        }
    }

    // Bắt đầu transaction
    $mysqli->begin_transaction();

    try {
        // 1. Lưu thông tin vào bảng tbl_order
        $order_date = date('Y-m-d H:i:s');
        $sql_order = "INSERT INTO tbl_order (id_user, order_date, shipping_address, payment_method, shipping_method, shipping_fee, total_amount, order_status)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_order = $mysqli->prepare($sql_order);

        if ($stmt_order === false) {
            throw new Exception("Lỗi prepare (tbl_order): " . $mysqli->error);
        }

        $initial_status = 'Đang xử lý';
        $stmt_order->bind_param("isssssds", $user_id, $order_date, $address, $payment_method, $shipping_method, $shipping_fee, $total_amount, $initial_status);

        if (!$stmt_order->execute()) {
            throw new Exception("Lỗi execute (tbl_order): " . $stmt_order->error);
        }

        $id_order = $mysqli->insert_id;
        $stmt_order->close();

        // 2. Lưu thông tin vào bảng tbl_order_item
        if (isset($_SESSION['cart'][$user_id])) {
            foreach ($_SESSION['cart'][$user_id] as $book_id => $item) {
                $price = isset($item['price']) ? (float)$item['price'] : 0.0;
                $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;

                $sql_order_item = "INSERT INTO tbl_order_item (id_order, book_id, quantity, price)
                                  VALUES (?, ?, ?, ?)";
                $stmt_order_item = $mysqli->prepare($sql_order_item);

                if ($stmt_order_item === false) {
                    throw new Exception("Lỗi prepare (tbl_order_item): " . $mysqli->error);
                }

                $stmt_order_item->bind_param("iiid", $id_order, $book_id, $quantity, $price);

                if (!$stmt_order_item->execute()) {
                    throw new Exception("Lỗi execute (tbl_order_item): " . $stmt_order_item->error);
                }

                $stmt_order_item->close();
            }
        }

        // Commit transaction
        $mysqli->commit();

        // Xóa giỏ hàng
        unset($_SESSION['cart'][$user_id]);

        echo "<script>
                alert('Đơn hàng của bạn đã được hoàn tất!');
                window.location.href = 'Trang_chủ.php';
              </script>";
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        $mysqli->rollback();
        echo "<script>alert('Có lỗi xảy ra trong quá trình thanh toán: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/Thanh_toán.css?v=<?php echo time(); ?>">
    <title>Thông tin giao hàng</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h1>Thông tin giao hàng</h1>
        <form id="order-form" method="post">
            <label for="name">Họ và tên:</label>
            <input type="text" id="name" name="name" value="<?php echo $user_name; ?>" readonly required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_email; ?>" readonly required>


            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" required>
            <div class="error" id="phone-error"; style="display: none; color: red;">Số điện thoại không hợp lệ</div>

            <label for="payment-method">Phương thức thanh toán:</label>
            <select id="payment-method" name="payment-method" required>
                <option value="">Chọn phương thức</option>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="online">Thanh toán trực tiếp</option>
                <option value="transfer">Chuyển khoản</option>
            </select>

            <div class="shipping-method" id="shipping-method-container">
                <label for="delivery-method">Phương thức vận chuyển:</label>
                <select id="delivery-method" name="delivery-method" required>
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
                if (isset($_SESSION['cart'][$user_id]) && !empty($_SESSION['cart'][$user_id])) {
                    foreach ($_SESSION['cart'][$user_id] as $book_id => $item) {
                        $image = isset($item['image']) ? htmlspecialchars($item['image']) : '';
                        $title = isset($item['title']) ? htmlspecialchars($item['title']) : '';
                        $price = isset($item['price']) ? (float)$item['price'] : 0.0;
                        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;

                        echo '<div class="product">';
                        echo '<span><img src="images/' . $image . '" alt="' . $title . '"></span>';
                        echo '<span>' . $title . '</span>';
                        echo '<span>' . number_format($price * $quantity, 0, ',', '.') . '₫</span>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>Không có sản phẩm nào trong giỏ hàng.</p>";
                }
                ?>
            </div>

            <div class="total">
                Tổng cộng: <span id="total-amount"><?php
                $total_amount = 0;
                if (isset($_SESSION['cart'][$user_id])) {
                    foreach ($_SESSION['cart'][$user_id] as $item) {
                        $price = isset($item['price']) ? (float)$item['price'] : 0.0;
                        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                        $total_amount += $price * $quantity;
                    }
                }
                echo number_format($total_amount, 0, ',', '.');
                 ?>₫</span>
            </div>

            <button type="submit" class="button">Hoàn tất đơn hàng</button>
        </form>
    </div>

    <script>
        const paymentMethodSelect = document.getElementById('payment-method');
        const shippingMethodContainer = document.getElementById('shipping-method-container');
        const transferDetails = document.getElementById('transfer-details');
        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');


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
            let isValid = true;

            // Validate phone number
            const phoneValue = phoneInput.value.trim();
            const phoneRegex = /^0\d{9}$/; // Matches VN phone numbers, starts with 0 and has 10 digits
            if (!phoneRegex.test(phoneValue)) {
                phoneError.style.display = 'block';
                phoneError.textContent = 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại bắt đầu bằng 0 và có 10 chữ số.';
                isValid = false;
            } else {
                phoneError.style.display = 'none';
            }


             if (!isValid) {
                event.preventDefault(); // Prevent form submission
            }


        });
    </script>
    <script src="javascript/Thanh_toán.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>