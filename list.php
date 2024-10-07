<?php
include 'database.php'; // Kết nối đến cơ sở dữ liệu

// Khởi tạo biến thông báo
$message = "";

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
        $message = "Vui lòng đăng nhập để xem danh sách các task.";
    exit();
}

// Lấy user_id từ phiên
$user_id = $_SESSION['user_id'];

// Chuẩn bị truy vấn SELECT sử dụng prepared statement
$stmt = $mysqli->prepare("SELECT * FROM tasks WHERE user_id = ?");
$stmt->bind_param("i", $user_id); // 'i' là kiểu integer

// Thực thi truy vấn SELECT
$stmt->execute();
$result = $stmt->get_result(); // lay gia tri cua bien stmt va luu duoi dang doi tuong

// Kiểm tra và xử lý kết quả
if ($result->num_rows > 0) {
    echo '<table>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tiêu đề</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="taskList">';

    // Khởi tạo biến đếm
    $stt = 1;

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $stt . '</td>
                <td>' . htmlspecialchars($row['tieu_de']) . '</td>
                <td>
                    <form action="delete.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">
                        <input type="submit" value="Xóa" onclick="return confirm(\'Bạn có chắc chắn muốn xóa task này không?\');">
                    </form>
                </td>
            </tr>';
        // Tăng biến đếm
        $stt++;
    }

    echo '</tbody></table>';
} else {
    echo "Không có task nào để hiển thị.";
}

// Giải phóng bộ nhớ
$stmt->close();

// Đóng kết nối CSDL
$mysqli->close();
?>