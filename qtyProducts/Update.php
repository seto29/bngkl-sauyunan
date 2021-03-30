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

        if(
            isset($obj->kode) && !empty($obj->kode)
            && isset($obj->part_number) && !empty($obj->part_number)
            && isset($obj->nama) && !empty($obj->nama)
            && isset($obj->merk) && !empty($obj->merk)
            && isset($obj->satuan) && !empty($obj->satuan)
            ){
            $kode = $obj -> kode;
            $part_number = $obj -> part_number;
            $barcode = $obj -> barcode?$obj -> barcode:0;
            $nama = $obj -> nama;
            $merk = $obj -> merk;
            $satuan = $obj -> satuan;
            $fast_moving = $obj -> fast_moving;
            $beli = $obj -> beli?$obj -> beli:0;
            $jual1 = $obj ->jual1?$obj ->jual1:0;
            $jual2 = $obj ->jual2?$obj ->jual2:0;
            $jual3 = $obj ->jual3?$obj ->jual3:0;
            $foto = $obj ->foto?$obj->foto:'none.jpg';
            $stock_minimal = $obj -> stock_minimal?$obj -> stock_minimal:0;
            $jumlah_grosir = $obj -> jumlah_grosir?$obj -> jumlah_grosir:0;
            $harga_grosir = $obj -> harga_grosir?$obj -> harga_grosir:0;
            
            $insert = mysqli_query($db_conn, "UPDATE `barang` SET `kode`='$kode', `part_number`='$part_number', `barcode`='$barcode', `nama`='$nama', `merk`='$merk', `satuan`='$satuan', `beli`='$beli', `jual1`='$jual1', `jual2`='$jual2', `jual3`='$jual3', `foto`='$foto', `fast_moving`='$fast_moving', `stock_minimal`='$stock_minimal', `jumlah_grosir`='$jumlah_grosir', `harga_grosir`='$harga_grosir' WHERE `kode`='$kode' ");
            if ($insert) {
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Diubah"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Mengubah Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>