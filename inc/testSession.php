<?php
    if(session_status() == PHP_SESSION_NONE){
        //session has not started
        session_start();
    }
    if(!isset($_SESSION['u_id'])){
        if(!headers_sent() && !isset($responseObject)) {
            header("Location: https://mrsmith.ml/login/");
            exit();
        }
        $responseObject->success = false;
        $responseObject->error = "User not found";
        echo json_encode($responseObject);
        exit;
    }