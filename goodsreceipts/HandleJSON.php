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
        $don = $obj-> don;
        $dod = $obj-> dod;
        $sID = $obj-> sID;
        $createdBy = $obj-> createdBy;
        $details = json_decode($obj-> details);
        $sqlGR = mysqli_query($db_conn,"INSERT INTO goodsreceipts (created_by, supplier_id, delivery_order_number, delivery_order_date) VALUES ('$createdBy', '$sID', '$don', '$dod')");
        if($sqlGR){
            $lastid = mysqli_insert_id($db_conn);
            for($i=0;$i<count($details);$i++){
                $pID = $details[$i] -> productID;
                $qty = $details[$i] -> qty;
        		if($pID!==0 && $qty!==0){
        	        $sqlGRD = mysqli_query($db_conn, "INSERT INTO goodsreceiptdetails(good_receipt_id,product_id,qty,unit_price)VALUES('$lastid','$pID','$qty',0)");
        	        $sqlUpdate = mysqli_query($db_conn, "UPDATE products SET stock = stock+'$qty' WHERE id = '$pID'");
        		}
        		else{
        			echo json_encode(["success" => 0, "msg" =>"Mohon Isi Data"]);
        		}
	        }
	        echo json_encode(["success" => 1]);
        }else{
            echo json_encode(["success" => 0, "msg" =>"Insert Goods Receipt Gagal"]);
        }
?>