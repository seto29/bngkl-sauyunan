<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$id = $_GET['id'];

$menu = mysqli_query($db_conn, "SELECT grd.id, grd.qty, grd.unit_price, grd.product_id, p.name AS pName FROM salesdetails grd JOIN products p ON grd.product_id = p.id WHERE grd.deleted = 0 AND grd.sales_id='$id' ORDER BY grd.id DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "details" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
