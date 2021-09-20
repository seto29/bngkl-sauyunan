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
        $kode = "RJ".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `retur_penjualan_detail` WHERE kode_transaksi LIKE '$kode%' ORDER BY kode_transaksi DESC LIMIT 1");
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
        $nama_pelanggan=$obj->nama_pelanggan;
        $kode_pelanggan=$obj->kode_pelanggan;
        $alamat_pelanggan=$obj->alamat_pelanggan;
        $kota=$obj->kota;
        $telepon=$obj->telepon;
        $kode_sales=$obj->kode_sales;
        $jatuh_tempo= $obj->jatuh_tempo;
        $tanggal_retur= $obj->tanggal_retur;
        $strJT = date("Ymd",strtotime($obj->jatuh_tempo));
        $strTj = date("Ymd",strtotime($obj->tanggal_retur));
        $jam = date("H:i:s");
        $lama_tempo =  strtotime($obj->jatuh_tempo)-strtotime($obj->tanggal_retur);
        $lama_tempo = ceil(abs($lama_tempo) / 86400);

        $nama_sales="";
        $sales = mysqli_query($db_conn, "SELECT nama FROM `sales` WHERE kode='$kode_sales'");
        if (mysqli_num_rows($sales) > 0) {
            $all = mysqli_fetch_all($sales, MYSQLI_ASSOC);
            $nama_sales=$all[0]['nama'];
        }
        $kode_user=$obj->kode_user;
        $nama_user=$obj->nama_user;
        $total=0;
        
        foreach ($il as $value) {
            $kd = $value->kode_barang;
            $pn = $value->part_number;
            $merk = $value->merk;
            $nama_barang = $value->nama_barang;
            $harga_jual = $value->harga_jual;
            $qty = $value->qty;
            $satuan = $value->barang->satuan;
            $total_harga_jual = $qty*$harga_jual;
            $total += $total_harga_jual;

            $kw = substr($kd,0,2);
            $nama_kw = "";
            $komisi = 0;
            $kode_barangs = mysqli_query($db_conn, "SELECT * FROM `kode_barang` WHERE kode='$kw'");
            if (mysqli_num_rows($kode_barangs) > 0) {
                $all = mysqli_fetch_all($kode_barangs, MYSQLI_ASSOC);
                $nama_kw = $all[0]['nama'];
                $komisi = $all[0]['komisi'];
            }
            $komisi = ($total_harga_jual*$komisi)/100;

            $insertSales = mysqli_query($db_conn, "INSERT INTO `retur_penjualan_detail`(`kode_transaksi`, `kode_barang`, `part_number`, `nama_barang`, `merk`, `kode_pelanggan`, `nama_pelanggan`, `alamat_pelanggan`, `kota`, `telepon`, `kode_sales`, `nama_sales`, `kode_user`, `nama_user`, `harga_jual`, `qty`, `satuan`, `total_harga_jual`, `tanggal_retur`, `jam`, `komisi`) VALUES ('$kode','$kd','$pn','$nama_barang','$merk','$kode_pelanggan','$nama_pelanggan','$alamat_pelanggan','$kota','$telepon','$kode_sales','$nama_sales','$kode_user','$nama_user','$harga_jual','$qty','$satuan','$total_harga_jual','$strTj','$jam','$komisi')");
        }
        
        $retur_penjualan = mysqli_query($db_conn, "SELECT * FROM `retur_penjualan` WHERE kode_pelanggan='$kode_pelanggan'");
        if (mysqli_num_rows($retur_penjualan) > 0) {
            $insertSales = mysqli_query($db_conn, "UPDATE `retur_penjualan` SET `nilai`=`nilai`+'$total', `sisa`=`sisa`+'$total' WHERE `kode_pelanggan`='$kode_pelanggan'");
        }else{
            $insertSales = mysqli_query($db_conn, "INSERT INTO `retur_penjualan`(`kode_pelanggan`, `nilai`, `ambil`, `sisa`) VALUES ('$kode_pelanggan','$total',0,'$total')");
        }
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil"]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>