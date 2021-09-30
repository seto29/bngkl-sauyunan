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

$menu = mysqli_query($db_conn, "SELECT `pembelian`.`lama_tempo`, `pembelian`.`jatuh_tempo`, `bayar_pembelian_detail`.`kode_transaksi`, `supplier`.`kode` AS kode_supplier, `supplier`.`nama` AS nama_supplier, `supplier`.`alamat` AS alamat_supplier, `supplier`.`kota`, `supplier`.`telepon`, `user`.`kode` AS kode_user, `user`.`nama` AS nama_user, `bayar_pembelian_detail`.`harga`, `bayar_pembelian_detail`.`jumlah_giro1`, `bayar_pembelian_detail`.`jumlah_giro2`, `bayar_pembelian_detail`.`jumlah_giro3`, `bayar_pembelian_detail`.`jumlah_potongan`, `bayar_pembelian_detail`.`no_giro1`, `bayar_pembelian_detail`.`bank1`, `bayar_pembelian_detail`.`nilai_giro1`, `bayar_pembelian_detail`.`tanggal_cair1`, `bayar_pembelian_detail`.`cair1`, `bayar_pembelian_detail`.`no_giro2`, `bayar_pembelian_detail`.`bank2`, `bayar_pembelian_detail`.`nilai_giro2`, `bayar_pembelian_detail`.`tanggal_cair2`, `bayar_pembelian_detail`.`cair2`, `bayar_pembelian_detail`.`no_giro3`, `bayar_pembelian_detail`.`bank3`, `bayar_pembelian_detail`.`nilai_giro3`, `bayar_pembelian_detail`.`tanggal_cair3`, `bayar_pembelian_detail`.`cair3` FROM `bayar_pembelian_detail` JOIN `pembelian` ON `pembelian`.`kode_transaksi`=`bayar_pembelian_detail`.`kode_pembelian` JOIN `user` ON `user`.`kode`=`pembelian`.`kode_user` JOIN `supplier` ON `supplier`.`kode`=`pembelian`.`kode_supplier` WHERE pembelian.tanggal_beli>='$time1' AND pembelian.tanggal_beli<='$time2' AND ((no_giro1!='')  OR (no_giro2!='') OR (no_giro3!='')) GROUP BY `bayar_pembelian_detail`.`kode_transaksi`");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "paySells" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
