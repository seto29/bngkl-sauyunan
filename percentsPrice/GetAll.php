<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT * FROM `nilai` WHERE kode='persen_jual'");

if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $vals[0]['kode'] = 'jual1';
    $vals[0]['value'] = $all[0]['jual1'];
    $vals[1]['kode'] = 'jual2';
    $vals[1]['value'] = $all[0]['jual2'];
    $vals[2]['kode'] = 'jual3';
    $vals[2]['value'] = $all[0]['jual3'];
    echo json_encode(["success" => 1, "percents" => $vals]);
} else {
    echo json_encode(["success" => 0]);
}
