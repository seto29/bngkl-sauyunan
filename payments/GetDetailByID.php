<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$id = $_GET['id'];

$menu = mysqli_query($db_conn, "SELECT payment.sales_id, payment.due_date, payment.amount, shops.name AS sName, sales.code FROM payment JOIN sales ON payment.sales_id = sales.id JOIN shops ON sales.shop_id = shops.id WHERE payment.id='$id'");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "payments" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
