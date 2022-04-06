<?php
error_reporting(0);

$servername = "localhost";
$username = "xuansensor";
$password = "8eZWRShAHprLcbpd";
$dbname = "xuansensor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    exit('{"code":1,"message":"Error connected to SQL"}');
}

if (isset($_GET['time'])) {
    $time = $_GET['time'];
} else {
    $time = time();
}

$sql = "INSERT INTO `Sensor01`(`time`,`temperature`, `humidity`) VALUES (" . $time . "," . $_GET['tem'] . "," . $_GET['hum'] . ")";
if ($conn->query($sql) === TRUE) {
    exit('{"code":0,"msg":"success"}');
} else {
    exit('{"code":2,"message":"Error write to SQL: ' . $conn->error . '}');
}
