<?php

include "../inc/con_inc.php";
$responseObject = new stdClass();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $mail = $_POST['email'];
    $us = $_POST['username'];
    $pwd = $_POST['pwd1'];
    $repwd = $_POST['pwd2'];
    $keysize = $_POST['keysize'];


    $salt = md5(random_bytes(256),false);
    $rep = hash("sha256",$pwd.$salt,false);
    $config = array(
        "private_key_bits"=> $keysize
    );
    // Create the keypair
    $res=openssl_pkey_new($config);
    openssl_pkey_export($res, $privkey);
    // Get public key
    $pubkey=openssl_pkey_get_details($res);
    $pubkey=$pubkey["key"];


    if(!empty($mail) && !empty($us) && !empty($pwd) && $pwd == $repwd && !empty($keysize) && !empty($privkey) && !empty($pubkey)){
        $query_inuse = "SELECT u_name FROM users WHERE u_name='$us' OR u_email='$mail'";
        $result_inuse = mysqli_query($conn,$query_inuse);
        
            if(mysqli_num_rows($result_inuse)>0){
                $responseObject->success = false;
                $responseObject->error = "User ".$us." or ".$mail." already in use, pick other name or email.";
                echo json_encode($responseObject);
                exit;
            }

        $query = "INSERT INTO users (u_name,u_email,u_salt,u_pwd,u_public_sign_key,u_private_sign_key) VALUES ('$us','$mail','$salt','$rep','$pubkey','$privkey')";
        if($result = mysqli_query($conn,$query)){
            $responseObject->success = true;
            echo json_encode($responseObject);
            exit;
        }else{
            $responseObject->success = false;
            $responseObject->error = "User not inserted, Server Error";
            echo json_encode($responseObject);
            exit;
        }
    }else{
        $responseObject->success = false;
        $responseObject->error = "Fields not filled";
        echo json_encode($responseObject);
        exit;
    }
}else{
    $responseObject->success = false;
    $responseObject->error = "Wrong method";
    echo json_encode($responseObject);
    exit;
}
include "../inc/close_con.php";