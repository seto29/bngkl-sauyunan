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
            isset($obj->nama) && !empty($obj->nama)
            && isset($obj->kode) && !empty($obj->kode)
            && isset($obj->alamat) && !empty($obj->alamat)
            ){
            $nama = strtoupper($obj -> nama);
            $kode = $obj->kode;
            $alamat = $obj->alamat;
            $kota = $obj->kota;
            $telepon = $obj->telepon;
            $fax = $obj->fax;
            
            $insert = mysqli_query($db_conn,"UPDATE `sopir` SET `nama`='$nama',`alamat`='$alamat',`kota`='$kota',`telepon`='$telepon',`fax`='$fax' WHERE `kode`='$kode'");
            if ($insert) {
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Diubah"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Memasukkan Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>