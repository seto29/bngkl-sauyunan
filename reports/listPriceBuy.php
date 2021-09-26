<html>
<head>
	<title>Daftar Harga Pembelian</title>
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
      header("Content-Disposition: attachment; filename=Daftar_Harga_Pembelian_\"$dateFormat\".xls");
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

      $array = array();
      $list = mysqli_query($db_conn, "SELECT kode,part_number,nama,merk,beli FROM `barang` WHERE deleted_at IS NULL");
      $i=1;
        while($row = mysqli_fetch_array($list)){
            $row['no']=$i;
            array_push($array, $row);
            $i+=1;
        }
	?>
 	<center>
        <h1>Daftar Harga Pembelian </h1>
        <b>Tanggal: <?php echo date("d-m-Y");?><b>
        
	</center>
  <br/>
  <br/>
      <table border="1">
      <tr>
        <td>
          <b>
            No.
          </b>
        </td>
        <td>
          <b>
            Kd. Brg
          </b>
        </td>
        <td>
          <b>
            Nama Barang
          </b>
        </td>
        <td>
          <b>
            Part Number
          </b>
        </td>
        <td>
          <b>
            Merk
          </b>
        </td>
        <td>
          <b>
            Harga
          </b>
        </td>
      </tr>
        <?php
        foreach ($array as $value) {
        ?>
            <tr>
                <td>
                <b>
                    <?php echo $value['no'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['kode'];?>
                </b>
            </td>
            <td>
                <b>
                    <?php echo $value['nama'];?>
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
                    <?php echo rupiah($value['beli']);?>
                </b>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>

</body>
</html>