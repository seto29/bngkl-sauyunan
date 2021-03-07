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
            echo(json_encode($_POST));
            $obj = json_decode(json_encode($_POST));
        }
        

        $res = array();
        $dod = $obj-> dod;
        $sID = $obj-> sID;
        $createdBy = $obj-> createdBy;
        $details = json_decode($obj-> details);
        $payments = json_decode($obj-> payments);
        // $isDelivery = $obj-> isDelivery;
        $paidOff = $obj-> paidOff;
        $notes = $obj-> notes;
        $terminPay = $obj-> terminPay;
        $salesPrice = $obj-> salesPrice;
        
        //sales create
        $today = date("Y-m-d H:i:sa");
        $todayStr = date("my");
        $son = "S".$todayStr;
        $q1 = mysqli_query($db_conn,"SELECT COUNT(id) as counter FROM `sales` WHERE code LIKE '{$son}%' AND deleted=0");
        $id=0;
        while($row=mysqli_fetch_assoc($q1)){
            $id = (int) $row['counter'];
        }
        $id+=1;
        if ($id < 10) {
            $id = (string) $id;
            $id = ("000" . $id);
        }else if ($id >= 10) {
            $id = (string) $id;
            $id = ("00" . $id);
        } else if ($id >= 100) {
            $id = (string) $id;
            $id = ("0" . $id);
        };
        $son .= $id;
        $code = $son;
        $ispo = 0;
        $podt = "0000-00-00 00:00:00";
        if($paidOff===true){
            $ispo=1;
            $podt = $today;
            
        }
        $insertSales = mysqli_query($db_conn,"INSERT INTO `sales`(`code`, `created_by`, `notes`, `shop_id`,`paid_off`,`paid_off_date`) VALUES ('$code','$createdBy','$notes','$sID','$ispo','$podt')");

        $salesId = mysqli_insert_id($db_conn);
        if($insertSales){
            
            for($i=0;$i<count($details);$i++){
                $pID = $details[$i] -> productID;
                $qty = $details[$i] -> qty;
                $uPrice = $details[$i] -> unit_price;
                $cogs = $details[$i] -> cogs;
                $insertSalesDetails = mysqli_query($db_conn,"INSERT INTO `salesdetails`(`sales_id`, `product_id`, `unit_price`, `qty`, `cogs`) VALUES ('$salesId','$pID','$uPrice','$qty','$cogs')");
            }
        }
        
        //generate delivery order number
        $delivID = 0;
        // if($isDelivery==true){
            $today = date("Y-m-d H:i:sa");
            $todayStr = date("my",strtotime($obj->dod));
            $don = "D".$todayStr;
            $q = mysqli_query($db_conn,"SELECT COUNT(id) as counter FROM `deliveries` WHERE delivery_order_number LIKE '{$don}%' AND deleted=0");
            $id=0;
            while($row=mysqli_fetch_assoc($q)){
                $id = (int) $row['counter'];
            }
            $id+=1;
            if ($id < 10) {
                $id = (string) $id;
                $id = ("000" . $id);
            }else if ($id >= 10) {
                $id = (string) $id;
                $id = ("00" . $id);
            } else if ($id >= 100) {
                $id = (string) $id;
                $id = ("0" . $id);
            };
            $don .= $id;
            
            $sqlGR = mysqli_query($db_conn,"INSERT INTO deliveries (created_by, shop_id, delivery_order_number, delivery_order_date, created_at, updated_at) VALUES ('$createdBy', '$sID', '$don', '$dod','$today','0000-00-00 00:00:00')");
            
            $delivID = mysqli_insert_id($db_conn);
            if($sqlGR){
                for($i=0;$i<count($details);$i++){
                    $pID = $details[$i] -> productID;
                    $qty = $details[$i] -> qty;
            		if($pID!==0 && $qty!==0){
            	        $sqlGRD = mysqli_query($db_conn, "INSERT INTO deliverydetails(delivery_id,product_id,qty,unit_price, created_at, updated_at)VALUES('$delivID','$pID','$qty',0,'$today','0000-00-00 00:00:00')");
            	        $sqlUpdate = mysqli_query($db_conn, "UPDATE products SET stock = stock-'$qty' WHERE id = '$pID'");
            		}
    	        }
            }
        // }
        
        //create payment
        $income = 0;
        if($paidOff===false){
            $i = 0;
            foreach($payments as $payment){
                $ipo = 0;
                // if($i==0){
                //     $ipo = 1;
                //     $income = $payment->amount;
                //     $description ="Pembayaran DP penjualan ".$code;
                // }
                
               $sqlPays = mysqli_query($db_conn, "INSERT INTO `payment`(`sales_id`, `amount`, `due_date`, `is_paid_off`, `created_by`, `created_at`, `updated_at`, `deleted`) VALUES ('$salesId','$payment->amount','$payment->due_date','$ipo','$createdBy','$today','0000-00-00 00:00:00',0)");
                $i+=1;
            }
        }else{
            $sqlPays = mysqli_query($db_conn, "INSERT INTO `payment`(`sales_id`, `amount`, `due_date`, `is_paid_off`, `created_by`, `created_at`, `updated_at`, `deleted`) VALUES ('$salesId','$salesPrice','$today',1,'$createdBy','$today','$today',0)");
            $income = $salesPrice;
            $description ="Pembaran penjualan ".$code." (LUNAS)";
        }
        
        //createTransaction
        // $sqlTr = mysqli_query($db_conn, "INSERT INTO `transactions`( `delivery_id`, `good_receipt_id`, `sales_id`, `created_by`, `category_id`, `description`, `debit`, `credit`, `date`, `created_at`, `updated_at`) VALUES ('$delivID',0,'$salesId','$createdBy','1','$description','$income',0,'$today','$today','0000-00-00 00:00:00')");
        
        if($insertSales){
            echo json_encode(["success"=>1, "msg"=>"berhasil","salesId"=>$salesId,"delivId"=>$delivID]);  
        }else{
            echo json_encode(["success"=>0, "msg"=>"gagal"]);  
        }
        
        
?>