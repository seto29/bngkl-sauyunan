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
$kode = strtoupper($obj->kode);
$nama = $obj->nama;
$komisi = $obj->komisi;
$nilai_minimum = $obj->nilai_minimum;

if(isset($kode) && !empty($kode)
    && isset($nama) && !empty($nama)
){
        
    $insert = mysqli_query($db_conn, "INSERT INTO `kode_barang`(`kode`, `nama`, `komisi`, `nilai_minimum`) VALUES ('$kode','$nama','$komisi','$nilai_minimum')");
    if ($insert) {
        echo json_encode(["success" => 1, "msg"=>"Data Berhasil Dimasukkan"]);
    } else {
        $validate = mysqli_query($db_conn, "SELECT * FROM kode_barang WHERE kode='$kode'");
        if (mysqli_num_rows($validate) > 0) {
            echo json_encode(["success" => 0, "msg"=>"Kode Telah Terdaftar, Gagal Memasukkan Data"]);
        } else {
            echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Memasukkan Data"]);
        }
    }
}else{
    echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
}
?>