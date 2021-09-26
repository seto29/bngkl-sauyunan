<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$dateFrom = $_GET['dateFrom'];
$dateUntil = $_GET['dateUntil'];

$timestamp = strtotime($dateFrom);
$time1 = date('Ymd', $timestamp);

$timestamp = strtotime($dateUntil);
$time2 = date('Ymd', $timestamp);
$menu = mysqli_query($db_conn, "SELECT * FROM `kanvas` WHERE qty_jual!=0 AND tanggal_ambil>='$time1' AND tanggal_ambil<='$time2' ORDER BY kode_transaksi DESC");

$resOA = array();
$res = array();
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "kanvas" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
