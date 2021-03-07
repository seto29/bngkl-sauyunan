<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//import require file
require '../db_connection.php';

$url = '';
    
    $target_dir = $_POST['targetDir'];
    $target_file = $target_dir . basename($_FILES["myFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["myFile"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $success = 0;
            $status = 400;
            $msg = "File is not an image";
            $uploadOk = 0;
        }
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $success = 0;
        $status = 400;
        $msg = "File is already exist";
        $uploadOk = 0;
    }

    $ext = pathinfo($_FILES["myFile"]["name"], PATHINFO_EXTENSION);
    $rand = md5(uniqid().rand());
    $post_image = $target_dir . $rand.".".$ext;

    if ($uploadOk == 0) {
        $success = 0;
        $status = 400;
        $msg = "Failed";
    } else {
        if (move_uploaded_file($_FILES["myFile"]["tmp_name"], $post_image)) {
            $url = "http://localhost/cdn".substr($target_dir,9).$rand.".".$ext;
            $success = 1;
            $status = 200;
            $msg = "Success";
        } else {
            $success = 0;
            $status = 204;
            $msg = "Systems Failed";
        }
    }

echo json_encode(["success"=>$success, "status"=>$status, "msg"=>$msg, "url"=>$url]);
?>