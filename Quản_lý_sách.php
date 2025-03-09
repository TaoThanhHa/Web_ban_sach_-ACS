<?php
include_once('db/connect.php');
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$results = null;

$search_keyword = isset($_POST['search']) ? $_POST['search'] : '';

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
    <form method="post" action="">
      <input class="tim-kiem" type="text" name="search" placeholder="Tìm kiếm sách...">
      <input type="submit" value="Tìm kiếm">
    </form>

    <a href="Tao_san_pham.html" id="add-book-btn">Thêm sách</a>

    <div id="book-list">
      <?php
      if ($results->num_rows > 0) {
          while($row = $results->fetch_assoc()) {
              echo '<div class="book row">';
              echo '  <div class="book-left row">';
              echo '    <div class="image"><img src="images/' . $row["book_image"] . '" alt=""></div>';
              echo '    <div class="book-title">' . $row["book_title"] . '</div>';
              echo '  </div>';
              echo '  <div class="book-right row">';
              echo '    <button class="button" onclick="deleteBook(' . $row["book_id"] . ')">Xóa</button>';
              echo '    <button class="button" onclick="editBook(' . $row["book_id"] . ', \'' . addslashes($row["book_title"]) . '\')">Sửa</button>';
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
  <script src="javascript/QLS.js"></script>
</body>
</html>
