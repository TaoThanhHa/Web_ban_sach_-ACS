<?php
session_start(); 
include_once('db/connect.php');

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Bạn cần đăng nhập để liên hệ.');
<<<<<<< HEAD
            window.location.href = 'ĐN.php'; 
=======
            window.location.href = 'index.php'; 
>>>>>>> a8ecea68f1a6024d1c11193e5a5c5a7d166d51cf
          </script>";
    exit();
}

// Lấy user_id từ session
$user_id = $_SESSION['user_id'];

$reply = "";

// Truy vấn database để tìm phản hồi cho user này
$sql = "SELECT reply FROM contacts WHERE user_id = ? AND status = 'replied'";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $reply = $row['reply'];
} else {
    $reply = "Chưa có phản hồi nào.";
}

$stmt->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Liên_hệ.css" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/header.css" type="text/css">

</head>
<body>
    <?php include 'header.php'; ?>
    <section class="wrapper">
        <div class="left-section">
            <h2>Liên hệ</h2>
            <p>Nếu bạn có thắc mắc gì, có thể gửi yêu cầu cho chúng tôi, và chúng tôi sẽ liên lạc lại với bạn sớm nhất có thể.</p>
            <!-- Thêm action và method -->
            <form action="submit_contact.php" method="POST">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="Tên của bạn" required>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email của bạn" required>
                </div>
                <div class="form-group">
                    <textarea id="comment" name="comment" rows="4" placeholder="Viết bình luận" required></textarea>
                </div>
                <button type="submit">Gửi liên hệ</button>
            </form>


            <?php if (!empty($reply)): ?>
                <h3>Phản hồi của chúng tôi:</h3>
                <p><?php echo htmlspecialchars($reply); ?></p>
            <?php endif; ?>
        </div>
        <div class="right-section">
            <h2>Chúng tôi ở đây</h2>
            <p><strong>Book Haven</strong></p>
            <p>Công ty Cổ phần Xuất bản và Truyền thông Book Haven</p>
            <p>
                <i class="fas fa-map-marker-alt"></i> Here, Hà Nội<br>
                <i class="fas fa-envelope"></i> bookhaven@gmail.com<br>
                <i class="fas fa-phone"></i> 0666688889
            </p>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script src="javascript/Liên_hệ.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>