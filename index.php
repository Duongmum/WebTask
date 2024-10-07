<?php
session_start();
include 'database.php'; // Kết nối cơ sở dữ liệu

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login']))  {
    $username = $_POST['username'];
    $password = $_POST['password'];
    dangNhap($mysqli, $username, $password);
}

// Khởi tạo biến thông báo
$message = "";

// Hàm đăng nhập người dùng
function dangNhap($mysqli, $username, $password) {
    global $message; // Sử dụng biến toàn cục
    // Lấy thông tin người dùng từ cơ sở dữ liệu
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");// Chuẩn bị câu lệnh và lưu trong biến $stmt
    $stmt->bind_param("s", $username); // Kết nối biến với tham số username, "s" đại diện cho chuỗi
    $stmt->execute(); //Thực thi câu lệnh 
    $result = $stmt->get_result(); //Nhận kết quả của biến $stmt dưới dạng đối tượng và lưu vào biến $result 

    if ($result->num_rows === 0) {
        $message = "Tên đăng nhập không tồn tại!";
        return false;
    }

    $user = $result->fetch_assoc();//Lấy dữ liệu lưu dưới dạng 1 mảng kêt hợp 
    print_r($user);
    // Xác thực mật khẩu
    if (password_verify($password, $user['password'])) //So sánh mật khẩu người dùng nhập 
    {
        $_SESSION['user_id'] = $user['id']; //lưu vào session id người dùng và username
        $_SESSION['username'] = $user['username'];
        header("Location: main.php"); // Chuyển hướng đến trang chính
        exit(   );
    } else {
        $message = "Mật khẩu không đúng!";
        return false;
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
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div id="form">
        <form action="" method="POST">
            <h2>ĐĂNG NHẬP</h2>
            <label for="username">Tên đăng nhập</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" name="login" value="Đăng nhập"><br><br>
            <p>Bạn chưa có tài khoản? |  <a href="register.php">Đăng ký</a></p>
            <?php if ($message): ?>
                <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>