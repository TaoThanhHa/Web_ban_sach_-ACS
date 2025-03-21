<?php
include_once('db/connect.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$results = null;

$search_keyword = isset($_POST['search']) ? $mysqli->real_escape_string($_POST['search']) : '';

// Nếu có từ khóa tìm kiếm, thực hiện truy vấn tìm kiếm
if (!empty($search_keyword)) {
    $query = "SELECT * FROM tbl_book WHERE book_title LIKE ?";
    $stmt = $mysqli->prepare($query);
    $search_param = "%" . $search_keyword . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $results = $stmt->get_result();
    $stmt->close();
} else {
    // Nếu không có từ khóa tìm kiếm, lấy toàn bộ dữ liệu
    $query = "SELECT * FROM tbl_book";
    $results = $mysqli->query($query);
}

// Kiểm tra xem có thông báo từ trang Xử_lý_sách.php không
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý sách</title>
  <link rel="stylesheet" href="css/QLS.css">
</head>
<body>
    <div class="header">
        <a href="Admin.html">Thoát</a>
    </div>

    <div class="container">
    <h1>Quản lý sách</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <input class="tim-kiem" type="text" name="search" placeholder="Tìm kiếm sách...">
      <input type="submit" value="Tìm kiếm">
    </form>

    <a href="Tao_san_pham.html" id="add-book-btn">Thêm sách</a>

    <div id="book-list">
      <?php
      if ($results && $results->num_rows > 0) {
          while($row = $results->fetch_assoc()) {
              echo '<div class="book">';
              echo '  <div class="book-left">';
              echo '    <img src="images/' . htmlspecialchars($row["book_image"]) . '" alt="' . htmlspecialchars($row["book_title"]) . '">';
              echo '    <div class="book-title">' . htmlspecialchars($row["book_title"]) . '</div>';
              echo '  </div>';
              echo '  <div class="book-right">';
              echo '    <form method="post" action="Xử_lý_sách.php">';
              echo '      <input type="hidden" name="book_id" value="' . htmlspecialchars($row["book_id"]) . '">';
              echo '      <input type="hidden" name="action" value="delete">';
              echo '      <button type="submit" class="button">Xóa</button>';
              echo '    </form>';
              echo '    <a href="Sửa_sách.php?book_id=' . htmlspecialchars($row["book_id"]) . '" class="button">Sửa</a>';
              echo '  </div>';
              echo '</div>';
          }
      } else {
          echo "Không có sách nào.";
      }
      ?>
    </div>

  </div>

  <?php
  // Đóng kết nối
  $mysqli->close();
  ?>
</body>
</html>