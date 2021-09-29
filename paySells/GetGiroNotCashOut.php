<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT bayar_penjualan.*, bayar_penjualan_detail.kode_penjualan, penjualan.nama_pelanggan, penjualan.jatuh_tempo, penjualan.tanggal_jual, bayar_penjualan_detail.jumlah_bayar, bayar_penjualan_detail.sisa, bayar_penjualan_detail.kode_transaksi AS id_detail,
bayar_penjualan_detail.no_giro1, bayar_penjualan_detail.jumlah_giro1, bayar_penjualan_detail.bank1, bayar_penjualan_detail.nilai_giro1, bayar_penjualan_detail.tanggal_cair1 , bayar_penjualan_detail.cair1,
bayar_penjualan_detail.no_giro2, bayar_penjualan_detail.jumlah_giro2, bayar_penjualan_detail.bank2, bayar_penjualan_detail.nilai_giro2, bayar_penjualan_detail.tanggal_cair2, bayar_penjualan_detail.cair2,
bayar_penjualan_detail.no_giro3, bayar_penjualan_detail.jumlah_giro3, bayar_penjualan_detail.bank3, bayar_penjualan_detail.nilai_giro3, bayar_penjualan_detail.tanggal_cair3, bayar_penjualan_detail.cair3
FROM `bayar_penjualan` JOIN bayar_penjualan_detail ON bayar_penjualan_detail.kode_transaksi2=bayar_penjualan.kode_transaksi JOIN penjualan ON penjualan.kode_transaksi=bayar_penjualan_detail.kode_penjualan WHERE bayar_penjualan.sisa!=0 AND (no_giro1!='' AND cair1='Tidak')  OR (no_giro2!='' AND cair2='Tidak') OR (no_giro3!='' AND cair3='Tidak')
GROUP BY id_detail
ORDER BY bayar_penjualan_detail.seq DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "payBuys" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
