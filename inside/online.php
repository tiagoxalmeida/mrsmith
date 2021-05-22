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
    //TODO - make people online if not online
    session_start();
    if(!isset($_SESSION['u_id'])){
        return false;
    }
    $id = $_SESSION['u_id'];
    $res = mysqli_query($a, "SELECT u_id FROM online WHERE u_id = '$id';");
    if(!$res){
        return false;
    }
    if(mysqli_num_rows($res)<=0){
        $tes1 = mysqli_query($a,"INSERT INTO online (u_id,o_feedfoward) VALUES ('$id',1);");
        if(!$tes1){
            return false;
        }
        return true;
    }
    $tes2 = mysqli_query($a,"UPDATE online SET o_feedfoward=1  WHERE u_id = '$id';");
    if(!$tes2){
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

else if(isset($_POST['apagar'])){
    $id = $_POST['id'];
    $tes = mysqli_query($conn,"DELETE from online where u_id = '$id'");
    if($tes){
        $responseObject->success = true;
        echo json_encode($responseObject);
        exit;
    }
    $responseObject->success = false;
    echo json_encode($responseObject);
    exit;
}


include "../inc/close_con.php";