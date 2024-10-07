<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";
$mysqli = new mysqli($servername,$username,$password,$dbname);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Kết nối CSDL thất bại: " . $mysqli -> connect_error;
  exit();
}
?>