<?php
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['assinar'])){
            include "../inc/con_inc.php";   
            session_start();
           
            $user = $_SESSION['u_id'];
            $c_last_file = $_POST['c_last_file'];
            $c_encrypted = $_POST['c_encrypted'];
            $file = $_POST['file'];
            $uniq = uniqid('uploads/zip_',false).".zip";
            $zip = new ZipArchive();
            if ($zip->open($uniq, ZipArchive::CREATE)!==TRUE) {
                $responseObject->success = false;
                echo json_encode($responseObject);
                exit;
            }
            if(!$tes = mysqli_query($conn,"SELECT u_private_sign_key  FROM users where u_id = '$user'")){
                $responseObject->success = false;
                $respondeObject->error = "Server error";
                echo json_encode($responseObject);
                exit;
            }
            if(!openssl_sign("Assinatura:", $signature, $tes)){
                $responseObject->success = false;
                $respondeObject->error = "Error in signature criation";
                echo json_encode($responseObject);
                exit;
            }
            if(!$zip->addFromString("assinatura.txt" ,$signature)){
                $responseObject->success = false;
                $respondeObject->error = "Error in adding signature to zip ";
                echo json_encode($responseObject);
                exit;
            }
            if(!$zip->addFile($thisdir . "/'$c_encrypted'",$file)){
                $responseObject->success = false;
                $respondeObject->error = "Error in adding file to zip ";
                echo json_encode($responseObject);
                exit;
            }
            $zip_close();
            $query = "UPDATE connected SET c_last_file = '$c_last_file', c_encrypted = '$c_encrypted', c_last_file_ext = '$uniq' WHERE c_sender = '$user'";
            if( mysqli_query($conn,$query)){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }
            else{
                $responseObject->success = false;
                echo json_encode($responseObject);
                exit;
            }




    }}