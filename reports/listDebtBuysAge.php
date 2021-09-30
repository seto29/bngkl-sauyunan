<html>
<head>
	<title>Daftar Umur Hutang Pembelian</title>
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
      header("Content-Disposition: attachment; filename=Daftar_Umur_Hutang_Pembelian_\"$dateFormat\".xls");
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
      $age = $_GET['age'];
      $strAge = ' - '.$age.' days';
      $str = "Semua";
      $date1 = date("Y-m-d");
      $date2 = date('Ymd', strtotime($date1. $strAge));
      if($age==60){
            $date1 = date("Y-m-d");
            $date = date('Ymd', strtotime($date1. ' - 30 days'));
            $str = "30 s/d 59 Hari";
        }elseif ($age==90) {
            $str = "60 s/d 89 Hari";
            $date1 = date("Y-m-d");
            $date = date('Ymd', strtotime($date1. ' - 60 days'));
        }elseif ($age==120) {
            $str = "90 s/d 119 Hari";
            $date1 = date("Y-m-d");
            $date = date('Ymd', strtotime($date1. ' - 90 days'));
        }elseif ($age==30) {
            $str = "Lebih Kecil dari 30 Hari";
            $date1 = date("Y-m-d");
            $date =  date('Ymd', strtotime($date1));
        }
        $array = array();
        if ($age==121) {
            $str = "Lebih Besar Dari 120 Hari";
            $date1 = date("Y-m-d");
            $date = date('Ymd', strtotime($date1. ' - 120 days'));
            $list = mysqli_query($db_conn, "SELECT bayar_pembelian.*, bayar_pembelian_detail.kode_transaksi AS kode_pembelian, bayar_pembelian_detail.nama_supplier, bayar_pembelian_detail.jatuh_tempo, pembelian.tanggal_beli, pembelian.kode_user, pembelian.nama_user, bayar_pembelian_detail.tanggal_bayar, bayar_pembelian_detail.jumlah_bayar, pembelian.kode_supplier, pembelian.nama_supplier, pembelian.alamat_supplier FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE bayar_pembelian.sisa!=0 AND pembelian.tanggal_beli<='$date' GROUP BY bayar_pembelian.kode_transaksi ORDER BY pembelian.kode_supplier ASC");
        }elseif ($age==122) {
            $list = mysqli_query($db_conn, "SELECT bayar_pembelian.*, bayar_pembelian_detail.kode_transaksi AS kode_pembelian, bayar_pembelian_detail.nama_supplier, bayar_pembelian_detail.jatuh_tempo, pembelian.tanggal_beli, pembelian.kode_user, pembelian.nama_user, bayar_pembelian_detail.tanggal_bayar, bayar_pembelian_detail.jumlah_bayar, pembelian.kode_supplier, pembelian.nama_supplier, pembelian.alamat_supplier FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE bayar_pembelian.sisa!=0 GROUP BY bayar_pembelian.kode_transaksi ORDER BY pembelian.kode_supplier ASC");
        }else{
            $list = mysqli_query($db_conn, "SELECT bayar_pembelian.*, bayar_pembelian_detail.kode_transaksi AS kode_pembelian, bayar_pembelian_detail.nama_supplier, bayar_pembelian_detail.jatuh_tempo, pembelian.tanggal_beli, pembelian.kode_user, pembelian.nama_user, bayar_pembelian_detail.tanggal_bayar, bayar_pembelian_detail.jumlah_bayar, pembelian.kode_supplier, pembelian.nama_supplier, pembelian.alamat_supplier FROM `bayar_pembelian` JOIN bayar_pembelian_detail ON bayar_pembelian_detail.kode_transaksi2=bayar_pembelian.kode_transaksi JOIN pembelian ON pembelian.kode_transaksi=bayar_pembelian_detail.kode_pembelian WHERE bayar_pembelian.sisa!=0 AND pembelian.tanggal_beli>'$date2' AND pembelian.tanggal_beli<='$date' GROUP BY bayar_pembelian.kode_transaksi ORDER BY pembelian.kode_supplier ASC");
        }
        $i=1;
        while($row = mysqli_fetch_array($list)){
            $row['no']=$i;
            array_push($array, $row);
            $i+=1;
        }
	?>
 	<center>
        <h1>Daftar Umur Hutang Pembelian </h1>
        <b><?php echo $str;?><b>
        
	</center>
  <br/>
  <br/>
        <?php
        $temp="99XXXXX";
        $c = 1;
        $first = true;
        $jbeli =0;
        $jbayar =0;
        $jsisa =0;
        $Gjbeli =0;
        $Gjbayar =0;
        $Gjsisa =0;
        $ci = 0;
        foreach ($array as $value) {
            if($first==false && $temp!=$value['kode_supplier'] ){
                ?>
                    
                    <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jbeli);?>
                        </td>
                        <td colspan="1">
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
            if($temp!=$value['kode_supplier']){
                $jbeli =0;
                $jbayar =0;
                $jsisa =0;
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
                    Tgl Jual
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Kd User
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
                    Hrg Jual
                </b>
                </td>
                <td style="width:15%">
                <b>
                    Tgl Bayar
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
                    Supplier
                </td>
                <td colspan="9">
                    Kd: <?php echo $value['kode_supplier'];?>, NM <?php echo $value['nama_supplier'];?>, AL: <?php echo $value['alamat_supplier'];?>,
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
                    <?php echo $value['kode_pembelian'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['tanggal_beli']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_user'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_user'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['jatuh_tempo']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['tanggal_bayar']);?>
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
                    <?php echo $value['kode_pembelian'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['tanggal_beli']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_user'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_user'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['jatuh_tempo']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['tanggal_bayar']);?>
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
        $temp=$value['kode_supplier'];
        
        $jbeli +=$value['harga'];
        $jbayar +=$value['jumlah_bayar'];
        $jsisa +=$value['sisa'];
        $Gjbeli +=$value['harga'];
        $Gjbayar +=$value['jumlah_bayar'];
        $Gjsisa +=$value['sisa'];
        $c+=1;
        $ci+=1;
        if($ci==count($array)){
            ?>
            <tr >
                        <td colspan="6" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jbeli);?>
                        </td>
                        <td colspan="1">
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
                            <?php echo rupiah($Gjbeli);?>
                        </td>
                        <td colspan="1" style="width:15%">
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