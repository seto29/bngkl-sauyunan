<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$dateTo = $_GET['dateTo'];
$dateFrom = $_GET['dateFrom'];

$menu = mysqli_query($db_conn, "SELECT SUM(credit) AS credit FROM transactions WHERE deleted=0 AND DATE(transactions.created_at) BETWEEN '$dateFrom' AND '$dateTo'");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "expenses" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
