<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$id = $_POST['id'];
$by = $_POST['by'];
$salesID = $_POST['salesID'];
$code = $_POST['code'];
$amount = $_POST['amount'];

$desc = "Pembayaran Nota No. ".$code;
$menu = mysqli_query($db_conn, "UPDATE payment SET is_paid_off=1, updated_at=NOW(), created_by='$by' WHERE id='$id'");
$insertTrx = mysqli_query($db_conn, "INSERT INTO transactions (sales_id, created_by, description, debit, date) VALUES ('$salesID','$by', '$desc', '$amount', NOW())");
$checkSalesID = mysqli_query($db_conn, "SELECT sales_id FROM payment WHERE sales_id='$salesID' AND is_paid_off = 0 LIMIT 1");
if($menu){
    if (mysqli_num_rows($checkSalesID) == 0) {
    $sqlUpdateSales = mysqli_query($db_conn, "UPDATE sales SET paid_off=1, paid_off_date=NOW() WHERE id='$salesID'");
    }
    echo json_encode(["success" => 1,$id,$by,$salesID]);
}else{
    echo json_encode(["success" => 0]);
}

if ($menu) {
    
} else {
    
}
