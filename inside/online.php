<?php
include "../inc/testSession.php";
include "../inc/con_inc.php";

function upd($a){
    $tes = mysqli_query($a,"SELECT u_name FROM users u, online o where u.u_id = o.u_id");
    while($row =$tes->fetch_assoc()){

        $array[] = $row;
    }
    return $array;
}

if(!isset($_POST['getOnline'])){
    print_r (upd($a));/*
    $responseObject->success = false;
    $responseObject->error = "User not found";
    echo json_encode($responseObject);*/
    exit;
}

include "../inc/close_con.php";