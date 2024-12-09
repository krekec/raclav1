<?php
$servername = "88.200.86.10";
$username = "2024_TB_02"; 
$password = "nq5VRyJEy"; 
$dbname = "2024_tb_02"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>