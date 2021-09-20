<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';
        $obj = json_decode(file_get_contents("php://input"));
        if(gettype($obj)=="NULL"){
            $obj = json_decode(json_encode($_POST));
        }
        $a = strtotime($obj->dod);
        $tanggal_masuk = date("Ymd", $a);    
        $res = array();
        $don = $obj-> don;
        $details = json_decode($obj-> details);
        $kode_pembelian = $obj-> kode_pembelian;
        $kode_supplier = $obj-> kode_supplier;
        $nama_supplier = $obj-> nama_supplier;
        $alamat_supplier = $obj-> alamat_supplier;
        $kota = $obj-> kota;
        $telepon = $obj-> telepon;
        $kode_user = $obj-> kode_user;
        $nama_user = $obj-> nama_user;

            for($i=0;$i<count($details);$i++){
                $kode_barang = $details[$i] -> kode_barang;
                $part_number = $details[$i] -> part_number;
                $nama_barang = $details[$i] -> nama_barang;
                $merk = $details[$i] -> merk;
                $satuan = $details[$i] -> satuan;
                $qty = $details[$i] -> qty;
        		if($qty!==0){
                    $sqlGR = mysqli_query($db_conn,"INSERT INTO `barang_masuk`(`nomor_surat_jalan`, `kode_pembelian`, `kode_barang`, `part_number`, `nama_barang`, `merk`, `kode_supplier`, `nama_supplier`, `alamat_supplier`, `kota`, `telepon`, `kode_user`, `nama_user`, `qty`, `satuan`, `tanggal_masuk`) VALUES ('$don', '$kode_pembelian', '$kode_barang', '$part_number', '$nama_barang', '$merk', '$kode_supplier', '$nama_supplier', '$alamat_supplier', '$kota', '$telepon', '$kode_user', '$nama_user', '$qty', '$satuan', '$tanggal_masuk')");
        	        $sqlUpdate = mysqli_query($db_conn, "UPDATE barang SET qty = qty+'$qty' WHERE kode = '$kode_barang'");
        	        $sqlUpdate1 = mysqli_query($db_conn, "UPDATE `pembelian` SET `accept`='1' WHERE kode_transaksi='$kode_pembelian'");
        		}
        		else{
        			echo json_encode(["success" => 0, "msg" =>"Mohon Isi Data"]);
        		}
	        }
            echo json_encode(["success" => 1, "msg" =>"Berhasil"]);
?>