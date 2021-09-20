<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT p.*, s.nama as nama_sales FROM pelanggan p JOIN sales s ON s.kode=p.kode_sales WHERE p.deleted_at IS NULL ORDER BY p.kode ASC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "custumers" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
