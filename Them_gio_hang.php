<?php
session_start();

include_once('db/connect.php');

function db_error($mysqli) {
    echo "Lỗi database: " . $mysqli->error;
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>
                alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
                window.location.href = 'ĐN.php';
              </script>";
        exit();
    }

    $user_id = $_SESSION['user_id']; 

    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
    $book_title = isset($_POST['book_title']) ? $_POST['book_title'] : '';
    $book_image = isset($_POST['book_image']) ? $_POST['book_image'] : '';
    $book_price = isset($_POST['book_price']) ? (float)$_POST['book_price'] : 0.0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $query = "SELECT book_quantity FROM tbl_book WHERE book_id = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        db_error($mysqli);
    }

    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $available_quantity = (int)$row['book_quantity'];

        if (isset($_SESSION['cart'][$user_id][$book_id])) {
            $existing_quantity = (int)$_SESSION['cart'][$user_id][$book_id]['quantity'];
            $new_quantity = $existing_quantity + $quantity;

            // Kiểm tra tổng số lượng yêu cầu so với số lượng có sẵn
            if ($new_quantity > $available_quantity) {
                if ($available_quantity == $existing_quantity) {
                    // Hết hàng
                    echo "<script>
                        alert('Sản phẩm này đã hết hàng.');
                        window.location.href = 'Chi_tiet_san_pham.php?book_id=" . $book_id . "'; 
                      </script>";
                    exit();
                } else {
                    // Vượt quá số lượng có thể mua
                    echo "<script>
                        alert('Số lượng bạn chọn cộng với số lượng đã có trong giỏ hàng vượt quá số lượng còn lại trong kho. Bạn chỉ có thể mua tối đa " . ($available_quantity - $existing_quantity) . " sản phẩm này.');
                        window.location.href = 'Chi_tiet_san_pham.php?book_id=" . $book_id . "';
                      </script>";
                    exit();
                }
            } else {
                $update_query = "UPDATE tbl_book SET book_quantity = book_quantity - ? WHERE book_id = ?";
                $update_stmt = $mysqli->prepare($update_query);

                if (!$update_stmt) {
                    db_error($mysqli);
                }

                $update_stmt->bind_param("ii", $quantity, $book_id);
                $update_stmt->execute();
                $update_stmt->close();

                $_SESSION['cart'][$user_id][$book_id]['quantity'] = $new_quantity;

                echo "<script>
                    alert('Cập nhật giỏ hàng thành công!');
                    window.location.href = 'Giỏ_hàng.php'; 
                  </script>";
                exit();
            }
        } else {

            if ($quantity > $available_quantity) {
                 if ($available_quantity == 0) {
                    // Hết hàng
                    echo "<script>
                        alert('Sản phẩm này đã hết hàng.');
                        window.location.href = 'Chi_tiet_san_pham.php?book_id=" . $book_id . "'; 
                      </script>";
                    exit();
                } else {
                    // Vượt quá số lượng có thể mua
                    echo "<script>
                        alert('Số lượng bạn chọn vượt quá số lượng còn lại trong kho. Chỉ còn lại " . $available_quantity . " sản phẩm.');
                        window.location.href = 'Chi_tiet_san_pham.php?book_id=" . $book_id . "'; 
                      </script>";
                    exit();
                }

            } else {
                $update_query = "UPDATE tbl_book SET book_quantity = book_quantity - ? WHERE book_id = ?";
                $update_stmt = $mysqli->prepare($update_query);

                if (!$update_stmt) {
                    db_error($mysqli);
                }

                $update_stmt->bind_param("ii", $quantity, $book_id);
                $update_stmt->execute();
                $update_stmt->close();


                // Thêm sản phẩm vào giỏ hàng
                $item = array(
                    'book_id' => $book_id,
                    'title' => $book_title,
                    'image' => $book_image,
                    'price' => $book_price,
                    'quantity' => $quantity
                );
                $_SESSION['cart'][$user_id][$book_id] = $item;

                echo "<script>
                    alert('Thêm vào giỏ hàng thành công!');
                    window.location.href = 'Chi_tiet_san_pham.php?book_id=" . $book_id . "'; 
                  </script>";
                exit();
            }
        }
    } else {
        echo "<script>
                alert('Không tìm thấy sách.');
                window.location.href = 'index.php';
              </script>";
        exit();
    }

    $stmt->close();
} else {
    echo "<script>
            alert('Truy cập không hợp lệ.');
            window.location.href = 'index.php'; 
          </script>";
    exit();
}

$mysqli->close();
?>