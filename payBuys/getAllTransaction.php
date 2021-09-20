<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT bayar_pembelian.*, pembelian.kode_transaksi AS kode_pembelian, bayar_pembelian_detail.nama_supplier, bayar_pembelian_detail.tanggal_beli, bayar_pembelian_detail.jatuh_tempo FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE bayar_pembelian.sisa!=0 GROUP BY bayar_pembelian.kode_transaksi");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "payBuys" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
