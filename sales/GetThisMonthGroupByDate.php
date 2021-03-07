<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';
$dateTo = $_GET['dateTo'];
$dateFrom = $_GET['dateFrom'];
$lastDayThisMonth = date("t",strtotime($dateTo));

// $m1=(int) date("m",strtotime($dateFrom));
$m1=1;
$y1=(int) date("Y",strtotime($dateFrom));

$dt = date("Y-m",strtotime($dateTo)). "-01 00:00:00";
$df = date("Y-m",strtotime($dateFrom)). "-01 00:00:00";
$date1 = new DateTime($df);
$date2 = $date1->diff(new DateTime($dt));

$labels = array();
for($j=0;$j<=$date2->y;$j++){
    if($date2->y==0){
        for($i=0;$i<=11;$i++){
            $a = $m1+$i;
            if($m1+$i<10){
                $dt = "0".$a;
            }else{
                $dt = $a;
            }
            array_push($labels,$dt."-".$y1);
        }
    }else{
        
    }
}

$dates = array();

$menu = mysqli_query($db_conn, "SELECT SUM(payment.amount) AS counted, MONTH(payment.updated_at) AS month FROM sales JOIN payment ON payment.sales_id=sales.id WHERE payment.deleted=0 AND sales.paid_off=1 AND DATE(sales.created_at) BETWEEN '$dateFrom' AND '$dateTo'  GROUP BY MONTH(sales.created_at),YEAR(payment.created_at) ORDER BY payment.updated_at ASC");

$resOA = array();
$res = array();
$i =1;
$arr = array();
while($i<=12){
    array_push($arr,0);
    $i+=1;
}
$max = 0;
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $temp = [];
    foreach($all as $val){
        $tempIndx = (int)$val['month']-1;
        $arr[$tempIndx]=(int)$val['counted'];
        if($max<(int)$val['counted']){
            $max=(int)$val['counted'];
        }
    }
}


echo json_encode(["success" => 1, "sales" => $arr ,"max"=>$max,"labels"=>$labels]);
