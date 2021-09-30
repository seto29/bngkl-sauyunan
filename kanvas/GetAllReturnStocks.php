<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT * FROM `kanvas` WHERE qty_sisa!=0 ORDER BY seq DESC");
function tanggalF($tanggal)
{
    $y = $tanggal[0].$tanggal[1].$tanggal[2].$tanggal[3];
    $m = $tanggal[4].$tanggal[5];
    $d = $tanggal[6].$tanggal[7];
    $date = $d ."-". $m ."-". $y;
    return $date;
}
$resOA = array();
$res = array();
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    
    $i = 0;
    foreach($all as $a){
        if(isset($a['tanggal_ambil']) && !empty($a['tanggal_ambil'])){
            $all[$i]['tanggal_ambil'] = tanggalF($a['tanggal_ambil']);
        }
        if(isset($a['tanggal_jual']) && !empty($a['tanggal_jual'])){
            $all[$i]['tanggal_jual'] = tanggalF($a['tanggal_jual']);
        }
        if(isset($a['tanggal_kembali']) && !empty($a['tanggal_kembali'])){
            $all[$i]['tanggal_kembali'] = tanggalF($a['tanggal_kembali']);
        }
        $i+=1;
    }
    echo json_encode(["success" => 1, "kanvas" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
