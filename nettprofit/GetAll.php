<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT sa.id, sa.code, sa.notes, sa.created_at, sa.paid_off, s.name AS sName, e.name as eName FROM sales sa JOIN shops s ON sa.shop_id = s.id JOIN employees e ON sa.created_by = e.id WHERE sa.deleted = 0 AND sa.paid_off = 1 ORDER BY sa.id DESC");

$resOA = array();
$res = array();
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $temp = [];
    foreach($all as $val){
        $sId = $val['id'];
        $salesD = mysqli_query($db_conn, "SELECT sum(qty) AS totQty FROM `salesdetails` WHERE sales_id='$sId' AND product_id!=0 AND deleted=0");
        $sd = mysqli_fetch_all($salesD, MYSQLI_ASSOC);
        
        $salesDP = mysqli_query($db_conn, "SELECT product_id FROM `salesdetails` WHERE sales_id='$sId' AND deleted=0 AND product_id!=0 GROUP BY product_id ");
        $totP = mysqli_num_rows($salesDP);
        
        $totalS = mysqli_query($db_conn, "SELECT sum(unit_price*qty) AS total FROM `salesdetails` WHERE sales_id='$sId'");
        $totS = mysqli_fetch_all($totalS, MYSQLI_ASSOC);
        
        $totalC = mysqli_query($db_conn, "SELECT sum(cogs*qty) AS total FROM `salesdetails` WHERE sales_id='$sId'");
        $totC = mysqli_fetch_all($totalC, MYSQLI_ASSOC);
        
        
        $temp['totQty']+=$sd[0]['totQty'];
        $val['totQty']=$sd[0]['totQty'];
        $temp['totP']+=$totP;
        $val['totP']=$totP;
        $temp['tot']+=$totS[0]['total'];
        $val['tot']=$totS[0]['total'];
        $temp['totC']+=$totC[0]['total'];
        $val['totC']=$totC[0]['total'];
        
        array_push($res, $val);
    }
    array_push($data,$all);
    echo json_encode(["success" => 1, "nettprofit" => $res]);
} else {
    echo json_encode(["success" => 0]);
}
