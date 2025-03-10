<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hỗ trợ khách hàng</title>
    <link rel="stylesheet" href="css/Hỗ_trợ.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="javascript/Hỗ_trợ.js" defer></script>
</head>
<body>
    <!-- header -->
    <div class="header">
        <a href="Admin.html"><</a>
    </div>

    <div class="container">
        <h1>Hỗ trợ khách hàng</h1>

        <h2>Danh sách yêu cầu hỗ trợ</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Nội dung</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include_once('db/connect.php');

                // Truy vấn dữ liệu
                $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
                $result = $mysqli->query($sql); // Sử dụng $mysqli

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["comment"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                        echo "<td>";
                        // Thêm liên kết hoặc nút để trả lời
                        echo "<a href='reply_contact.php?id=" . $row["id"] . "'>Trả lời</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có yêu cầu hỗ trợ nào.</td></tr>";
                }
                $mysqli->close(); // Sử dụng $mysqli
                ?>
            </tbody>
        </table>
    </div>
      <footer>
        <div>
            <ul class="end">
                <li>
                    <ul>
                        <img src="images/Book Haven (2).png" width="130px" height="130px">
                    </ul>
                </li>
                <li><ul>
                    <li class="tieu_de">Dịch vụ</li>
                    <li><a href="">Điều khoản sử dụng</a></li>
                    <li><a href="">Liên hệ</a></li>
                    <li><a href="">Hệ thống nhà sách</a></li>
                </ul></li>
    
                <li><ul>
                    <li class="tieu_de">Hỗ trợ</li>
                    <li><a href="">Chính sách đổi trả - hoàn tiền</a></li>
                    <li><a href="">Phương thức vận chuyển</a></li>
                    <li><a href="">Phương thức thanh toán</a></li>
                </ul></li>
    
                <li><ul>
                    <li class="tieu_de">Nhà sách bán lẻ</li>
                    <li>Giám đốc: Tào Thanh Hà | Mai Phương Anh</li>
                    <li>Địa chỉ: Đại học Phenikaa</li>
                    <li>Số điện thoại: </li>
                    <li>Email: </li>
                    <li>Facebook: </li>
                </ul></li>
            </ul>
        </div>
    </footer>
</body>
</html>