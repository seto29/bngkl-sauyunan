<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT gr.*, s.name AS sName, e.name as eName FROM goodsreceipts gr JOIN suppliers s ON gr.supplier_id = s.id JOIN employees e ON gr.created_by = e.id  WHERE gr.deleted = 0 ORDER BY gr.id DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "goodsreceipts" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
