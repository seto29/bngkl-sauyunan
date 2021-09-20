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
        $kode = "KV".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `kanvas` WHERE kode_transaksi LIKE '$kode%' ORDER BY kode_transaksi DESC LIMIT 1");
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
            
        $il = json_decode($obj->inputList);
        $tanggal_ambil = date("Ymd");
        $jam_ambil = date("H:i:sa");
        $kode_sales = $obj->kode_sales;
        $nama_sales = $obj->nama_sales;
        $kode_sopir = $obj->kode_sopir;
        $nama_sopir = $obj->nama_sopir;
        $kode_user = $obj->kode_user;
        $nama_user = $obj->nama_user;
        $tujuan = $obj->tujuan;
        
        foreach ($il as $value) {
            $kode_barang = $value->kode_barang;
            $part_number = $value->part_number;
            $barcode = $value->barang->barcode;
            $nama_barang = $value->nama_barang;
            $merk = $value->merk;
            $satuan = $value->barang->satuan;
            $qty_ambil = $value->qty_ambil;
            $harga_ambil = $value->barang->beli;
            $total_harga_ambil = $qty_ambil*$harga_ambil;
        
            $insertSales = mysqli_query($db_conn, "INSERT INTO `kanvas`(`kode_transaksi`, `kode_sales`, `nama_sales`, `kode_sopir`, `nama_sopir`, `kode_user`, `nama_user`, `tujuan`, `kode_barang`, `part_number`, `barcode`, `nama_barang`, `merk`, `satuan`, `qty_ambil`, `harga_ambil`, `total_harga_ambil`, `tanggal_ambil`, `jam_ambil`) VALUES ('$kode','$kode_sales', '$nama_sales', '$kode_sopir', '$nama_sopir', '$kode_user', '$nama_user', '$tujuan', '$kode_barang', '$part_number', '$barcode', '$nama_barang', '$merk', '$satuan', '$qty_ambil', '$harga_ambil', '$total_harga_ambil', '$tanggal_ambil', '$jam_ambil')");
        }
        
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>