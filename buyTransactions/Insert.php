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
        $kode = "PB".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `pembelian` WHERE kode_transaksi LIKE '$kode%' ORDER BY kode_transaksi DESC LIMIT 1");
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
            
        $jam = date('ymd');
        $nama_supplier=$obj->nama_supplier;
        $kode_supplier=$obj->kode_supplier;
        $alamat_supplier=$obj->alamat_supplier;
        $kota=$obj->kota;
        $telepon=$obj->telepon;
        $jatuh_tempo= $obj->jatuh_tempo;
        $tanggal_beli= $obj->tanggal_beli;
        $strJT = date("Ymd",strtotime($obj->jatuh_tempo));
        $strTj = date("Ymd",strtotime($obj->tanggal_beli));
        $lama_tempo =  strtotime($obj->jatuh_tempo)-strtotime($obj->tanggal_beli);
        $lama_tempo = ceil(abs($lama_tempo) / 86400);

        $kode_user=$obj->kode_user;
        $nama_user=$obj->nama_user;
        
        foreach ($il as $value) {
            $kd = $value->kode_barang;
            $pn = $value->part_number;
            $merk = $value->merk;
            $nama_barang = $value->nama_barang;
            $harga_beli = $value->harga_beli;
            $qty = $value->qty;
            $satuan = $value->barang->satuan;
            $total_harga_beli = $qty*$harga_beli;

            $kw = substr($kd,0,2);
            $nama_kw = "";
            $kode_barangs = mysqli_query($db_conn, "SELECT * FROM `kode_barang` WHERE kode='$kw'");
            if (mysqli_num_rows($kode_barangs) > 0) {
                $all = mysqli_fetch_all($kode_barangs, MYSQLI_ASSOC);
                $nama_kw = $all[0]['nama'];
            }
            $jam=date("H:i:s");
            $insertSales = mysqli_query($db_conn, "INSERT INTO `pembelian`(`kode_transaksi`, `kode_barang`, `part_number`, `nama_barang`, `merk`, `kode_supplier`, `nama_supplier`, `alamat_supplier`, `kota`, `telepon`, `kode_user`, `nama_user`, `faktur`, `harga_beli`, `qty`, `satuan`, `total_harga_beli`, `tanggal_beli`, `jam`, `jatuh_tempo`, `lama_tempo`) VALUES ('$kode','$kd','$pn','$nama_barang','$merk','$kode_supplier','$nama_supplier','$alamat_supplier','$kota','$telepon','$kode_user','$nama_user','','$harga_beli','$qty','$satuan','$total_harga_beli','$strTj','$jam','$strJT','$lama_tempo')");
        }
        
        if($insertSales){
            $kode2 = "BB".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `bayar_pembelian` WHERE kode_transaksi LIKE '$kode2%' ORDER BY kode_transaksi DESC LIMIT 1");
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
                $kode2.=$strI;
            }else{
                $kode2 .= '001';
            }
            $insertSales = mysqli_query($db_conn, "INSERT INTO `bayar_pembelian`(`kode_transaksi`, `kode_supplier`, `harga`, `jumlah_bayar`, `sisa`) VALUES ('$kode2','$kode_supplier','$total_harga_beli',0,'$total_harga_beli')");
            
            $kode1 = "DB".''.date("ymd");
            $getCode = mysqli_query($db_conn, "SELECT kode_transaksi FROM `bayar_pembelian_detail` WHERE kode_transaksi LIKE '$kode1%' ORDER BY kode_transaksi DESC LIMIT 1");
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
                $kode1.=$strI;
            }else{
                $kode1 .= '001';
            }
            $insertSales = mysqli_query($db_conn, "INSERT INTO `bayar_pembelian_detail`
            (`kode_transaksi`, `kode_transaksi2`, `kode_pembelian`, `kode_supplier`, `nama_supplier`, 
            `alamat_supplier`, `kota`, `telepon`,  
            `kode_user`, `nama_user`, `harga`, `jumlah_bayar`, `jumlah_retur`, 
            `jumlah_giro1`, `jumlah_giro2`, `jumlah_giro3`, `jumlah_potongan`, `sisa`, 
            `tanggal_beli`, `tanggal_bayar`, `jam`, `jatuh_tempo`, `lama_tempo`, 
            `no_giro1`, `bank1`, `nilai_giro1`, `tanggal_cair1`, `cair1`, 
            `no_giro2`, `bank2`, `nilai_giro2`, `tanggal_cair2`, `cair2`, 
            `no_giro3`, `bank3`, `nilai_giro3`, `tanggal_cair3`, `cair3`, 
            `status`) VALUES 
            ('$kode1','$kode2','$kode','$kode_supplier','$nama_supplier',
            '$alamat_supplier','$kota','$telepon',
            '$kode_user','$nama_user','$total_harga_beli',0,0,
            0,0,0,'','$total_harga_beli','$strTj',
            '$strJT','$jam','$strJT','$lama_tempo','',
            '',0,'','Tidak','',
            '',0,'','Tidak','',
            '',0,'','Tidak',''
            )");
            echo json_encode(["success"=>1, "msg"=>"berhasil", "kode"=>$kode]);
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);    
        }
?>