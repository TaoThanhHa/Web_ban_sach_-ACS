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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/QLS.css">
  <style>
      .book {
          display: flex;
          justify-content: space-between;
          align-items: center;
          padding: 10px;
          border: 1px solid #ccc;
          margin-bottom: 5px;
      }
      .book-left {
          display: flex;
          align-items: center;
      }
      .book-left img {
          width: 50px;
          height: auto;
          margin-right: 10px;
      }
      .book-right button {
          margin-left: 5px;
      }
  </style>
</head>
<body>
    <div class="container">
        <h1>Quản lý sách</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="post" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sách...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <a href="Tao_san_pham.php" class="btn btn-success mb-3">Thêm sách</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($results && $results->num_rows > 0) {
                    while($row = $results->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><img src="images/' . htmlspecialchars($row["book_image"]) . '" alt="' . htmlspecialchars($row["book_title"]) . '" style="width: 50px;"></td>';
                        echo '<td>' . htmlspecialchars($row["book_title"]) . '</td>';
                        echo '<td>';
                        echo '    <form method="post" action="Xử_lý_sách.php" style="display:inline;">';
                        echo '      <input type="hidden" name="book_id" value="' . htmlspecialchars($row["book_id"]) . '">';
                        echo '      <input type="hidden" name="action" value="delete">';
                        echo '      <button type="submit" class="btn btn-danger btn-sm">Xóa</button>';
                        echo '    </form>';
                        echo '    <a href="Sửa_sách.php?book_id=' . htmlspecialchars($row["book_id"]) . '" class="btn btn-primary btn-sm">Sửa</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">Không có sách nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

  <?php
  // Đóng kết nối
  $mysqli->close();
  ?>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>