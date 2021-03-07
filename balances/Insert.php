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
        $sku = $obj -> sku;
        $cID = $obj->cID;
        $name = $obj->name;
        $cogs = $obj->cogs;
        $price = $obj->price;
        $stock = $obj->stock;

        $insert = mysqli_query($db_conn, "INSERT INTO products (sku, category_id, name, cogs, price, stock) VALUES ('$sku','$cID','$name','$cogs','$price','$stock')");
        if ($insert) {
            echo json_encode(["success" => 1]);
        } else {
            echo json_encode(["success" => 0]);
        }
?>