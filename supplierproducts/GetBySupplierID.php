<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$sID = $_GET['sID'];
$menu = mysqli_query($db_conn, "SELECT p.name, c.name AS cName FROM supplierproduct sp JOIN products p ON sp.product_id = p.id JOIN categories c ON p.category_id = c.id WHERE supplier_id = '$sID'");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "supplierproducts" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
