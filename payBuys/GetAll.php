<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

function tanggalF($tanggal)
{
    $y = $tanggal[0].$tanggal[1].$tanggal[2].$tanggal[3];
    $m = $tanggal[4].$tanggal[5];
    $d = $tanggal[6].$tanggal[7];
    $date = $d ."-". $m ."-". $y;
    return $date;
}
$menu = mysqli_query($db_conn, "SELECT bayar_pembelian.*, bayar_pembelian_detail.kode_pembelian, pembelian.nama_supplier, pembelian.jatuh_tempo, pembelian.tanggal_beli, bayar_pembelian_detail.jumlah_bayar, bayar_pembelian_detail.sisa,bayar_pembelian_detail.jumlah_giro1 + bayar_pembelian_detail.jumlah_giro2 + bayar_pembelian_detail.jumlah_giro3 jumlah_giro_cair FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian ORDER BY bayar_pembelian_detail.seq DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    
    $i = 0;
    foreach($all as $a){
        if(isset($a['tanggal_beli']) && !empty($a['tanggal_beli'])){
            $all[$i]['tanggal_beli'] = tanggalF($a['tanggal_beli']);
        }
        if(isset($a['jatuh_tempo']) && !empty($a['jatuh_tempo'])){
            $all[$i]['jatuh_tempo'] = tanggalF($a['jatuh_tempo']);
        }
        $i+=1;
    }
    echo json_encode(["success" => 1, "payBuys" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
