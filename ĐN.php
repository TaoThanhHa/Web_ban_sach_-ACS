<?php
include_once('db/connect.php');

if (isset($_POST['login'])) {
    $user = $_POST['user']; // Người dùng nhập email hoặc tên
    $pass = $_POST['pass']; // Mật khẩu nhập vào

    // Truy vấn để lấy mật khẩu đã lưu và vai trò
    $stmt = $mysqli->prepare("SELECT pass, role FROM tbl_users WHERE email = ? OR user = ?");
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedPassword, $role);
        $stmt->fetch();

        // So sánh mật khẩu nhập vào với mật khẩu đã lưu
        if ($pass === $storedPassword) { 
            if ($role == 1) {
                header("Location: Admin.html"); // Giao diện cho role 1
            } else {
                header("Location: Trang_chủ.php"); // Giao diện cho role 0
            }
        } else {
            echo "Mật khẩu không chính xác.";
        }
    } else {
        echo "Người dùng không tồn tại.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Đăng nhập</title>
        <link rel="stylesheet" href="/webbansach/css/ĐN.css" type="text/css">
    </head>
    <body>
        <div class="gdđn">
            <div class="khunglon">
                <div class="dangnhap">
                    <div class="dnvao">
                        <span class="chu">Đăng nhập vào tài khoản của bạn</span>
                    </div>
                    
                        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <div class="chon">
                            <p class="mot">Email hoặc Tên người dùng<span class="sao">*</span></p>
                            <input type="text" id="" name="user" class="tdnemail" required="" value="">
                        </div>
                        <div class="chon">
                            <p class="mot">Mật khẩu<span class="sao">*</span></p>
                            <input type="password" id="" name="pass" class="pass" required="" value="">
                        </div>
                        <button name="login" class="bt">Đăng nhập</button>
                        </form>

                        <div class="qmk">
                        <a href="#">Quên mật khẩu?</a>
                    </div>
                    <div class="back">
                        <a href="GDĐN.html">Trở lại các mục đăng nhập</a>
                    </div>
                    <div class="last">
                        Chưa có tài khoản?
                        <a class="dki" href="ĐK.php">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>