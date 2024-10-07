<?php
include 'database.php'; // Kết nối cơ sở dữ liệu

// Khởi tạo biến thông báo
$message = "";

// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $re_password = $_POST['re-password'];

    // Kiểm tra mật khẩu nhập lại
    if ($password !== $re_password) {
        $message = "Mật khẩu không khớp!";
    } else {
        dangKy($mysqli, $username, $password, $email);
    }
}

// Hàm đăng ký người dùng
function dangKy($mysqli, $username, $password, $email) {
    global $message; // Sử dụng biến toàn cục
    // Kiểm tra xem tên đăng nhập hoặc email đã tồn tại chưa
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();//nhận kết quả dưới dạng đối tượng và lưu vào biến $result     
    if ($result->num_rows > 0) {
        $message = "Tên đăng nhập hoặc email đã tồn tại!";
        return false;
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Thêm người dùng mới vào cơ sở dữ liệu
    $stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        $message = "Đăng ký thành công!"; 
        header("Location: index.php");
        exit(); // Dừng thực thi để không chạy mã phía dưới
    } else {
        $message = "Lỗi: " . $stmt->error;
    }
}

// Đóng kết nối
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div id="form">
        <form action="" method="POST">
            <h2>ĐĂNG KÝ</h2>
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
            <label for="re-password">Nhập lại Mật khẩu</label>
            <input type="password" id="re-password" name="re-password" required>
            <input type="submit" name="register" value="Đăng ký"><br><br>
            <p>Bạn đã có tài khoản | <a href="index.php">Đăng nhập</a></p>
            <?php if ($message): ?>
                <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>