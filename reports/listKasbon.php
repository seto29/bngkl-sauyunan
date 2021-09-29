<html>
<head>
	<title>Report Kasbon</title>
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
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Kansbon\"$str\".xls");
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

        $list = mysqli_query($db_conn, "SELECT * FROM `kasbon` WHERE tanggal>='$time1' AND tanggal<='$time2'");
        $i=1;
        $array = array();
        while($row = mysqli_fetch_array($list)){
            $row['no']=$i;
            array_push($array, $row);
            $i+=1;
        }
	?>
 	<center>
        <h1>Laporan Kasbon</h1>
        <b><?php echo date('d-m-Y', strtotime($dateFrom))." Sampai Dengan ". date('d-m-Y', strtotime($dateUntil));?><b>
        
	</center>
  <br/>
  <br/>
        <?php
        $first = true;
        $gTotal =0;
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
                    Pengguna
                </b>
                </td>
                <td style="width:8%">
                <b>
                    Keterangan
                </b>
                </td>
                <td style="width:8%">
                    <b>
                        Jumlah
                    </b>
                </td>
            </tr>
            
        <?php
        
        $ci = 0;
        $c = 1;
        foreach ($array as $value) {
        ?>
            <tr>
                <td>
                <b>
                    <?php echo $c;?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo tanggalF($value['tanggal']);?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['Keterangan'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo rupiah($value['kredit']);?>
                </b>
            </td>
        </tr>
        <?php
        $gTotal+=$value['kredit'];
        $ci+=1;
        $c+=1;
        if($ci==count($array)){
            ?>
            <tr >
            <td colspan="4" style="text-align:right">
                            Total : 
                        </td>
                        <td colspan="1" style="text-align:right">
                            <?php echo rupiah($gTotal);?>
                        </td>
                    </tr>
                </table>
            <?php
        }
        }
        ?>

</body>
</html>