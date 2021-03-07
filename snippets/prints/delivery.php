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
            
            $transaksi = mysqli_query($db_conn, "SELECT deliveries.delivery_order_number, deliveries.delivery_order_date, shops.name, shops.address, shops.phone FROM `deliveries` JOIN shops ON shops.id=deliveries.shop_id WHERE deliveries.id='$id' AND deliveries.deleted!=1");
            $delivery_order_number = "";
            $date = "";
            $sname = "";
            while($row=mysqli_fetch_assoc($transaksi)){
                $delivery_order_number = $row['delivery_order_number'];
                $sname = $row['name'] .", ".$row['address']. ", ".$row['phone'];
                $date = tgl_indo(date("Y-m-d", strtotime($row['delivery_order_date'])));
            }
        ?>
    <div   style="display: flex;padding: 5px 10px 0 10px;">
        <div style="width: 100%;padding-right: 10px;" class="col-md-16">
            <div class="row">
                <div style="width: 25%;">
                    <h1 style="margin-top: 65px;font-size: 40px; padding-left: 10px;">JOPEX</h1>
                </div>
                <div style="width: 50%;">
                    <h5 style="font-size: 20px;margin-bottom: 15px;margin-top: 45px;">jopex.id</h5>

                    <p style="font-size: 15px;margin: 0;padding: 0;">Panorama Blok D2 No.18-19</p>

                    <p style="font-size: 15px;margin: 0;padding-top: 0px;;">Purwakarta, Jawa Barat</p>

                    <p style="font-size: 15px;margin: 0;padding-top: 0px;;">082216772829</p>
                    <p style="font-size: 15px;margin: 0;padding-top: 0px;;">www.jopex.id</p>
                    <br>
                </div>
                <div  style="width: 25%;">
                    <div class="" style="margin-top: 45px;text-align: end;">
                        <b style="font-size: 15px;margin: 0;padding: 0; margin-bottom: 15px"><?php echo $delivery_order_number;?></b>
                        <p style="font-size: 15px;padding: 0; margin-top:10px"><?php echo $date;?></p>
                        <p style="font-size: 15px;margin: 0;padding-top: 0px;;">Kpd yth: <?php echo $sname;?></p>
                    </div>
                </div>
                
                <div style="width: 100%;">
                    <h1 style=" text-align:center;">Surat Jalan</h1>
                </div>
            </div>
            <br>
            <table width="100%" >
                <tr>
                    <th style="border: 1px solid black;" class="text-left">
                        No.
                    </th>
                    <th style="border: 1px solid black;" class="text-left" colspan="4">
                       Nama Barang
                    </th>
                    <th style="border: 1px solid black;" class="text-left">
                        Jumlah
                    </th>
                </tr>
                <tbody>
                <?php
                    $transaksi = mysqli_query($db_conn, "SELECT deliverydetails.qty, products.name FROM `deliverydetails` JOIN products ON products.id=deliverydetails.product_id WHERE deliverydetails.delivery_id='$id' AND deliverydetails.deleted!=1");
                    $tot = 0;
                    $i = 1;
                    while($row=mysqli_fetch_assoc($transaksi)){
                        echo '<tr><td style="border: 1px solid black;" class="text-left">'.$i.'</td><td style="border: 1px solid black;"  colspan="4">'.$row['name'].'</td><td style="border: 1px solid black;" class="text-left">'.$row['qty'].'</td></tr>';
                        $i+=1;
                    }
                    ?>
                
                </tbody>
            </table>
            <br >
            <table  width="94%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="20%" rowspan="3" align="center" valign="top"><h4 style="margin-bottom: 0;text-align:center">Pengirimin</h4></td>
                    <td width="60%" rowspan="3" valign="top"><strong class="asd"> &nbsp;<br></strong></td>
                    <td width="20%" valign="top"><h4 style="margin-bottom: 0;text-align:center">Penerima
                    </h4></td>
                </tr>

            </table>
            <table style="margin-top:2cm;" width="94%" border="0" cellspacing="0" cellpadding="0">
                <tr style="align">
                    <td width="20%" rowspan="3" align="center" valign="top"><h4 style="margin-bottom: 0;text-align:center">
                        <span style="text-decoration: dashed; padding-left: 100%;color: #000; border-bottom: 1px solid black;"></span>
                    </h4></td>
                    <td width="60%" rowspan="3" valign="top"><strong class="asd"> &nbsp;<br></strong></td>
                    <td width="20%" valign="top"><h4 style="margin-bottom: 0;text-align:center">
                        <span style="text-decoration: dashed; padding-left: 100%;color: #000; border-bottom: 1px solid black;"></span>
                    </h4></td>
                </tr>

            </table>
        </div>

    </div>

</section>
<script language="javascript" type="text/javascript">
window.print();
</script>
</body>
</html>
