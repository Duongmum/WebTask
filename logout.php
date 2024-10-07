<?php
// Bắt đầu phiên làm việc
session_start();

// Hủy tất cả các biến phiên
$_SESSION = [];

// Hủy phiên
session_destroy();

// Chuyển hướng về trang đăng nhập (index.php)
header("Location: index.php");
exit();
?>