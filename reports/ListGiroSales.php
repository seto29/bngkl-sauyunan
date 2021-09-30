<html>
<head>
	<title>Giro Penjualan</title>
</head>
<body>
	<style type="text/css">
	body{
		font-family: sans-serif;
	}
	table{
		margin: 20px auto;
		border-collapse: collapse;
	}
	table th,
	table td{
		border: 1px solid #3c3c3c;
		padding: 3px 8px;
 
	}
	a{
		background: blue;
		color: #fff;
		padding: 8px 10px;
		text-decoration: none;
		border-radius: 2px;
	}
	</style>
 
    <?php
      date_default_timezone_set('Asia/Jakarta');
      $dateFormat = date("Y-m-d");
      header("Content-Type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=Daftar_Giro_penjualan\"$dateFormat\".xls");
      require '../db_connection.php';
      function tanggalF($tanggal)
        {
            $y = $tanggal[0].$tanggal[1].$tanggal[2].$tanggal[3];
            $m = $tanggal[4].$tanggal[5];
            $d = $tanggal[6].$tanggal[7];
            $date = $d ."-". $m ."-". $y;
            return $date;
        }
      function rupiah($angka)
      {
        $hasil_rupiah = "Rp" . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
      }
      $array = array();
      $dateFrom = $_GET['dateFrom'];
      $dateUntil = $_GET['dateUntil'];

      $timestamp = strtotime($dateFrom);
      $time1 = date('Ymd', $timestamp);

      $timestamp = strtotime($dateUntil);
      $time2 = date('Ymd', $timestamp);
      $str = date('d-m-Y', strtotime($dateFrom))." Sampai Dengan ". date('d-m-Y', strtotime($dateUntil));

        $list = mysqli_query($db_conn, "SELECT `penjualan`.`lama_tempo`, `penjualan`.`jatuh_tempo`, `bayar_penjualan_detail`.`kode_transaksi`, `pelanggan`.`kode` AS kode_pelanggan, `pelanggan`.`nama` AS nama_pelanggan, `pelanggan`.`alamat` AS alamat_pelanggan, `pelanggan`.`kota`, `pelanggan`.`telepon`, `sales`.`kode` AS kode_sales, `sales`.`nama` AS nama_sales, `bayar_penjualan_detail`.`harga`, `bayar_penjualan_detail`.`jumlah_giro1`, `bayar_penjualan_detail`.`jumlah_giro2`, `bayar_penjualan_detail`.`jumlah_giro3`, `bayar_penjualan_detail`.`jumlah_potongan`, `bayar_penjualan_detail`.`no_giro1`, `bayar_penjualan_detail`.`bank1`, `bayar_penjualan_detail`.`nilai_giro1`, `bayar_penjualan_detail`.`tanggal_cair1`, `bayar_penjualan_detail`.`cair1`, `bayar_penjualan_detail`.`no_giro2`, `bayar_penjualan_detail`.`bank2`, `bayar_penjualan_detail`.`nilai_giro2`, `bayar_penjualan_detail`.`tanggal_cair2`, `bayar_penjualan_detail`.`cair2`, `bayar_penjualan_detail`.`no_giro3`, `bayar_penjualan_detail`.`bank3`, `bayar_penjualan_detail`.`nilai_giro3`, `bayar_penjualan_detail`.`tanggal_cair3`, `bayar_penjualan_detail`.`cair3` FROM `bayar_penjualan_detail` JOIN `penjualan` ON `penjualan`.`kode_transaksi`=`bayar_penjualan_detail`.`kode_penjualan` JOIN `sales` ON `sales`.`kode`=`penjualan`.`kode_sales` JOIN `pelanggan` ON `pelanggan`.`kode`=`penjualan`.`kode_pelanggan` WHERE penjualan.tanggal_jual>='$time1' AND penjualan.tanggal_jual<='$time2' AND ((no_giro1!='')  OR (no_giro2!='') OR (no_giro3!='')) GROUP BY `bayar_penjualan_detail`.`kode_transaksi` ORDER BY `penjualan`.`jatuh_tempo`");
        $i=1;
        $i = 0;
        $temp = 0;
        while($row = mysqli_fetch_array($list)){
            $date = substr($row['jatuh_tempo'],6,2)."-".substr($row['jatuh_tempo'],4,2)."-".substr($row['jatuh_tempo'],0,4);
            $row['jatuh_tempo']=$date;

            if(isset($row['tanggal_cair1']) && !empty($row['tanggal_cair1'])){
                $date = substr($row['tanggal_cair1'],6,2)."-".substr($row['tanggal_cair1'],4,2)."-".substr($row['tanggal_cair1'],0,4);
                $row['no']=$i;
                $row['tanggal_cair1']=$date;
            }else{
                $row['tanggal_cair1']="";
            }
            if(isset($row['tanggal_cair2']) && !empty($row['tanggal_cair2'])){
                $date = substr($row['tanggal_cair2'],6,2)."-".substr($row['tanggal_cair2'],4,2)."-".substr($row['tanggal_cair2'],0,4);
                $row['no']=$i;
                $row['tanggal_cair2']=$date;
            }else{
                $row['tanggal_cair2']="";
            }
            if(isset($row['tanggal_cair3']) && !empty($row['tanggal_cair3'])){
                $date = substr($row['tanggal_cair3'],6,2)."-".substr($row['tanggal_cair3'],4,2)."-".substr($row['tanggal_cair3'],0,4);
                $row['no']=$i;
                $row['tanggal_cair3']=$date;
            }else{
                $row['tanggal_cair3']="";
            }
            array_push($array, $row);
            
            $i+=1;
        }
	?>
 	<center>
        <h1>Giro Penjualan </h1>
        <b><?php echo $str;?><b>
        
	</center>
  <br/>
  <br/>
        <?php
        $temp="99XXXXX";
        $c = 1;
        $first = true;
        $harga =0;
        $jGiro2 =0;
        $jGiro3 =0;
        $jGiro1 =0;
        $Gharga =0;
        $GjGiro2 =0;
        $GjGiro3 =0;
        $ci = 0;
        $GjGiro1 =0;
        foreach ($array as $value) {
            if($first==false && $temp!=$value['jatuh_tempo'] ){
                ?>
                    
                    <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($harga);?>
                        </td>
                        <td colspan="2" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro1);?>
                        </td>
                        <td colspan="4" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro2);?>
                        </td>
                        <td colspan="4" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro3);?>
                        </td>
                    </tr>
                </table>
        <?php    
        }
        
        $first=false;
            if($temp!=$value['jatuh_tempo']){
                $harga =0;
                $jGiro2 =0;
                $jGiro3 =0;
                $jGiro1 =0;
                $c=1;
                ?>
        
        <table border="1">
            <tr>
                <td style="width:15%">
                <b>
                    No.
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Kd Transaksi
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Kd Sls
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Kd Plg
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Nama
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Lama Tempo
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Harga
                </b>
                </td>
                <td style="width:15%">
                <b>
                    No. Gir1
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Bank1
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Nilai Giro1
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Tgl Cair1
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Cair1
                </b>
                </td>
                <td style="width:15%">
                <b>
                    No. Gir2
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Bank2
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Nilai Giro2
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Tgl Cair2
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Cair2
                </b>
                </td>
                <td style="width:15%">
                <b>
                    No. Gir3
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Bank3
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Nilai Giro3
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Tgl Cair3
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Cair3
                </b>
                </td>
            </tr>
            <tr >
                <td>
                    Tgl Jatuh Tempo
                </td>
                <td colspan="21">
                    <?php echo $value['jatuh_tempo'];?>
                </td>
            </tr>
            <tr>
                <td>
                <b>
                    <?php echo $c;?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_transaksi'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_sales'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_pelanggan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_pelanggan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['lama_tempo'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro1']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair1']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro2']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair2']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro3'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank3'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro3']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair3']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair3'];?>
                </b>
            </td>
        </tr>
        <?php
        
            }else{
                
                ?>
            <tr>
                <td>
                <b>
                    <?php echo $c;?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_transaksi'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_sales'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_pelanggan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_pelanggan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['lama_tempo'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro1']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair1']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair1'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro2']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair2']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair2'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['no_giro3'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['bank3'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['nilai_giro3']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['tanggal_cair3']?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['cair3'];?>
                </b>
            </td>
        </tr>
        <?php
            }
        $temp=$value['jatuh_tempo'];
        
        $harga +=$value['harga'];
        $jGiro2 +=$value['nilai_giro2'];
        $jGiro3 +=$value['nilai_giro3'];
        $jGiro1 +=$value['nilai_giro1'];
        $Gharga +=$value['harga'];
        $GjGiro2 +=$value['nilai_giro2'];
        $GjGiro3 +=$value['nilai_giro3'];
        $GjGiro1 +=$value['nilai_giro1'];
        $c+=1;
        $ci+=1;
        if($ci==count($array)){
            ?>
            <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($harga);?>
                        </td>
                        <td colspan="2" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro1);?>
                        </td>
                        <td colspan="4" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro2);?>
                        </td>
                        <td colspan="4" style="text-align:right">
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro3);?>
                        </td>
                    </tr>
                </table>
            <?php
        }
        }
        ?>
            <table>
        <tr >
            <td colspan="6" style="text-align:right; width:15%">
                Total : 
            </td>
            <td colspan="1" style="text-align:right; width:15%">
                <?php echo rupiah($Gharga);?>
            </td>
            <td colspan="2" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
                <?php echo rupiah($GjGiro1);?>
            </td>
            <td colspan="4" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
                <?php echo rupiah($GjGiro2);?>
            </td>
            <td colspan="4" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
                <?php echo rupiah($GjGiro3);?>
            </td>
        </tr>
    </table>

</body>
</html>