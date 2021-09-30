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
        $nama = strtoupper($obj -> nama);
        $alamat = $obj->alamat;
            $kota = $obj->kota;
            $telepon = $obj->telepon;
            $fax = $obj->fax;
            $login = $obj->login;
            $password = $obj->password;
            $kode = $obj->kode;
            $state1 = $obj->state1;
            $state2 = $obj->state2;
            $state3 = $obj->state3;
            $state4 = $obj->state4;
            $state5 = $obj->state5;
        if(isset($obj->password) && !empty($obj->password)){
            $password = md5($obj->password);
            $insert = mysqli_query($db_conn, "UPDATE user SET login='$login',nama='$nama',kota='$kota',password='$password',telepon='$telepon',alamat='$alamat',fax='$fax', state1='$state1', state2='$state2', state3='$state3', state4='$state4', state5='$state5'  WHERE kode = '$kode'");
        }else{
            $insert = mysqli_query($db_conn, "UPDATE user SET login='$login',nama='$nama',kota='$kota',telepon='$telepon',alamat='$alamat',fax='$fax', state1='$state1', state2='$state2', state3='$state3', state4='$state4', state5='$state5'  WHERE kode = '$kode'");
        }
        if ($insert) {
            echo json_encode(["success" => 1]);
        } else {
            echo json_encode(["success" => 0]);
        }
?>