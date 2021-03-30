<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require '../db_connection.php';


$menu = mysqli_query($db_conn, "SELECT * FROM `user` WHERE deleted_at IS NULL");
$res = array();
if (mysqli_num_rows($menu) > 0) {
    $all = mysqli_fetch_all($menu, MYSQLI_ASSOC);
    $menu1 = mysqli_query($db_conn, "SELECT * FROM `menu` ORDER BY state1");
    $all1 = mysqli_fetch_all($menu1, MYSQLI_ASSOC);

    foreach ($all as $key1 => $value) {
        $data=$value;
        foreach ($value as $key => $val) {
            if(strpos($key, 'state')!==false){
                $state = substr($key,5);
                if($state<=49){
                    $im = ($all1[$state-1]['main_menu'].' - '.$all1[$state-1]['isi_menu']);
                    $data[$im]=$data['state'.$state];
                }
            }
        }
        array_push($res,$data);
    }
    echo json_encode(["success" => 1, "employees" => $res]);
} else {
    echo json_encode(["success" => 0]);
}
