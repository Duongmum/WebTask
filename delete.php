<?php
session_start(); // Bắt đầu phiên
include 'database.php'; // Kết nối đến cơ sở dữ liệu

// Khởi tạo biến thông báo
$message = "";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "Bạn cần đăng nhập để xóa task.";
    exit();
}

// Lấy ID task từ phương thức POST
if (isset($_POST['id'])) {
    $task_id = (int) $_POST['id']; // Chuyển đổi ID thành số nguyên

    // Lấy user_id từ phiên
    $user_id = $_SESSION['user_id'];

    // Chuẩn bị câu lệnh SQL để xóa taskedit
    $stmt = $mysqli->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    
    if ($stmt) {
        $stmt->bind_param("ii", $task_id, $user_id); // 'ii': i = integer

        // Thực thi câu lệnh xóa
        if ($stmt->execute()) {
            // Kiểm tra số hàng bị ảnh hưởng
            if ($stmt->affected_rows > 0) {
                // Chuyển hướng về trang chính sau khi xóa thành công
                header("Location: main.php?success=1");
                exit();
            } else {
             $message = "Task không tồn tại hoặc bạn không có quyền xóa.";
            }
        } else {
             $message = "Lỗi khi xóa task: " . htmlspecialchars($stmt->error);
        }

        $stmt->close(); // Đóng câu lệnh
    } else {
        $message = "Lỗi chuẩn bị câu lệnh: " . htmlspecialchars($mysqli->error);
    }
} else {
        $message = "ID task không hợp lệ.";
}

// Đóng kết nối CSDL
$mysqli->close();
?>