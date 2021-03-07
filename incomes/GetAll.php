<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT t.*, e.name AS eName, c.id AS cID, c.name AS cName FROM transactions t JOIN employees e ON t.created_by = e.id JOIN income_categories c ON t.category_id = c.id WHERE t.credit = 0 AND t.deleted = 0 ORDER BY t.id DESC");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    echo json_encode(["success" => 1, "incomes" => $all]);
} else {
    echo json_encode(["success" => 0]);
}
