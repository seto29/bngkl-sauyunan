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
        $tanggal_kembali = date("Ymd");
        $jam_kembali = date("H:i:sa");
        $kode_transaksi = $obj->kode_transaksi;
        
        foreach ($il as $value) {
            $kode_barang = $value->id;
            $qty_kembali = $value->qty_kembali;
        
            $insertSales = mysqli_query($db_conn, "UPDATE `kanvas` SET `qty_sisa`='$qty_kembali', `tanggal_kembali`='$tanggal_kembali',`jam_kembali`='$jam_kembali' WHERE kode_transaksi='$kode_transaksi' AND kode_barang='$kode_barang'");
        }
        
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>