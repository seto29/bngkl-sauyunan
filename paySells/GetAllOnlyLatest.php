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

$menu = mysqli_query($db_conn, "SELECT bayar_penjualan_detail.kode_transaksi AS id_detail, bayar_penjualan_detail.kode_transaksi2 AS kode_transaksi, bayar_penjualan_detail.kode_penjualan, MAX(bayar_penjualan_detail.jumlah_bayar) jumlah_bayar, bayar_penjualan_detail.jumlah_retur, MAX(jumlah_giro1+jumlah_giro2+jumlah_giro3) AS jumlah_giro_cair, MIN(bayar_penjualan_detail.sisa) sisa, bayar_penjualan.kode_pelanggan, penjualan.nama_pelanggan, penjualan.tanggal_jual, penjualan.jatuh_tempo, bayar_penjualan.harga FROM `bayar_penjualan_detail` JOIN bayar_penjualan ON bayar_penjualan.kode_transaksi=bayar_penjualan_detail.kode_transaksi2 JOIN penjualan ON penjualan.kode_transaksi=bayar_penjualan_detail.kode_penjualan WHERE penjualan.tanggal_jual>='$time1' AND penjualan.tanggal_jual<='$time2'  GROUP BY kode_penjualan ORDER BY kode_penjualan DESC, bayar_penjualan_detail.sisa ASC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $arr = array();
    $i = 0;
    $temp = 0;
    foreach ($all as $value) {
        $date = substr($value['tanggal_jual'],6,2)."-".substr($value['tanggal_jual'],4,2)."-".substr($value['tanggal_jual'],0,4);
        $value['tanggal_jual']=$date;
        $date = substr($value['jatuh_tempo'],6,2)."-".substr($value['jatuh_tempo'],4,2)."-".substr($value['jatuh_tempo'],0,4);
        $value['jatuh_tempo']=$date;
        if($i==0){
            array_push($arr, $value);
        }else{
            if($temp!=$value['kode_penjualan']){
                array_push($arr, $value);
            }
        }
        $temp= $value['kode_penjualan'];
        $i+=1;
    }
    echo json_encode(["success" => 1, "paySells" => $arr]);
} else {
    echo json_encode(["success" => 0]);
}
