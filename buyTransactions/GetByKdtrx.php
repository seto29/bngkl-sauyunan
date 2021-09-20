<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$id =$_GET['id'];
$menu = mysqli_query($db_conn, "SELECT * FROM `pembelian` WHERE kode_transaksi='$id' ORDER BY seq DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $vals = array();
    foreach ($all as $val) {
        $data = $val;
        $data['jatuh_tempo'] = date("d-m-Y",strtotime($val['jatuh_tempo']));
        $data['tanggal_beli'] = date("d-m-Y",strtotime($val['tanggal_beli']))." ".$val['jam'];
        array_push($vals,$data);
    }
    echo json_encode(["success" => 1, "details" => $vals]);
} else {
    echo json_encode(["success" => 0]);
}
