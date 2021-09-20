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
        $kode = "KP".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode FROM `kasbon` WHERE kode LIKE '$kode%' ORDER BY kode DESC LIMIT 1");
            if (mysqli_num_rows($getCode) > 0) {
                $res = mysqli_fetch_all($getCode, MYSQLI_ASSOC);
                $i =(int) substr($res[0]['kode'],8) +1;
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
        $strTj = date("Ymd");
        $jam = date("H:i:sa");
        $nama = $obj->nama;
        $kredit = $obj->kredit;
        $debit = $obj->debit;
        $Keterangan = $obj->Keterangan;

        $insertSales = mysqli_query($db_conn, "INSERT INTO `kasbon`(`kode`, `tanggal`, `jam`, `nama`, `kredit`, `debit`, `Keterangan`) VALUES ('$kode','$strTj','$jam','$nama','$kredit','$debit','$Keterangan')");
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>