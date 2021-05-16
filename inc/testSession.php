<?php
    if(session_status() == PHP_SESSION_NONE){
        //session has not started
        session_start();
    }
    if(!isset($_SESSION['u_id'])){
        $responseObject->success = false;
        $responseObject->error = "User not found";
        echo json_encode($responseObject);
        exit;
    }