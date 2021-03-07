<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT e.id, e.role_id, r.name AS rName, e.name, e.email FROM employees e JOIN roles r ON e.role_id = r.id WHERE e.deleted =0 AND e.id!=0 ORDER BY e.id DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "employees" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
