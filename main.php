<?php
session_start();
include 'database.php'; 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Task</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <div class="head_main">
        <h1>Quản lý Task</h1> 
        <a href="logout.php">Đăng xuất</a>
    </div>
    <div class="task">
        <h2>Danh sách Task của bạn</h2>
        <div class="add">
            <form action="add.php" method="POST">
                <input type="text" name="title" placeholder="Nhập tiêu đề task..." required>
                <input id="submit_add" type="submit" value="Thêm Task mới">
            </form>
        </div>

        <?php include 'list.php'; // Gọi tệp list.php để hiển thị danh sách task ?>
    </div>
</body>
</html>