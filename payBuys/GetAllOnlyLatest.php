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

$menu = mysqli_query($db_conn, "SELECT bayar_pembelian_detail.kode_transaksi AS id_detail, bayar_pembelian_detail.kode_transaksi2 AS kode_transaksi, bayar_pembelian_detail.kode_pembelian, MAX(bayar_pembelian_detail.jumlah_bayar) jumlah_bayar, bayar_pembelian_detail.jumlah_retur, MAX(jumlah_giro1+jumlah_giro2+jumlah_giro3) AS jumlah_giro_cair, MIN(bayar_pembelian_detail.sisa) sisa, bayar_pembelian.kode_supplier, pembelian.nama_supplier, pembelian.tanggal_beli, pembelian.jatuh_tempo, bayar_pembelian.harga FROM `bayar_pembelian_detail` JOIN bayar_pembelian ON bayar_pembelian.kode_transaksi=bayar_pembelian_detail.kode_transaksi2 JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE pembelian.tanggal_beli>='$time1' AND pembelian.tanggal_beli<='$time2' GROUP BY kode_pembelian ORDER BY kode_pembelian DESC, bayar_pembelian_detail.sisa ASC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $arr = array();
    $i = 0;
    $temp = 0;
    foreach ($all as $value) {
        $date = substr($value['tanggal_beli'],6,2)."-".substr($value['tanggal_beli'],4,2)."-".substr($value['tanggal_beli'],0,4);
        $value['tanggal_beli']=$date;
        $date = substr($value['jatuh_tempo'],6,2)."-".substr($value['jatuh_tempo'],4,2)."-".substr($value['jatuh_tempo'],0,4);
        $value['jatuh_tempo']=$date;
        if($i==0){
            array_push($arr, $value);
        }else{
            if($temp!=$value['kode_pembelian']){
                array_push($arr, $value);
            }
        }
        $temp= $value['kode_pembelian'];
        $i+=1;
    }
    echo json_encode(["success" => 1, "paySells" => $arr]);
} else {
    echo json_encode(["success" => 0]);
}
