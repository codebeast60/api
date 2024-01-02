<?php
header("Access-Control-Allow-Origin: *");
header("Contnte-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
include "connect.php";
$sql = "SELECT name,email,image From users WHERE groupid = 0";
$result = mysqli_query($conn, $sql);

if ($result) {
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'error while fetching data from DB']);
}

mysqli_close($conn);
