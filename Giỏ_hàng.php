<?php
include_once('db/connect.php');
session_start();

// Kiểm tra xem user đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Bạn cần đăng nhập để xem giỏ hàng.');
            window.location.href = 'Trang_chủ.php';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id']; // Lấy user ID từ session

// Kiểm tra nếu giỏ hàng của người dùng này không tồn tại
if (!isset($_SESSION['cart'][$user_id])) {
    $_SESSION['cart'][$user_id] = [];
}

// Cập nhật giỏ hàng khi có yêu cầu từ client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $book_id = $_POST['book_id'];
        $quantity = $_POST['quantity'];

        // Lấy số lượng cũ từ giỏ hàng (nếu có)
        $old_quantity = isset($_SESSION['cart'][$user_id][$book_id]['quantity']) ? $_SESSION['cart'][$user_id][$book_id]['quantity'] : 0;

        if ($quantity <= 0) {
            // Xóa sản phẩm khỏi giỏ hàng và hoàn trả số lượng
            unset($_SESSION['cart'][$user_id][$book_id]);
            // Cập nhật số lượng trong database (hoàn trả lại số lượng đã có)
            $quantity_diff = $old_quantity;
            $sql_update_quantity = "UPDATE tbl_book SET book_quantity = book_quantity + ? WHERE book_id = ?";
            $stmt_update_quantity = $mysqli->prepare($sql_update_quantity);
            $stmt_update_quantity->bind_param("ii", $quantity_diff, $book_id);

            if ($stmt_update_quantity->execute()) {
                 //echo "Số lượng sản phẩm đã được hoàn trả.";
            } else {
                echo "Lỗi khi hoàn trả số lượng: " . $stmt_update_quantity->error;
            }
            $stmt_update_quantity->close();

        } else {
            // Cập nhật số lượng trong giỏ hàng
            $_SESSION['cart'][$user_id][$book_id]['quantity'] = $quantity;

            // Tính toán sự khác biệt giữa số lượng cũ và số lượng mới
            $quantity_diff = $quantity - $old_quantity; // Số lượng mới - số lượng cũ

            // Cập nhật số lượng trong database
            //Lưu ý: Phải đảo ngược lại số lượng khi update
            $sql_update_quantity = "UPDATE tbl_book SET book_quantity = book_quantity - ? WHERE book_id = ?";
            $stmt_update_quantity = $mysqli->prepare($sql_update_quantity);
            $stmt_update_quantity->bind_param("ii", $quantity_diff, $book_id);

            if ($stmt_update_quantity->execute()) {
                //echo "Số lượng sản phẩm đã được cập nhật.";
            } else {
                echo "Lỗi khi cập nhật số lượng: " . $stmt_update_quantity->error;
            }
            $stmt_update_quantity->close();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $book_id = $_POST['book_id'];

        // Lấy số lượng cũ từ giỏ hàng trước khi xóa
        $old_quantity = isset($_SESSION['cart'][$user_id][$book_id]['quantity']) ? $_SESSION['cart'][$user_id][$book_id]['quantity'] : 0;

        unset($_SESSION['cart'][$user_id][$book_id]);

        // Hoàn trả số lượng vào database
        $quantity_diff = $old_quantity;
        $sql_update_quantity = "UPDATE tbl_book SET book_quantity = book_quantity + ? WHERE book_id = ?";
        $stmt_update_quantity = $mysqli->prepare($sql_update_quantity);
        $stmt_update_quantity->bind_param("ii", $quantity_diff, $book_id);
        if ($stmt_update_quantity->execute()) {
            //echo "Số lượng sản phẩm đã được hoàn trả.";
        } else {
            echo "Lỗi khi hoàn trả số lượng: " . $stmt_update_quantity->error;
        }
        $stmt_update_quantity->close();
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Giỏ_hàng.css?v=<?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>">
    <title>Giỏ Hàng</title>
    <script>
        function updateTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#cart-items tr');
            rows.forEach(row => {
                const quantityInput = row.querySelector('input[type="number"]');
                const unitPriceCell = row.querySelector('.unit-price');
                const priceCell = row.querySelector('.price');
                const unitPrice = parseFloat(unitPriceCell.dataset.price);
                const quantity = quantityInput.value;
                const itemTotal = unitPrice * quantity;
                priceCell.innerText = numberWithCommas(itemTotal.toFixed(0)) + '₫'; // Update cột "Thành tiền"
                total += itemTotal;
            });
            document.getElementById('total-price').innerText = numberWithCommas(total.toFixed(0)) + '₫';

        }

        function removeItem(button) {
            const row = button.closest('tr');
            const bookId = row.dataset.bookId;
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=remove&book_id=${bookId}`
            }).then(() => {
                row.parentNode.removeChild(row);
                updateTotal();
            });
        }

        function updateQuantity(input) {
            const row = input.closest('tr');
            const bookId = row.dataset.bookId;
            const quantity = input.value;
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&book_id=${bookId}&quantity=${quantity}`
            }).then(() => {
                updateTotal();
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        function checkout() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=checkout'
            }).then(() => {
                window.location.href = 'Thanh_toán.php';
            });
        }
    </script>
</head>
<body>
    <!-- header -->
    <?php include 'header.php'; ?>
    
    <div class="box_cart">
        <div class="box_cart_item">
            <h1>Giỏ Hàng</h1>
            <table>
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <?php
                    $total_price = 0;
                    if (isset($_SESSION['cart'][$user_id]) && !empty($_SESSION['cart'][$user_id])) {
                        foreach ($_SESSION['cart'][$user_id] as $book_id => $item) {
                            $item_total = $item['price'] * $item['quantity'];
                            $total_price += $item_total;
                            echo '<tr data-book-id="' . htmlspecialchars($book_id) . '">';
                            echo '<td><img src="images/' . htmlspecialchars($item['image']) . '" alt=""></td>';
                            echo '<td>' . htmlspecialchars($item['title']) . '</td>';
                            echo '<td><input type="number" value="' . $item['quantity'] . '" min="1" onchange="updateQuantity(this)"></td>';
                            echo '<td class="unit-price" data-price="' . htmlspecialchars($item['price']) . '">' . number_format($item['price'], 0, ',', '.') . '₫</td>';
                            echo '<td class="price" data-price="' . htmlspecialchars($item['price']) . '">' . number_format($item_total, 0, ',', '.') . '₫</td>';
                            echo '<td><button class="button" onclick="removeItem(this)">Xóa</button></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo "<tr><td colspan='6'>Giỏ hàng trống.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="total">
                Tổng cộng: <span id="total-price"><?php echo number_format($total_price, 0, ',', '.'); ?>₫</span>
            </div>

            <button class="button" onclick="checkout()">Thanh toán</button>
        </div>
    </div>
    <script src="javascript/Giỏ_hàng.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
</html>