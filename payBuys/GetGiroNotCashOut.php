<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT bayar_pembelian.*, bayar_pembelian_detail.kode_pembelian, pembelian.nama_supplier, pembelian.jatuh_tempo, pembelian.tanggal_beli, bayar_pembelian_detail.jumlah_bayar, bayar_pembelian_detail.sisa, bayar_pembelian_detail.kode_transaksi AS id_detail,
bayar_pembelian_detail.no_giro1, bayar_pembelian_detail.jumlah_giro1, bayar_pembelian_detail.bank1, bayar_pembelian_detail.nilai_giro1, bayar_pembelian_detail.tanggal_cair1 , bayar_pembelian_detail.cair1,
bayar_pembelian_detail.no_giro2, bayar_pembelian_detail.jumlah_giro2, bayar_pembelian_detail.bank2, bayar_pembelian_detail.nilai_giro2, bayar_pembelian_detail.tanggal_cair2, bayar_pembelian_detail.cair2,
bayar_pembelian_detail.no_giro3, bayar_pembelian_detail.jumlah_giro3, bayar_pembelian_detail.bank3, bayar_pembelian_detail.nilai_giro3, bayar_pembelian_detail.tanggal_cair3, bayar_pembelian_detail.cair3
FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE bayar_pembelian.sisa!=0 AND (no_giro1!='' AND cair1='Tidak')  OR (no_giro2!='' AND cair2='Tidak') OR (no_giro3!='' AND cair3='Tidak')
ORDER BY bayar_pembelian_detail.seq DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "payBuys" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
