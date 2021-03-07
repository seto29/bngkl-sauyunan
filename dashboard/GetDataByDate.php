<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';

$dateTo = $_GET['dateTo'];
$dateFrom = $_GET['dateFrom'];

$reminder = mysqli_query($db_conn, "SELECT COUNT(id) AS reminder FROM products WHERE stock < 12 AND deleted =0 ORDER BY stock DESC, id");
$products = mysqli_query($db_conn, "SELECT COUNT(id) AS countProducts FROM products WHERE deleted = 0 AND DATE(products.created_at) BETWEEN '$dateFrom' AND '$dateTo'");
$sales = mysqli_query($db_conn, "SELECT SUM(qty) AS qty FROM salesdetails WHERE deleted = 0 AND DATE(salesdetails.created_at) BETWEEN '$dateFrom' AND '$dateTo'");
$shops = mysqli_query($db_conn, "SELECT s.name AS shop, SUM(sd.qty*sd.unit_price) AS total FROM salesdetails sd JOIN sales sa ON sd.sales_id = sa.id JOIN shops s ON sa.shop_id = s.id WHERE s.deleted = 0 AND DATE(s.created_at) BETWEEN '$dateFrom' AND '$dateTo' GROUP BY s.name ORDER BY total DESC LIMIT 1");
$laku = mysqli_query($db_conn, "SELECT p.name AS name, SUM(sd.qty) AS qty FROM salesdetails sd JOIN products p ON sd.product_id = p.id WHERE p.deleted = 0 AND DATE(sd.created_at) BETWEEN '$dateFrom' AND '$dateTo' GROUP BY p.name ORDER BY qty DESC LIMIT 1");
$galaku = mysqli_query($db_conn, "select p.name, SUM(s.qty) as sale from products p left join salesdetails s on p.id = s.product_id WHERE DATE(s.created_at) BETWEEN '$dateFrom' AND '$dateTo' group by p.name order by sale ASC LIMIT 1");
$deadline = mysqli_query($db_conn, "SELECT COUNT(id) AS deadline FROM payment WHERE is_paid_off = 0 AND due_date<'$dateTo'");
$accReceivable = mysqli_query($db_conn, "SELECT SUM(amount) as ar FROM payment WHERE is_paid_off =  0 AND DATE(due_date) BETWEEN '$dateFrom' AND '$dateTo'");



if (mysqli_num_rows($reminder) > 0) {
    $first = mysqli_fetch_all($products, MYSQLI_ASSOC);
    $second = mysqli_fetch_all($sales, MYSQLI_ASSOC);
    if($second[0]['qty']==null){
        $second[0]['qty']="0";
    }
    $third = mysqli_fetch_all($shops, MYSQLI_ASSOC);
    if($third[0]==null){
        $third[0]['shop']="Tidak Ada";
        $third[0]['total']="0";
    }
    $fourth = mysqli_fetch_all($reminder, MYSQLI_ASSOC);
    $fifth = mysqli_fetch_all($laku, MYSQLI_ASSOC);
    if($fifth[0]==null){
        $fifth[0]['name']="Tidak Ada";
        $fifth[0]['qty']="0";
    }
    $sixth = mysqli_fetch_all($galaku, MYSQLI_ASSOC);
    if($sixth[0]==null){
        $sixth[0]['name']="Tidak Ada";
        $sixth[0]['qty']="0";
    }
    $seventh = mysqli_fetch_all($deadline, MYSQLI_ASSOC);
    $eight = mysqli_fetch_all($accReceivable, MYSQLI_ASSOC);
    if($eight[0]['ar']==null){
        $eight[0]['ar']="0";
    }
    
    echo json_encode(["success" => 1, $first, $second,$third, $fourth, $fifth, $sixth,$seventh, $eight]);
} else {
    echo json_encode(["success" => 0]);
}
