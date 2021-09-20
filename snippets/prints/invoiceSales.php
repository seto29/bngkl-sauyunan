<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="print_style.css">

    <!-- Load paper.css for happy printing -->
    <!-- <link rel="stylesheet" href="paper.css"> -->
    <link rel="stylesheet" href="normalize.css">

    <script>
        /*window.onload = function () {
          window.print();
           window.top.close();

        }*/
    </script>
    <style>
        body{
            margin: auto;
            justify-content: space-between;
            background-color: lightgray;
        }
        .row {
            display: flex;
            flex-flow: row wrap;
            margin-left: auto;
            margin-right: auto
            }
        .column {
            float: left;
            padding: 5px;
        }

        /* Clear floats after image containers */
        .row::after {
            content: "";
            clear: both;
            display: table;
        }

        table tbody tr td {
            padding: 2px !important;
            line-height: 1.35 !important;
        }

        @media print {
            .box-body {
                margin-top: 10px !important;
                margin-bottom: 10px;
            }
        }
        table {
        box-sizing: border-box;
        max-width: 100%;
        overflow-x: auto;
        width: 100%
        }
        @media only screen and (max-width:480px) {
        table thead tr th {
            padding: 2%
        }
        table tbody tr td {
            padding: 2%
        }
        }
        table thead tr th {
        line-height: 1.5;
        padding: 8px;
        text-align: left;
        vertical-align: bottom
        }
        table tbody tr td {
        line-height: 1.5;
        padding: 8px;
        vertical-align: top
        }
        table.table-hover tbody tr:hover {
        color: #0071de
        }
        table.table-alternating tbody tr:nth-of-type(even) {
        color: #82807c
        }
        @media screen {
            body { background: #e0e0e0 }
            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                margin: 5mm;
            }
        }
        /** Padding area **/
        .sheet.padding-10mm { padding: 5mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }
        .sheet {
        margin: 0;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
        }
    </style>
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body style="width: 9in; height: 5in;padding-right:1cm">

<!-- Each sheet element should have the class "sheet" -->
<!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
<section class="sheet">
        <?php
            require './db_connection.php';
            function tgl_indo($tanggal){
                $bulan = array (
                    1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
                $pecahkan = explode('-', $tanggal);
                
                // variabel pecahkan 0 = tanggal
                // variabel pecahkan 1 = bulan
                // variabel pecahkan 2 = tahun
             
                return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
            }

            function rupiah($angka){

                $hasil_rupiah = "Rp. " . number_format($angka,0,',','.');
                return $hasil_rupiah;
             
            }

            function paidValidate($angka){

                if($angka==1 || $angka=='1'){
                    return "LUNAS";
                }else{
                    return "BELUM LUNAS";
                }
             
            }
            $id = $_GET['id'];
            
            $transaksi = mysqli_query($db_conn, "SELECT kode_transaksi, kode_barang, part_number, nama_barang, merk, nama_pelanggan, alamat_pelanggan, kota, telepon, nama_sales, harga_jual,qty, total_harga_jual, tanggal_jual FROM penjualan WHERE kode_transaksi='$id'");
            $code = "";
            $date = "";
            $sname = "";
            $data = "";
            while($row=mysqli_fetch_assoc($transaksi)){
                $data = $row;
                $code = $row['kode_transaksi'];
                $sname = $row['nama_sales'];
                $date = tgl_indo(date("Y-m-d", strtotime($row['tanggal_jual'])));
            }
        ?>
    <div   style="display: flex;padding: 5px 10px 0 10px;">
        <div style="width: 100%;padding-right: 10px;" class="col-md-16">
            <div class="row">
            
            <div  style="width: 100%;margin-top: 45px;text-align: center;">
            <h3>
            Faktur Penjualan
            </h3>
            </div>
            </div>
            <div class="row">
                <div  style="width: 50%;">
                    <div>
                        <b style="font-size: 15px;">No. Faktur : <?php echo $code;?></b>
                        <p style="font-size: 15px;">Tanggal : <?php echo $date;?></p>
                        <p style="font-size: 15px;">Sales : <?php echo $sname;?></p>
                    </div>
                </div>
                <div  style="width: 50%;text-align:end;">
                    <div>
                        <b style="font-size: 15px;">Pembeli : <?php echo $data['nama_pelanggan'];?></b>
                        <p style="font-size: 15px;">Alamat : <?php echo $data['alamat_pelanggan'];?>, <?php echo $data['kota'];?></p>
                        <p style="font-size: 15px;">Telepon : <?php echo $data['telepon'];?></p>
                    </div>
                </div>
            </div>
            <br>
            <table width="100%" >
                <tr>
                    <th style="border: 1px solid black;" class="text-left">
                        No.
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Jumlah
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Kode Brg.
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Part Number
                    </th>
                    <th style="border: 1px solid black;" class="text-left" colspan="4">
                       Nama Barang
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Merk
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Harga Satuan
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Total
                    </th>
                </tr>
                <tbody>
                <?php
                    $transaksi = mysqli_query($db_conn, "SELECT kode_transaksi, kode_barang, part_number, nama_barang, merk, nama_pelanggan, alamat_pelanggan, kota, telepon, nama_sales, harga_jual,qty, total_harga_jual, tanggal_jual FROM penjualan WHERE kode_transaksi='$id'");
                    $tot = 0;
                        $totQty=0;
                    $i = 1;
                    while($row=mysqli_fetch_assoc($transaksi)){
                        echo '<tr><td style="border: 1px solid black;" class="text-left">'.$i.'</td><td style="border: 1px solid black;" class="text-left">'.$row['qty'].'</td><td style="border: 1px solid black;" class="text-left">'.$row['kode_barang'].'</td><td style="border: 1px solid black;" class="text-left">'.$row['part_number'].'</td><td style="border: 1px solid black;"  colspan="4">'.$row['nama_barang'].'</td><td style="border: 1px solid black;" class="text-left">'.$row['merk'].'</td><td style="border: 1px solid black;" class="text-left">'.rupiah($row['harga_jual']).'</td><td style="border: 1px solid black;" class="text-left">'.rupiah($row['qty']*$row['harga_jual']).'</td></tr>';
                        $tot +=$row['qty']*$row['harga_jual'];
                        $i+=1;
                        $totQty += $row['qty'];
                    }
                    ?>
                    <tr>
                        <td colspan="9" style="text-align: left;"> </td>
                        <td colspan="1" style="text-align: left;"><b>Jumlah Jenis Barang</b></td>
                        <td style="border: 1px solid black;"><b><?php echo $i-1; ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: left;"> </td>
                        <td colspan="1" style="text-align: left;"><b>Jumlah Barang</b></td>
                        <td style="border: 1px solid black;"><b><?php echo $totQty; ?></b></td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: left;"> </td>
                        <td colspan="1" style="text-align: left;"><b>Jumlah Rp.</b></td>
                        <td style="border: 1px solid black;"><b><?php echo rupiah($tot); ?></b></td>
                    </tr>
                
                </tbody>
            </table>
            <br/>
            <br/>
        </div>

    </div>

</section>
<script language="javascript" type="text/javascript">
window.print();
</script>
</body>
</html>
