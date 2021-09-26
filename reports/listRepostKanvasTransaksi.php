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
        $dateFrom = $_GET['dateFrom'];
        $dateUntil = $_GET['dateUntil'];

        $timestamp = strtotime($dateFrom);
        $time1 = date('Ymd', $timestamp);

        $timestamp = strtotime($dateUntil);
        $time2 = date('Ymd', $timestamp);
        $str = date('d-m-Y', strtotime($dateFrom))." Sampai Dengan ". date('d-m-Y', strtotime($dateUntil));
        // header("Content-Type: application/vnd.ms-excel");
        // header("Content-Disposition: attachment; filename=Laporan_Kanvas_Transaksi\"$str\".xls");
        require '../db_connection.php';
        function rupiah($angka)
        {
            $hasil_rupiah = "Rp" . number_format($angka, 0, ',', '.');
            return $hasil_rupiah;
        }
        function tanggalF($tanggal)
        {
            $y = $tanggal[0].$tanggal[1].$tanggal[2].$tanggal[3];
            $m = $tanggal[4].$tanggal[5];
            $d = $tanggal[6].$tanggal[7];
            $date = $d ."-". $m ."-". $y;
            return $date;
        }

        $list = mysqli_query($db_conn, "SELECT * FROM `penjualan` WHERE kode_kanvas!='' AND tanggal_jual>='$time1' AND tanggal_jual<='$time2' ORDER BY `kode_kanvas` DESC");
        $i=1;
        $array = array();
        while($row = mysqli_fetch_array($list)){
            $row['no']=$i;
            array_push($array, $row);
            $i+=1;
        }
	?>
 	<center>
        <h1>Laporan Kanvas (Transaksi) </h1>
        <b><?php echo date('d-m-Y', strtotime($dateFrom))." Sampai Dengan ". date('d-m-Y', strtotime($dateUntil));?><b>
        
	</center>
  <br/>
  <br/>
        <?php
        $temp="99XXXXX";
        $temp1="99XXXXX";
        $c = 1;
        $first = true;
        $jjual =0;
        $Gjjual =0;
        $jtjual =0;
        $Gjtjual =0;
        $ci = 0;
        foreach ($array as $value) {
            if($first==false && $temp!=$value['kode_kanvas'] && $temp1!=$value['kode_transaksi'] ){
                ?>
                    
                    <tr >
                        <td colspan="11" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jjual);?>
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jtjual);?>
                        </td>
                    </tr>
                </table>
        <?php    
        }
        
        $first=false;
            if($temp!=$value['kode_kanvas'] && $temp1!=$value['kode_transaksi']){
                $jjual =0;
                $jtjual =0;
                $c=1;
                ?>
        
        <table border="1">
            <tr>
                <td style="width:8%">
                <b>
                    No.
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Tanggal
                </b>
                </td>
                <td style="width:8%">
                <b>
                    K. Transaksi
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Kd Brg
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Nama
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Part Number
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Merk
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Sat
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Ambil
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Jual
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Sisa
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Harga
                </b>
                </td>
                <td style="width:8%">
                <b>
                   Total Harga
                </b>
                </td>
            </tr>
            <tr >
                <td>
                    Kode Transaksi:
                </td>
                <td colspan="12s">
                    Kd Kanvas: <?php echo $value['kode_kanvas'];?>, Kd Penjualan:<?php echo $value['kode_transaksi'];?>
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
                    <?php echo tanggalF($value['tanggal_jual']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_transaksi'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_barang'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_barang'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['part_number'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['merk'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['satuan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_ambil'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_jual'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_sisa'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga_jual']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['total_harga_jual']);?>
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
                    <?php echo tanggalF($value['tanggal_jual']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_transaksi'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode_barang'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama_barang'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['part_number'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['merk'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['satuan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_ambil'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_jual'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['qty_sisa'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['harga_jual']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['total_harga_jual']);?>
                </b>
            </td>
        </tr>
        <?php
            }
        $temp=$value['kode_kanvas'];
        $temp1=$value['kode_transaksi'];
        
        $jjual +=$value['harga_jual'];
        $Gjjual +=$value['harga_jual'];
        $jtjual +=$value['total_harga_jual'];
        $Gjtjual +=$value['total_harga_jual'];
        $c+=1;
        $ci+=1;
        if($ci==count($array)){
            ?>
            <tr >
            <td colspan="11" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jjual);?>
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($jtjual);?>
                        </td>
                    </tr>
                </table>
            <?php
        }
        }
        ?>
            <table>
        <tr >
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
            <td colspan="1" style="width:8%">
            </td>
                        <td colspan="1" style="text-align:right; width:8%">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right; width:8%">
                            <?php echo rupiah($Gjjual);?>
                        </td>
                        <td colspan="1" style="text-align:right; width:8%">
                            <?php echo rupiah($Gjtjual);?>
                        </td>
                    </tr>
                </table>

</body>
</html>