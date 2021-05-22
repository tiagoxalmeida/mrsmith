<?php
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['receiver']) && isset($_POST['opt']) && isset($_POST['session_encrypt']) && isset($_POST['algo'])){
            include "../inc/con_inc.php";   
            session_start();
            $user = $_SESSION['u_id'];//id de quem quer fazer conexao
            $receiver = $_POST['receiver'];
            $opt = $_POST['opt'];
            $key = $_POST['session_encrypt'];
            $algo = $_POST['algo'];
            if(empty($user) || empty($receiver) || empty($key) || empty($opt) || empty($algo)){
                $responseObject->success = false;
                $responseObject-> error = "Fields not filled";
                echo json_encode($responseObject);
                exit;
            }
            $array[0] = $algo;
            $array[1] = $opt;
            $array[2] = $key;
            $arr = serialize($array);
            $query = "INSERT INTO request_connection (rc_key_encrypted ,rc_sender, rc_receiver) VALUES ('$arr','$user', '$receiver');";

            if(mysqli_query($conn,$query)){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }else{
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            include "../inc/close_con.php";
        }else if(isset($_POST['testConnection'])){
            
            include "../inc/con_inc.php";   
            session_start();
            $user = $_SESSION['u_id'];
            $userid = $_POST['userid'];
            $query = "SELECT c_last_file,c_encrypted,c_last_file_ext FROM connected WHERE c_receiver = '$user' AND c_sender= '$userid'";
            if(!$result = mysqli_query($conn,$query)){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result)<=0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            $row = mysqli_fetch_assoc($result);
            $filename = $row['c_last_file'];
            $filedir = $_SERVER['DOCUMENT_ROOT']."/uploads/".$row['c_last_file_ext'];
            $encrypted = $row['c_encrypted'];
            if($encrypted == 1){ // se o ficheiro está encriptado
                $file = fopen($filedir, "r");
                $textencrypted = fread($file, filesize($filedir));
                fclose($file);
                
                $responseObject->fileName = $filename;
                $responseObject->fileContents = $textencrypted;
                $responseObject->fileEncrypted = $encrypted;

            }else if($encrypted == 2){

            }else if($encrypted == 3){

            }
            $responseObject->success = true;
            echo json_encode($responseObject);
            include "../inc/close_con.php";
            exit;/**/
        }else if(isset($_POST['connect'])){
            include "../inc/con_inc.php";   
            session_start();
            $senderid = $_POST['userid'];
            $receiverid = $_SESSION['u_id'];
            $privateKey = file_get_contents('../inc/priv.pem');
            mysqli_begin_transaction($conn);
            $query1 = "SELECT rc_key_encrypted FROM request_connection WHERE rc_sender='$senderid' AND rc_receiver='$receiverid'";
            if(!($result1 = mysqli_query($conn,$query1))){
                $responseObject->success = false;
                $responseObject->error = "Query 1 Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result1)<=0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            if(!$row = mysqli_fetch_assoc($result1)){
                $responseObject->success = false;
                $responseObject->error = "Error fetching array";
                echo json_encode($responseObject);
                exit;
            }
            $response_array = unserialize($row['rc_key_encrypted']);
            $key_encrypted_array = $response_array[2];
            $options[0] = $response_array[0];
            $options[1] = $response_array[1];

            $query2 = "DELETE FROM request_connection WHERE rc_receiver = '$receiverid' AND rc_sender= '$senderid'";
            if(!mysqli_query($conn,$query2)){
                $responseObject->success = false;
                $responseObject->error = "Query 2 Error";
                echo json_encode($responseObject);
                exit;
            }
            $query3 = "DELETE FROM connected WHERE (c_sender = '$senderid' AND c_receiver='$receiverid') OR (c_sender= '$receiverid' AND c_receiver= '$senderid')";
            if(!mysqli_query($conn,$query3)){
                $responseObject->success = false;
                $responseObject->error = "Query 3 Error";
                echo json_encode($responseObject);
                exit;
            }
            $query4 = "INSERT INTO connected (c_sender, c_receiver) VALUES ('$senderid','$receiverid')";
            if(!mysqli_query($conn,$query4)){
                $responseObject->success = false;
                $responseObject->error = "Query 4 Error";
                echo json_encode($responseObject);
                exit;
            }
            $query45 = "INSERT INTO connected (c_sender, c_receiver) VALUES ('$receiverid','$senderid')";
            if(!mysqli_query($conn,$query45)){
                $responseObject->success = false;
                $responseObject->error = "Query 4.5 Error";
                echo json_encode($responseObject);
                exit;
            }
            $query5 = "SELECT u_public_encrypt_key FROM users WHERE u_id = '$receiverid'";
            if(!$result5 = mysqli_query($conn,$query5)){
                $responseObject->success = false;
                $responseObject->error = "Query 5 Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result5)<0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            if(!$row = mysqli_fetch_assoc($result5)){
                $responseObject->success = false;
                $responseObject->error = "Error fetching array";
                echo json_encode($responseObject);
                exit;
            }
            $pk_receiver_array = unserialize($row['u_public_encrypt_key']);
            
            $pk_receiver = "";
            foreach($pk_receiver_array as $part){
              $decrypted = null;
              if(!openssl_private_decrypt(base64_decode($part), $decrypted, $privateKey)){
                $responseObject->success = false;
                $responseObject->error = "Error decrypting key1";
                echo json_encode($responseObject);
                exit;
              }
              $pk_receiver .= $decrypted;
            }

            $query6 = "SELECT u_private_encrypt_key FROM users WHERE u_id = '$senderid'";
            if(!$result6 = mysqli_query($conn,$query6)){
                $responseObject->success = false;
                $responseObject->error = "Query 6 Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result6)<0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            if(!$row2 = mysqli_fetch_assoc($result6)){
                $responseObject->success = false;
                $responseObject->error = "Error fetching array";
                echo json_encode($responseObject);
                exit;
            }
            $sk_sender_array = unserialize($row2['u_private_encrypt_key']);
            $sk_sender = "";
            foreach($sk_sender_array as $part){
              $decrypted = null;
              if(!openssl_private_decrypt(base64_decode($part), $decrypted, $privateKey)){
                $responseObject->success = false;
                $responseObject->error = "Error decrypting key2";
                echo json_encode($responseObject);
                exit;
              }
              $sk_sender .= $decrypted;
            }
            $key = [];
            foreach($key_encrypted_array as $part){
                $decrypted = null;
                if(!openssl_private_decrypt(base64_decode($part), $decrypted, $sk_sender)){
                  $responseObject->success = false;
                  $responseObject->error = "Error decrypting key3";
                  echo json_encode($responseObject);
                  exit;
                }
                $crypted = null;    
                if(!openssl_public_encrypt($decrypted, $crypted, $pk_receiver)){
                    $responseObject->success = false;
                    $responseObject->error = "Error encrypting key";
                    echo json_encode($responseObject);
                    exit;
                }
                $key[] = base64_encode($crypted);
            }
            $responseObject->success = true;
            $responseObject->options = $options;
            $responseObject->key = $key;
            echo json_encode($responseObject);
            mysqli_commit($conn);
            include "../inc/close_con.php";
            exit;
        }else if(isset($_POST['disconnect'])){
            include "../inc/con_inc.php";   
            session_start();

            $user = $_SESSION['u_id'];
            $userid = $_POST['userid'];
            $query = "DELETE FROM connected WHERE (c_receiver = '$userid' AND c_sender= '$user') OR (c_receiver = '$user' AND c_sender= '$userid')";

            if(!($result = mysqli_query($conn,$query))){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            $responseObject->success = true;
            echo json_encode($responseObject);

            include "../inc/close_con.php";
            exit;
        }else if(isset($_POST['sendFileEnc'])){
            include "../inc/con_inc.php";   
            session_start();

            $user = $_SESSION['u_id'];
            $c_last_file = $_POST['c_last_file'];
            $c_encrypted = $_POST['c_encrypted'];
            $file = $_POST['file'];

            $uniq = uniqid('file_',false).".enc";
            //opendir("uploads/");
            $myfile = fopen($_SERVER['DOCUMENT_ROOT']."/uploads/".$uniq, "w") or die("Unable to open file!");
            fwrite($myfile, $file);
            fclose($myfile);
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
        }else if(isset($_POST['verifySender'])){
            include "../inc/con_inc.php";   
            session_start();
            $user = $_SESSION['u_id'];
            $query = "SELECT c_last_file,c_encrypted,c_last_file_ext FROM connected WHERE c_sender = '$user'";

            if(!$result = mysqli_query($conn,$query)){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result)<=0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            $row = mysqli_fetch_assoc($result);
            if($row['c_last_file'] == "" && $row['c_encrypted'] == "" && $row['c_last_file_ext'] == ""){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }

            $responseObject->success = false;
            echo json_encode($responseObject);

            include "../inc/close_con.php";
            exit;
        }else if(isset($_POST['decline'])){
            include "../inc/con_inc.php";   
            session_start();

            $user = $_SESSION['u_id'];
            $userid = $_POST['userid'];
            $query = "DELETE FROM request_connection WHERE rc_sender= '$userid' AND rc_receiver='$user'";

            if(!($result = mysqli_query($conn,$query))){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            $responseObject->success = true;
            echo json_encode($responseObject);

            include "../inc/close_con.php";
            exit;
        }else if(isset($_POST['delete'])){
            include "../inc/con_inc.php";   
            session_start();

            $user = $_SESSION['u_id'];
            $userid = $_POST['userid'];
            $query = "DELETE FROM request_connection WHERE rc_sender= '$user' AND rc_receiver='$userid'";

            if(!($result = mysqli_query($conn,$query))){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            $responseObject->success = true;
            echo json_encode($responseObject);

            include "../inc/close_con.php";
            exit;
        }else if(isset($_POST['received'])){
            include "../inc/con_inc.php";   
            session_start();

            $user = $_SESSION['u_id'];
            $query = "UPDATE connected SET c_last_file='', c_encrypted='', c_last_file_ext='' WHERE c_receiver= '$user'";

            if(!($result = mysqli_query($conn,$query))){
                $responseObject->success = false;
                $responseObject-> error = "Server Error";
                echo json_encode($responseObject);
                exit;
            }
            $responseObject->success = true;
            echo json_encode($responseObject);

            include "../inc/close_con.php";
            exit;
        }else{
            $responseObject->success = false;
            $responseObject->error = "Fields not filled";
            echo json_encode($responseObject);
            exit;
        }

    }else{
        $responseObject->success = false;
        $responseObject->error = "Fields not filled";
        echo json_encode($responseObject);
        exit;
    }