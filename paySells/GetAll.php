<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT bayar_penjualan.*, penjualan.kode_transaksi AS kode_penjualan, bayar_penjualan_detail.nama_pelanggan, bayar_penjualan_detail.tanggal_jual, bayar_penjualan_detail.jatuh_tempo, bayar_penjualan_detail.sisa, bayar_penjualan_detail.jumlah_bayar FROM `bayar_penjualan` JOIN bayar_penjualan_detail ON bayar_penjualan_detail.kode_transaksi2=bayar_penjualan.kode_transaksi JOIN penjualan ON penjualan.kode_transaksi=bayar_penjualan_detail.kode_penjualan ORDER BY bayar_penjualan.seq ");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "paySells" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
