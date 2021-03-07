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
        
        //generate delivery order number
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
        
        if($sqlGR){
            $lastid = mysqli_insert_id($db_conn);
            for($i=0;$i<count($details);$i++){
                $pID = $details[$i] -> productID;
                $qty = $details[$i] -> qty;
        		if($pID!==0 && $qty!==0){
        	        $sqlGRD = mysqli_query($db_conn, "INSERT INTO deliverydetails(delivery_id,product_id,qty,unit_price, created_at, updated_at)VALUES('$lastid','$pID','$qty',0,'$today','0000-00-00 00:00:00')");
        	        $sqlUpdate = mysqli_query($db_conn, "UPDATE products SET stock = stock-'$qty' WHERE id = '$pID'");
        		}
        		else{
        			echo json_encode(["success" => 0, "msg" =>"Mohon Isi Data"]);
        		}
	        }
	        echo json_encode(["success" => 1, "id"=>$lastid]);
        }else{
            echo json_encode(["success" => 0, "msg" =>"Insert Goods Receipt Gagal"]);
        }
?>