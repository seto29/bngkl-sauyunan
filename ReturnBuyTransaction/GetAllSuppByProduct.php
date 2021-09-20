<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$kode_product = $_GET['kode_product'];
$menu = mysqli_query($db_conn, "SELECT kode_supplier, nama_supplier, alamat_supplier, kota, telepon FROM `pembelian` WHERE kode_barang='$kode_product' GROUP BY kode_supplier");

$resOA = array();
$res = array();
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "kode_supplier" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
