<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT bayar_penjualan_detail.kode_transaksi AS id_detail, bayar_penjualan_detail.kode_transaksi2 AS kode_transaksi, bayar_penjualan_detail.kode_penjualan, bayar_penjualan_detail.jumlah_bayar, bayar_penjualan_detail.jumlah_retur, jumlah_giro1+jumlah_giro2+jumlah_giro3 AS jumlah_giro_cair, bayar_penjualan_detail.sisa, bayar_penjualan.kode_pelanggan, penjualan.nama_pelanggan, penjualan.tanggal_jual, penjualan.jatuh_tempo, bayar_penjualan.harga FROM `bayar_penjualan_detail` JOIN bayar_penjualan ON bayar_penjualan.kode_transaksi=bayar_penjualan_detail.kode_transaksi2 JOIN penjualan ON penjualan.kode_transaksi=bayar_penjualan_detail.kode_penjualan GROUP BY id_detail ORDER BY bayar_penjualan_detail.seq DESC ");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "paySells" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
