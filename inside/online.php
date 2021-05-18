<?php

include "../inc/con_inc.php";
$responseObject = new stdClass();
function upd($a, $id){
    $arra=[];
    $tes = mysqli_query($a,"SELECT u.u_name, u.u_id FROM users u, online o where u.u_id = o.u_id and u.u_id != '$id'");

    while($row =$tes->fetch_assoc()){
        $arra[] = $row;
    }
    return $arra;
}

function feedfoward($a, $id){
    
     if(mysqli_query($a,"SELECT u.u_name, u.u_id FROM users u, online o where u.u_id = o.u_id and u.u_id != '$id'")){
         return true;
    }
    return false;
   
}

function requests($a, $id){
    $tes = mysqli_query($a,"UPDATE online SET o_feedfoward=1  WHERE u_id = '$id'");
    if(!$tes){
        return false;
    }
    return true;
   
}
function pedidos($a, $id){
    $tes = mysqli_query($a,"SELECT u.u_name, r.rc_sender FROM users u, request_connection r where u.u_id = r.rc_sender and  r.rc_receiver = '$id'");
    $array1=[];
    while($row =$tes->fetch_assoc()){

        $array1[] = $row;
    }
    return $array1;
}


if(isset($_POST['getOnline'])){
    $id = $_POST['id'];
    $responseObject->success = true;
    $responseObject->table = upd($conn,$id);
    $responseObject->ff = feedfoward($conn,$id);
    $responseObject->pedidos = pedidos($conn,$id);
    echo json_encode($responseObject);
    exit;
}

if(isset($_POST['criaconex']))


include "../inc/close_con.php";