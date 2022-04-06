<?php
error_reporting(0);

$servername = "localhost";
$username = "xuansensor";
$password = "8eZWRShAHprLcbpd";
$dbname = "xuansensor";

$conn = new mysqli($servername, $username, $password, $dbname);

function returnJSONP($resStr){
    $callback = $_GET['callback'];
    exit($callback.'('.$resStr.')');
}

if ($conn->connect_error) {
    returnJSONP('{"code":1,"message":"Error connected to SQL"}');
}

$sql = "SELECT * FROM `Sensor01` ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result == false) {
    returnJSONP('{"code":3,"msg":"Error getting data from SQL"}');
} else {
    $row = $result->fetch_assoc();
    $data = array("time" => $row['time'],"tem"=>$row["temperature"],"hum"=> $row["humidity"] );
    $res = array("code"=>0,"data"=>$data);
    returnJSONP(json_encode($res));
}
