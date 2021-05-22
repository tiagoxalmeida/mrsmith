<?php
    function writeFile($filename,$filecontents,$filedir){
        $myfile = fopen($filedir.$filename, "w") or die("Unable to open file!");
        fwrite($myfile, $filecontents);
        fclose($myfile);
    }
    
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['sendFileSign'])){
            include "../inc/con_inc.php";   
            session_start();
            $user = $_SESSION['u_id'];
            $c_last_file = $_POST['file_name'];
            $file = $_POST['file_contents'];
            $filedir = $_SERVER['DOCUMENT_ROOT']."/uploads/";
            $uniq = uniqid('folder_',false).;
            if (mkdir($filedir.$uniq,0700)){
                $responseObject->success = false;
                $respondeObject->error = "Server error";
                echo json_encode($responseObject);
                exit;
            }
            if(!$tes = mysqli_query($conn,"SELECT u_private_sign_key  FROM users where u_id = '$user'")){
                $responseObject->success = false;
                $respondeObject->error = "Query Error";
                echo json_encode($responseObject);
                exit;
            }
            if(!openssl_sign($file, $signature, $tes)){
                $responseObject->success = false;
                $respondeObject->error = "Error in signature criation";
                echo json_encode($responseObject);
                exit;
            }
            writeFile("sign.sig",$signature,$filedir.$uniq);
            writeFile($c_last_file,$file,$filedir.$uniq);
            $query = "UPDATE connected SET c_last_file = '$c_last_file', c_encrypted = 2, c_last_file_ext = '$uniq' WHERE c_sender = '$user'";
            if(!mysqli_query($conn,$query)){
                $responseObject->success = false;
                $respondeObject->error = "Query Error";
                echo json_encode($responseObject);
                exit;
            }       
            $responseObject->success = true;
            echo json_encode($responseObject);
            exit;
        }
    }