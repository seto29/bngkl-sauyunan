<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT p.id, p.sales_id, p.amount, p.due_date, p.created_by, s.code, e.name as eName FROM payment p JOIN sales s ON p.sales_id = s.id JOIN employees e ON p.created_by = e.id  WHERE p.is_paid_off = 1 AND p.deleted = 0 ORDER BY p.id DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "payments" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
