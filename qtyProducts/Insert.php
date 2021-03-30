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
            isset($obj->kode_barang) && !empty($obj->kode_barang)
            && isset($obj->qty_edit) && !empty($obj->qty_edit)
            && isset($obj->alasan) && !empty($obj->alasan)
            ){
            $kode = "EQ".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `edit_qty` WHERE kode_transaksi LIKE '$kode%' ORDER BY kode_transaksi DESC LIMIT 1");
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
            $kode_barang = $obj->kode_barang;
            $nama_barang = $obj->nama_barang;
            $part_number = $obj->part_number;
            $merk = $obj->merk;
            $kode_user = $obj->kode_user;
            $nama_user = $obj->nama_user;
            $qty_asal = $obj->qty_asal;
            $qty_edit = $obj->qty_edit;
            $alasan = $obj->alasan;
            $tanggal_edit = date("Ymd");
            $jam = date("H:i:s");
            
            $insert = mysqli_query($db_conn, "INSERT INTO `edit_qty`(`kode_transaksi`, `kode_barang`, `nama_barang`, `part_number`, `merk`, `kode_user`, `nama_user`, `qty_asal`, `qty_edit`, `tanggal_edit`, `jam`, `alasan`) VALUES ('$kode', '$kode_barang', '$nama_barang', '$part_number', '$merk', '$kode_user', '$nama_user', '$qty_asal', '$qty_edit', '$tanggal_edit', '$jam', '$alasan')");
            if ($insert) {
                $insert = mysqli_query($db_conn, "UPDATE `barang` SET `qty`='$qty_edit' WHERE kode='$kode_barang' ");
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Dimasukkan"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalahan Sistem, Gagal Memasukkan Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>