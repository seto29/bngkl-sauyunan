<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';
$headers = apache_request_headers();
        $obj = json_decode(file_get_contents("php://input"));
        if(gettype($obj)=="NULL"){
            $obj = json_decode(json_encode($_POST));
        }
        $il = json_decode($obj->inputList);
        $kode = "RB".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `retur_pembelian_detail` WHERE kode_transaksi LIKE '$kode%' ORDER BY kode_transaksi DESC LIMIT 1");
            if (mysqli_num_rows($getCode) > 0) {
                $res = mysqli_fetch_all($getCode, MYSQLI_ASSOC);
                $i =(int) substr($res[0]['kode_transaksi'],8) +1;
                if($i<10){
                    $strI = "00".$i;
                }elseif ($i<100) {
                    $strI = "0".$i;
                }else{
                    $strI = $i;
                }
                $kode.=$strI;
            }else{
                $kode .= '001';
            }
        $nama_supplier=$obj->nama_supplier;
        $kode_supplier=$obj->kode_supplier;
        $alamat_supplier=$obj->alamat_supplier;
        $kota=$obj->kota;
        $telepon=$obj->telepon;
        $kode_user=$obj->kode_user;
        $nama_user=$obj->nama_user;
        // $kode_sales=$obj->kode_sales;
        $tanggal_retur= $obj->tanggal_retur;
        $strTj = date("Ymd",strtotime($obj->tanggal_retur));
        $jam = date("H:i:s");
            $total = 0;
        $nama_sales="";
        
        foreach ($il as $value) {
            $kd = $value->kode_barang;
            $pn = $value->part_number;
            $merk = $value->merk;
            $nama_barang = $value->nama_barang;
            $harga_beli = $value->harga_beli;
            $qty = $value->qty;
            $satuan = $value->satuan;
            $total_harga_beli = $qty*$harga_beli;
            $total = $total_harga_beli;

            $kw = substr($kd,0,2);
            $nama_kw = "";
            $komisi = 0;
            $kode_barangs = mysqli_query($db_conn, "SELECT * FROM `kode_barang` WHERE kode='$kw'");
            if (mysqli_num_rows($kode_barangs) > 0) {
                $all = mysqli_fetch_all($kode_barangs, MYSQLI_ASSOC);
                $nama_kw = $all[0]['nama'];
                $komisi = $all[0]['komisi'];
            }
            $komisi = ($total_harga_beli*$komisi)/100;

            $insertSales = mysqli_query($db_conn, "INSERT INTO `retur_pembelian_detail`(`kode_transaksi`, `kode_barang`, `part_number`, `nama_barang`, `merk`, `kode_supplier`, `nama_supplier`, `alamat_supplier`, `kota`, `telepon`, `kode_user`, `nama_user`, `harga_beli`, `qty`, `satuan`, `total_harga_beli`, `tanggal_retur`, `jam`) VALUES ('$kode','$kd','$pn','$nama_barang','$merk','$kode_supplier','$nama_supplier','$alamat_supplier','$kota','$telepon', '$kode_user','$nama_user','$harga_beli','$qty','$satuan','$total_harga_beli','$strTj','$jam')");
        }
        
        $retur_pembelian = mysqli_query($db_conn, "SELECT * FROM `retur_pembelian` WHERE kode_supplier='$kode_supplier'");
        if (mysqli_num_rows($retur_pembelian) > 0) {
            $insertSales = mysqli_query($db_conn, "UPDATE `retur_pembelian` SET `nilai`=`nilai`+'$total', `sisa`=`sisa`+'$total' WHERE `kode_supplier`='$kode_supplier'");
        }else{
            $insertSales = mysqli_query($db_conn, "INSERT INTO `retur_pembelian`(`kode_supplier`, `nilai`, `ambil`, `sisa`) VALUES ('$kode_supplier','$total',0,'$total')");
        }
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>