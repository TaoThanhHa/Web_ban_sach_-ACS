<?php
session_start();
include_once('db/connect.php');

// Truy vấn lấy dữ liệu người dùng
function getUsers($mysqli, $search_keyword = '') {
    $sql = "SELECT id, name, email, phone, status FROM tbl_user WHERE role = 'user'";

    if (!empty($search_keyword)) {
        $search_keyword = mysqli_real_escape_string($mysqli, $search_keyword);
        $sql .= " AND (email LIKE '%$search_keyword%' OR phone LIKE '%$search_keyword%')";
    }

    $result = $mysqli->query($sql);

    if (!$result) {
        echo "Lỗi truy vấn: " . $mysqli->error;
        return NULL;
    }

    return $result;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Quản_lý_người dùng.css?php echo time(); ?>" type="text/css">
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<?php include 'header_admin.php'; ?>

<div class="container my-5">
    <h1>Quản lý Người dùng</h1>

      <!-- Form tìm kiếm -->
      <div class="search-form">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Tìm kiếm email hoặc số điện thoại" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Tìm kiếm</button>
        </form>
        <button id="save-changes" class="btn btn-primary">Lưu thay đổi</button>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

        $result = getUsers($mysqli, $search_keyword);

        // Kiểm tra xem $result có phải là null hay không trước khi lặp
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td contenteditable="true" class="editable" data-id="<?php echo $row['id']; ?>" data-field="name">
                        <?php echo htmlspecialchars($row['name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td>
                        <select class="status-select" data-id="<?php echo $row['id']; ?>">
                            <option value="0" <?php echo $row['status'] == 0 ? 'selected' : ''; ?>>Active</option>
                            <option value="1" <?php echo $row['status'] == 1 ? 'selected' : ''; ?>>Locked</option>
                        </select>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='5'>Không có dữ liệu người dùng.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        var changes = [];

        // Hàm để thêm thay đổi vào mảng
        function addChange(id, field, value) {
            var existingChangeIndex = changes.findIndex(function (change) {
                return change.id === id && change.field === field;
            });

            if (existingChangeIndex !== -1) {
                changes[existingChangeIndex].value = value;
            } else {
                changes.push({id: id, field: field, value: value});
            }
        }

        // Xử lý cập nhật Name, Email, Phone trực tiếp
        $('.editable').blur(function () {
            var id = $(this).data('id');
            var field = $(this).data('field');
            var value = $(this).text();
            addChange(id, field, value);
        });

        // Xử lý cập nhật Status trực tiếp
        $('.status-select').change(function () {
            var id = $(this).data('id');
            var value = $(this).val();
            addChange(id, 'status', value);
        });

        // Xử lý sự kiện click nút "Lưu"
        $('#save-changes').click(function () {
            if (changes.length > 0) {
                // Gửi mảng các thay đổi đến server
                $.ajax({
                    url: 'update_users.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(changes),
                    success: function (response) {
                        try {
                            var data = JSON.parse(response);
                            if (data.status === 'success') {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Lỗi: ' + data.message);
                            }
                        } catch (e) {
                            alert('Lỗi phân tích JSON: ' + e);
                        }
                    },
                    error: function (xhr, status, error) {
                        alert('Lỗi AJAX: ' + error);
                    }
                });
                // Reset mảng changes sau khi gửi thành công (hoặc thất bại)
                changes = [];
            } else {
                alert('Không có thay đổi nào để lưu.');
            }
        });
    });
</script>
</body>
</html>
<?php
if (isset($mysqli)) {
    $mysqli->close();
}
?>