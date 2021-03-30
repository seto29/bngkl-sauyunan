<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT * FROM `penjualan` WHERE kode_kanvas='' OR kode_kanvas IS NULL ORDER BY `seq` DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $vals = array();
    foreach ($all as $val) {
        $data = $val;
        $data['jatuh_tempo'] = date("d-m-Y",strtotime($val['jatuh_tempo']))." ".$val['jam'];
        $data['tanggal_jual'] = date("d-m-Y",strtotime($val['tanggal_jual']));
        array_push($vals,$data);
    }
    echo json_encode(["success" => 1, "salesTransactions" => $vals]);
} else {
    echo json_encode(["success" => 0]);
}
