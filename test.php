<?php
session_start();
include_once('db/connect.php');

// Đăng ký tài khoản
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Kiểm tra mật khẩu
    if ($password !== $confirm_password) {
        echo "Mật khẩu không khớp!";
    } else {
        // Kiểm tra số điện thoại đã tồn tại
        $check_phone = $mysqli->prepare("SELECT * FROM tbl_users WHERE user_phone = ?");
        $check_phone->bind_param("s", $phone);
        $check_phone->execute();
        $result = $check_phone->get_result();

        if ($result->num_rows > 0) {
            echo "Số điện thoại đã được sử dụng!";
        } else {
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO tbl_users (user_name, user_email, user_phone, user_password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);
            if ($stmt->execute()) {
                echo "Đăng ký thành công!";
            } else {
                echo "Có lỗi xảy ra. Vui lòng thử lại.";
            }
            $stmt->close();
        }
        $check_phone->close();
    }
}

// Đăng nhập tài khoản
if (isset($_POST['login'])) {
    $phone = $_POST['login_phone'];
    $password = $_POST['login_password'];

    $stmt = $mysqli->prepare("SELECT * FROM tbl_users WHERE user_phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            header("Location: index.php");
            exit();
        } else {
            echo "Mật khẩu không đúng!";
        }
    } else {
        echo "Số điện thoại không tồn tại!";
    }
    $stmt->close();
}

// Đăng xuất
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
