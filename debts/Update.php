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
        $kode = $obj->kode;
        $debit = $obj->debit;

        $insertSales = mysqli_query($db_conn, "UPDATE `kasbon` SET debit='$debit' WHERE kode='$kode'");
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>