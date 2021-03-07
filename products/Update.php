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
        $sku = $obj -> sku;
        $cID = $obj->cID;
        $name = $obj->name;
        $cogs = $obj->cogs;
        $price = $obj->price;
        $stock = $obj->stock;
        $insert = mysqli_query($db_conn, "UPDATE products SET sku = '$sku', category_id='$cID', name='$name', cogs='$cogs', price='$price', stock='$stock', updated_at = NOW()  WHERE id = '$id'");
        if ($insert) {
            echo json_encode(["success" => 1]);
        } else {
            echo json_encode(["success" => 0]);
        }
?>