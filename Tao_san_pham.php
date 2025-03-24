<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Tao_san_pham.css" type="text/css">
    <title>Truyện của tôi</title>
</head>
<body>
    <div class="header">
        <a href="./Admin.php"><</a>
        
    </div>

    <div class="body">
        <form class="container" id="story-form" action="db/Tao_san-pham.php" method="POST" enctype="multipart/form-data">
            <!--Ảnh tải avata-->
            <div class="box-avata">
                <div class="avata">
                    <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;">
                    <label for="image-upload">
                        <img class="img" id="preview-image" src="#" alt="Preview">
                    </label>
                </div>
            </div>

            <div class="box-box">
                <div class="box" id="box">
                    <div class="story">
                        <p for="story-title">Tiêu đề:</p>
                        <input type="text" name="book_title" id="story-title" placeholder="Nhập tiêu đề của truyện" width="200px" required>
                        <p for="story-content">Mô tả:</p>
                        <textarea id="story-content" name="book_describe" placeholder="Viết mô tả của truyện ở đây" width="300px" height="300px" required></textarea> <br>
                        <div class="hd">
                            <p>Tác giả:</p>
                            <input type="text" name="book_author" id="price"  width="200px" placeholder="Nhập tên tác giả" required />
                        </div>

                        <label for="publisher">Nhà xuất bản:</label>
                        <select id="publisher" name="book_publisher" required>
                            <option value="Nhà xuất bản Kim Đồng">Nhà xuất bản Kim Đồng</option>
                            <option value="Nhà xuất bản Trẻ">Nhà xuất bản Trẻ</option>
                            <option value="Nhà xuất bản IPM">Nhà xuất bản IPM</option>
                            <option value="Nhà xuất bản Amak">Nhà xuất bản Amak</option>
                            <option value="Nhà xuất bản Amak">Az Việt Nam</option>
                            <option value="Nhà xuất bản Amak">Nhà xuất bản Phúc Minh</option>
                            <option value="Nhà xuất bản Amak">Nhà xuất bản Moonbook</option>
                        </select> <br>

                        <label for="size">Kích thước:</label>
                        <select id="size" name="book_size" width="200px" required>
                            <option value="11.3x17.6 cm">11.3x17.6 cm</option>
                            <option value="13x18 cm">13x18 cm</option>
                            <option value="15.7x24 cm">15.7x24 cm</option>
                            <option value="14.5x20.5 cm">14.5x20.5 cm</option>
                        </select>

                        <div class="hd">
                            <p>Số tiền:</p>
                            <input type="number" name="book_original_price" id="price"  width="200px" placeholder="Nhập số tiền" min="0" step="1000" required />
                        </div>

                        <div class="hd">
                            <p>Phần trăm giảm:</p>
                            <input type="number" name="book_discount" id="discount" placeholder="Phần trăm giảm" min="0" max="100" step="1" required />
                        </div>

                        <div class="hd">
                            <p>Số lượng:</p>
                            <input type="number" name="book_quantity" id="quantity" placeholder="Nhập số lượng" min="0" step="1" required /> <br>
                        </div>

                        <!--Thể loại-->
                        <label for="category">Thể loại:</label>
                        <select name="book_category" class="form-group" required>
                            <option value="">Chọn thể loại</option>
                            <?php
                            // Kết nối database ở đây, vì trang này cần lấy dữ liệu categories
                            $mysqli = new mysqli("localhost", "root", "", "webbansach");

                            if ($mysqli->connect_errno) {
                                echo "Failed to connect to MySQL: " . $mysqli->connect_error;
                                exit();
                            }

                            $stmt = $mysqli->prepare("SELECT category_id, category_name FROM tbl_category");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $categories = $result->fetch_all(MYSQLI_ASSOC);

                            if ($categories) {
                                foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['category_id']); ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                                <?php endforeach;
                            } else {
                                echo "<option value=''>Không có thể loại nào</option>";
                            }

                            $mysqli->close();
                            ?>
                        </select>
                        <input type="submit" value="Lưu">
                    </div>
                </div>
            </div>
        </form>

    </div>
    <script src="javascript/Tao_san_pham.js" type="text/javascript"></script>
</body>
</html>