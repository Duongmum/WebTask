<?php
session_start(); // Bắt đầu phiên
include 'database.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem form đã được gửi không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $title = trim($_POST['title']);

    // Kiểm tra xem tiêu đề có rỗng không
    if (!empty($title)) {
        // Lấy ID người dùng từ phiên
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        } else {
            echo "Bạn cần đăng nhập để thêm task.";
            exit();
        }

        // Chuẩn bị câu lệnh SQL để thêm task
        $stmt = $mysqli->prepare("INSERT INTO tasks (tieu_de, user_id) VALUES (?, ?)");

        // Kiểm tra xem câu lệnh chuẩn bị có thành công không
        if ($stmt) {
            $stmt->bind_param("si", $title, $user_id); // 'si': s = string, i = integer

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                // Chuyển hướng về trang chính sau khi thêm thành công
                header("Location: main.php?success=1");
                exit(); // Dừng thực thi để không chạy mã phía dưới
            } else {
                echo "Lỗi khi thêm task: " . htmlspecialchars($stmt->error);
            }

            $stmt->close(); // Đóng câu lệnh
        } else {
            echo "Lỗi chuẩn bị câu lệnh: " . htmlspecialchars($mysqli->error);
        }
    } else {
        echo "Tiêu đề không được để trống.";
    }
}

// Đóng kết nối CSDL
$mysqli->close();
?>