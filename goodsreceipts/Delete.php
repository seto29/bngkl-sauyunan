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
            $obj = json_decode(json_encode($_POST));
        }
        $id = $obj -> id;
        $getDetails = mysqli_query($db_conn, "SELECT g.*, p.name AS pName, p.stock AS pStock FROM goodsreceiptdetails g JOIN products p ON g.product_id = p.id WHERE g.good_receipt_id ='$id'");
        while($row = mysqli_fetch_assoc($getDetails)){
            $sudahDikurangi = $row['pStock']-$row['qty'];
            $productID = $row['product_id'];
            $grdID = $row['id'];
            $updateStock = mysqli_query($db_conn, "UPDATE products SET stock='$sudahDikurangi' WHERE id='$productID'");
            if($updateStock){
                
            }else{
                
            }
            $sqlDeleteDetails = mysqli_query($db_conn,"UPDATE goodsreceiptdetails SET deleted = 1 WHERE id='$grdID'");
        }
        $sqlDeleteGR = mysqli_query($db_conn, "UPDATE goodsreceipts SET deleted=1 WHERE id='$id'");
        if($sqlDeleteGR){
            echo json_encode(["success" => 1]);
        }else{
            echo json_encode(["success" => 0]);
        }
?>