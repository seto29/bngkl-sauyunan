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
            && isset($obj->alamat) && !empty($obj->alamat)
            ){
            $nama = strtoupper($obj -> nama);
            $init = $nama[0];
            $kode = $nama[0];
            $getCode = mysqli_query($db_conn, "SELECT kode FROM `user` WHERE nama LIKE '$init%' ORDER BY kode DESC LIMIT 1");
            if (mysqli_num_rows($getCode) > 0) {
                $res = mysqli_fetch_all($getCode, MYSQLI_ASSOC);
                $i =(int) substr($res[0]['kode'],3) +1;
                if($i<10){
                    $strI = "000".$i;
                }elseif ($i<100) {
                    $strI = "00".$i;
                }elseif ($i<1000) {
                    $strI = "0".$i;
                }else{
                    $strI = $i;
                }
                $kode.=$strI;
            }else{
                $kode .= '0001';
            }

            $alamat = $obj->alamat;
            $kota = $obj->kota;
            $telepon = $obj->telepon;
            $fax = $obj->fax;
            $login = $obj->login;
            $password = $obj->password;
            
            $insert = mysqli_query($db_conn, "INSERT INTO `user`(`kode`, `nama`,`login`, `alamat`, `kota`, `telepon`, `fax`, `password`) VALUES ('$kode','$nama','$login','$alamat','$kota','$telepon','$fax', '$password')");
            if ($insert) {
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Dimasukkan"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Memasukkan Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>