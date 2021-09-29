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
            isset($obj->kode_penjualan) && !empty($obj->kode_penjualan)
            && isset($obj->kode_transaksi) && !empty($obj->kode_transaksi)
            && isset($obj->index_giro) && !empty($obj->index_giro)
            && isset($obj->nilai_giro) && !empty($obj->nilai_giro)
            && isset($obj->id_detail) && !empty($obj->id_detail)
            ){
                $kode_penjualan = $obj->kode_penjualan;
                $kode_transaksi = $obj->kode_transaksi;
                $index_giro = $obj->index_giro;
                $nilai_giro = $obj->nilai_giro;
                $id_detail = $obj->id_detail;
                $insertSales = mysqli_query($db_conn, "UPDATE `bayar_penjualan` SET `jumlah_bayar`=`jumlah_bayar`+'$nilai_giro',`sisa`=`sisa`-'$nilai_giro' WHERE kode_transaksi='$kode_transaksi'");
                
                if($index_giro=="1"){
                    $insertSales = mysqli_query($db_conn, "UPDATE `bayar_penjualan_detail` SET jumlah_giro1='$nilai_giro', `sisa`=`sisa`-'$nilai_giro',cair1='Ya' WHERE kode_transaksi='$id_detail'");
                }elseif ($index_giro=="2"){
                    $insertSales = mysqli_query($db_conn, "UPDATE `bayar_penjualan_detail` SET jumlah_giro2='$nilai_giro', `sisa`=`sisa`-'$nilai_giro',cair2='Ya' WHERE kode_transaksi='$id_detail'");
                }elseif ($index_giro=="3"){
                    $insertSales = mysqli_query($db_conn, "UPDATE `bayar_penjualan_detail` SET jumlah_giro3='$nilai_giro', `sisa`=`sisa`-'$nilai_giro',cair3='Ya' WHERE kode_transaksi='$id_detail'");
                }
            
            if ($insertSales) {
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Dimasukkan"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Memasukkan Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>