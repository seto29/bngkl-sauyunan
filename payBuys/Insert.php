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
            isset($obj->kode_penjualan) && !empty($obj->kode_penjualan)
            && isset($obj->kode_transaksi) && !empty($obj->kode_transaksi)
            ){
                $kode_penjualan = $obj->kode_penjualan;
                $kode_pelanggan = $obj->kode_pelanggan;
                $nama_pelanggan = $obj->nama_pelanggan;
                $alamat_pelanggan = "";
                $kota = "";
                $telepon = "";
                $harga = $obj->harga;
                $sisa = $obj->sisa;
                $kode_transaksi = $obj->kode_transaksi;
                $komisi = $obj->komisi;
                $tanggal_bayar = $obj->tanggal_bayar;
                $jumlah_bayar = $obj->jumlah_bayar;
                $jumlah_retur = $obj->jumlah_retur;
                $no_giro1 = $obj->no_giro1;
                $bank1 = $obj->bank1;
                $nilai_giro1 = $obj->nilai_giro1;

                $tanggal_cair1 = $obj->tanggal_cair1;
                if(isset($obj->tanggal_cair1) && !empty($obj->tanggal_cair1)){
                    $tanggal_cair1 = strtotime($tanggal_cair1);
                    $tanggal_cair1 = date('Ymd', $tanggal_cair1);
                }
                
                $no_giro2 = $obj->no_giro2;
                $bank2 = $obj->bank2;
                $nilai_giro2 = $obj->nilai_giro2;
                $tanggal_cair2 = $obj->tanggal_cair2;
                if(isset($obj->tanggal_cair2) && !empty($obj->tanggal_cair2)){
                    $tanggal_cair2 = strtotime($tanggal_cair2);
                    $tanggal_cair2 = date('Ymd', $tanggal_cair2);
                }
                $no_giro3 = $obj->no_giro3;
                $bank3 = $obj->bank3;
                $nilai_giro3 = $obj->nilai_giro3;
                $tanggal_cair3 = $obj->tanggal_cair3;
                if(isset($obj->tanggal_cair3) && !empty($obj->tanggal_cair3)){
                    $tanggal_cair3 = strtotime($tanggal_cair3);
                    $tanggal_cair3 = date('Ymd', $tanggal_cair3);
                }
                $jumlah_potongan = $obj->jumlah_potongan;
                $insertSales = mysqli_query($db_conn, "UPDATE `bayar_pembelian` SET `jumlah_bayar`=`jumlah_bayar`+'$jumlah_bayar'+'$jumlah_retur'+'$jumlah_potongan',`sisa`='$sisa'-'$jumlah_bayar'-'$jumlah_potongan'-'$jumlah_retur' WHERE kode_transaksi='$kode_transaksi'");
                
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
                ('$kode1','$kode_transaksi','$kode_penjualan','$kode_pelanggan','$nama_pelanggan',
                '$alamat_pelanggan','$kota','$telepon',
                '','','$harga','$jumlah_bayar','$jumlah_retur',
                '','','','$jumlah_potongan','$sisa'-'$jumlah_bayar'-'$jumlah_potongan'-'$jumlah_retur','',
                '','','','','$no_giro1',
                '$bank1','$nilai_giro1','$tanggal_cair1','Tidak','$no_giro2',
                '$bank2','$nilai_giro2','$tanggal_cair2','Tidak','$no_giro3',
                '$bank3','$nilai_giro3','$tanggal_cair3','Tidak','')");
            
            if ($insertSales) {
                echo json_encode(["success" => 1, "msg"=>"Data Berhasil Dimasukkan"]);
            } else {
                echo json_encode(["success" => 0, "msg"=>"Kesalah Sistem, Gagal Memasukkan Data"]);
            }
        }else{
            echo json_encode(["success" => 0, "msg"=>"Mohon Lengkapi Data Wajib"]);
        }

?>