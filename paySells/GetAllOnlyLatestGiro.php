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

$menu = mysqli_query($db_conn, "SELECT `penjualan`.`lama_tempo`, `penjualan`.`jatuh_tempo`, `bayar_penjualan_detail`.`kode_transaksi`, `pelanggan`.`kode` AS kode_pelanggan, `pelanggan`.`nama` AS nama_pelanggan, `pelanggan`.`alamat` AS alamat_pelanggan, `pelanggan`.`kota`, `pelanggan`.`telepon`, `sales`.`kode` AS kode_sales, `sales`.`nama` AS nama_sales, `bayar_penjualan_detail`.`harga`, `bayar_penjualan_detail`.`jumlah_giro1`, `bayar_penjualan_detail`.`jumlah_giro2`, `bayar_penjualan_detail`.`jumlah_giro3`, `bayar_penjualan_detail`.`jumlah_potongan`, `bayar_penjualan_detail`.`no_giro1`, `bayar_penjualan_detail`.`bank1`, `bayar_penjualan_detail`.`nilai_giro1`, `bayar_penjualan_detail`.`tanggal_cair1`, `bayar_penjualan_detail`.`cair1`, `bayar_penjualan_detail`.`no_giro2`, `bayar_penjualan_detail`.`bank2`, `bayar_penjualan_detail`.`nilai_giro2`, `bayar_penjualan_detail`.`tanggal_cair2`, `bayar_penjualan_detail`.`cair2`, `bayar_penjualan_detail`.`no_giro3`, `bayar_penjualan_detail`.`bank3`, `bayar_penjualan_detail`.`nilai_giro3`, `bayar_penjualan_detail`.`tanggal_cair3`, `bayar_penjualan_detail`.`cair3` FROM `bayar_penjualan_detail` JOIN `penjualan` ON `penjualan`.`kode_transaksi`=`bayar_penjualan_detail`.`kode_penjualan` JOIN `sales` ON `sales`.`kode`=`penjualan`.`kode_sales` JOIN `pelanggan` ON `pelanggan`.`kode`=`penjualan`.`kode_pelanggan` WHERE penjualan.tanggal_jual>='$time1' AND penjualan.tanggal_jual<='$time2' AND ((no_giro1!='')  OR (no_giro2!='') OR (no_giro3!='')) GROUP BY `bayar_penjualan_detail`.`kode_transaksi`");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "paySells" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
