<html>
<head>
	<title>Daftar Umur Hutang Penjualan</title>
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
      header("Content-Disposition: attachment; filename=Daftar_Pembayaran_Penjualan\"$dateFormat\".xls");
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

        $list = mysqli_query($db_conn, "SELECT bayar_penjualan_detail.kode_transaksi AS id_detail, bayar_penjualan_detail.kode_transaksi2 AS kode_transaksi, bayar_penjualan_detail.kode_penjualan, MAX(bayar_penjualan_detail.jumlah_bayar) jumlah_bayar, bayar_penjualan_detail.jumlah_retur, MAX(jumlah_giro1+jumlah_giro2+jumlah_giro3) AS jumlah_giro_cair, MIN(bayar_penjualan_detail.sisa) sisa, bayar_penjualan.kode_pelanggan, penjualan.nama_pelanggan, penjualan.tanggal_jual, penjualan.jatuh_tempo, bayar_penjualan.harga, penjualan.kode_sales FROM `bayar_penjualan_detail` JOIN bayar_penjualan ON bayar_penjualan.kode_transaksi=bayar_penjualan_detail.kode_transaksi2 JOIN penjualan ON penjualan.kode_transaksi=bayar_penjualan_detail.kode_penjualan WHERE penjualan.tanggal_jual>='$time1' AND penjualan.tanggal_jual<='$time2'  GROUP BY kode_penjualan ORDER BY kode_penjualan DESC, bayar_penjualan_detail.sisa ASC");
        $i=1;
        $i = 0;
        $temp = 0;
        while($row = mysqli_fetch_array($list)){
            $date = substr($row['tanggal_jual'],6,2)."-".substr($row['tanggal_jual'],4,2)."-".substr($row['tanggal_jual'],0,4);
            $row['tanggal_jual']=$date;
            $date = substr($row['jatuh_tempo'],6,2)."-".substr($row['jatuh_tempo'],4,2)."-".substr($row['jatuh_tempo'],0,4);
            $row['no']=$i;
            $row['jatuh_tempo']=$date;
            if($i==0){
                array_push($array, $row);
            }else{
                if($temp!=$row['kode_penjualan']){
                    array_push($array, $row);
                }
            }
            $temp= $row['kode_penjualan'];
            $i+=1;
        }
	?>
 	<center>
        <h1>Pembayaran Penjualan </h1>
        <b><?php echo $str;?><b>
        
	</center>
  <br/>
  <br/>
        <?php
        $temp="99XXXXX";
        $c = 1;
        $first = true;
        $jjual =0;
        $jbayar =0;
        $jsisa =0;
        $jGiro =0;
        $Gjjual =0;
        $Gjbayar =0;
        $Gjsisa =0;
        $ci = 0;
        $GjGiro =0;
        foreach ($array as $value) {
            if($first==false && $temp!=$value['tanggal_jual'] ){
                ?>
                    
                    <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jjual);?>
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro);?>
                            </td>
                        <td colspan="1">
                            <?php echo rupiah($jbayar);?>
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jsisa);?>
                        </td>
                    </tr>
                </table>
        <?php    
        }
        
        $first=false;
            if($temp!=$value['tanggal_jual']){
                $jjual =0;
                $jbayar =0;
                $jsisa =0;
                $jGiro =0;
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
                    Kd Jual
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
                    Jt Tmp
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Harga Jual
                </b>
                </td>
                <td style="width:15%">
                <b>
                Jml Giro Cair
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Jml Bayar
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Sisa
                </b>
                </td>
            </tr>
            <tr >
                <td>
                    Tanggal Jual
                </td>
                <td colspan="9">
                    <?php echo $value['tanggal_jual'];?>
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
                    <?php echo $value['kode_penjualan'];?>
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
                    <?php echo $value['jatuh_tempo'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['jumlah_giro_cair']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['jumlah_bayar']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['sisa']);?>
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
                    <?php echo $value['kode_penjualan'];?>
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
                    <?php echo $value['jatuh_tempo'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['jumlah_giro_cair']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['jumlah_bayar']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['sisa']);?>
                </b>
            </td>
        </tr>
        <?php
            }
        $temp=$value['tanggal_jual'];
        
        $jjual +=$value['harga'];
        $jbayar +=$value['jumlah_bayar'];
        $jsisa +=$value['sisa'];
        $jGiro +=$value['jumlah_giro_cair'];
        $Gjjual +=$value['harga'];
        $Gjbayar +=$value['jumlah_bayar'];
        $Gjsisa +=$value['sisa'];
        $GjGiro +=$value['jumlah_giro_cair'];
        $c+=1;
        $ci+=1;
        if($ci==count($array)){
            ?>
            <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jjual);?>
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jGiro);?>
                            </td>
                        <td colspan="1">
                            <?php echo rupiah($jbayar);?>
                        </td>
                        <td colspan="1">
                            <?php echo rupiah($jsisa);?>
                        </td>
                    </tr>
                </table>
            <?php
        }
        }
        ?>
            <table>
        <tr >
            <td colspan="1" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
            </td>
            <td colspan="1" style="width:15%">
            </td>
                        <td colspan="1" style="text-align:right; width:15%">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right; width:15%">
                            <?php echo rupiah($Gjjual);?>
                        </td>
                        <td colspan="1" style="width:15%">
                            <?php echo rupiah($GjGiro);?>
                            </td>
                        <td colspan="1" style="width:15%">
                            <?php echo rupiah($Gjbayar);?>
                        </td>
                        <td colspan="1" style="width:15%">
                            <?php echo rupiah($Gjsisa);?>
                        </td>
                    </tr>
                </table>

</body>
</html>