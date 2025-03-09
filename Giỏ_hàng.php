<?php
include_once('db/connect.php');
?>
<?php
session_start();

// Kiểm tra nếu giỏ hàng không tồn tại
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Cập nhật giỏ hàng khi có yêu cầu từ client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $book_id = $_POST['book_id'];
        $quantity = $_POST['quantity'];
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$book_id]);
        } else {
            $_SESSION['cart'][$book_id]['quantity'] = $quantity;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $book_id = $_POST['book_id'];
        unset($_SESSION['cart'][$book_id]);
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
    <link rel="stylesheet" href="css/Giỏ_hàng.css" type="text/css">
    <link rel="stylesheet" href="css//header.css" type="text/css">
    <title>Giỏ Hàng</title>
    <script>
        function updateTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#cart-items tr');
            rows.forEach(row => {
                const quantityInput = row.querySelector('input[type="number"]');
                const priceCell = row.querySelector('.price');
                const unitPrice = parseFloat(priceCell.dataset.price);
                const quantity = quantityInput.value;
                const itemTotal = unitPrice * quantity;
                priceCell.innerText = numberWithCommas(itemTotal.toFixed(0)) + '₫';
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
                        <th>Giá tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <?php
                    $total_price = 0;
                    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $book_id => $item) {
                            $item_total = $item['price'] * $item['quantity'];
                            $total_price += $item_total;
                            echo '<tr data-book-id="' . htmlspecialchars($book_id) . '">';
                            echo '<td><img src="images/' . htmlspecialchars($item['image']) . '" alt=""></td>';
                            echo '<td>' . htmlspecialchars($item['title']) . '</td>';
                            echo '<td><input type="number" value="' . $item['quantity'] . '" min="1" onchange="updateQuantity(this)"></td>';
                            echo '<td class="price" data-price="' . htmlspecialchars($item['price']) . '">' . number_format($item['price'], 0, ',', '.') . '₫</td>';
                            echo '<td><button class="button" onclick="removeItem(this)">Xóa</button></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo "<tr><td colspan='5'>Giỏ hàng trống.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="total">
                Tổng cộng: <span id="total-price"><?php echo number_format($total_price, 0, ',', '.'); ?>₫</span>
            </div>


            <div class="note">
                <label for="note">Ghi chú:</label>
                <textarea id="note" rows="4" style="width: 100%;"></textarea>
            </div>

            <button class="button" onclick="checkout()">Thanh toán</button>
        </div>
    </div>
    
</body>
</html>