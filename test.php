<?php
// error_reporting(0);

$servername = "localhost";
$username = "xuansensor";
$password = "8eZWRShAHprLcbpd";
$dbname = "xuansensor";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    exit('{"code":1,"message":"Error connected to SQL"}');
}

$sql = "SELECT * FROM `Sensor01` ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result == false) {
    exit('{"code":3,"msg":"Error getting data from SQL"}');
} else {
    $row = $result->fetch_assoc();
    $data = array("time" => $row['time'],"tem"=>$row["temperature"],"hum"=> $row["humidity"] );
    $res = array("code"=>0,"data"=>$data);
    exit(json_encode($res));
}
