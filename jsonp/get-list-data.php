<?php
// error_reporting(0);

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

$item = $_GET['item'];

if (isset($item)) {
    if ($item == 0) {
        $sql = "SELECT * FROM `Sensor01` ORDER BY id ASC";
    } else {
        $sql = "SELECT * FROM `Sensor01` ORDER BY id DESC LIMIT $item";
    }
} else {
    returnJSONP('{"code":2,"msg":"Error: No query"}');
}

$result = $conn->query($sql);

if ($result == false) {
    returnJSONP('{"code":3,"msg":"Error getting data from SQL"}');
} else {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $dataRow = array((int)$row['time'], (float)$row["temperature"], (float)$row["humidity"]);
        array_push($data, $dataRow);

    }
    $res = array("code"=>0,"data"=>$data);
    returnJSONP(json_encode($res));
}