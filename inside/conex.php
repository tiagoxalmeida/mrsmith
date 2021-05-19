<?php

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
        }else if(isset($_POST['testConnection'])){
            session_start();
            $user = $_SESSION['u_id'];
            $userid = $_POST['userid'];
            $query = "SELECT u_id FROM connected WHERE c_receiver = '$userid' && c_sender= '$user';";

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
        }else if(isset($_POST['connect'])){
            session_start();
            $senderid = $_POST['userid'];
            $receiverid = $_SESSION['u_id'];
            $privateKey = file_get_contents('../inc/priv.pem');
            $query1 = "SELECT rc_key_encrypted FROM request_connection WHERE rc_sender='$senderid' AND rc_receiver='$receiverid'";
            if(!$result1 = mysqli_query($conn,$query1)){
                $responseObject->success = false;
                $responseObject->error = "Query 1 Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result1)<0){
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

            $query2 = "DELETE FROM request_connection WHERE rc_receiver = '$receiverid' AND rc_sender= '$receiverid'";
            if(!mysqli_query($conn,$query2)){
                $responseObject->success = false;
                $responseObject->error = "Query 2 Error";
                echo json_encode($responseObject);
                exit;
            }
            $query3 = "DELETE FROM connected WHERE c_sender = '$senderid' OR c_receiver='$receiverid'";
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
            $pk_receiver_array = unserialize($row['u_public_key_encrypt']);
            
            $pk_receiver = "";
            foreach($pk_receiver_array as $part){
              $decrypted = null;
              if(!openssl_private_decrypt(base64_decode($part), $decrypted, $privateKey)){
                $responseObject->success = false;
                $responseObject->error = "Error decrypting key";
                echo json_encode($responseObject);
                exit;
              }
              $pk_receiver .= $decrypted;
            }

            $query6 = "SELECT u_private_encrypt_key FROM users WHERE u_id = '$senderid'";
            if(!$result6 = mysqli_query($conn,$query6)){
                $responseObject->success = false;
                $responseObject->error = "Query 5 Error";
                echo json_encode($responseObject);
                exit;
            }
            if(mysqli_num_rows($result6)<0){
                $responseObject->success = false;
                $responseObject->error = "Results Not Found";
                echo json_encode($responseObject);
                exit;
            }
            if(!$row = mysqli_fetch_assoc($result6)){
                $responseObject->success = false;
                $responseObject->error = "Error fetching array";
                echo json_encode($responseObject);
                exit;
            }
            $sk_sender_array = unserialize($row['u_public_key_encrypt']);
            $sk_sender = "";
            foreach($pk_sender_array as $part){
              $decrypted = null;
              if(!openssl_private_decrypt(base64_decode($part), $decrypted, $privateKey)){
                $responseObject->success = false;
                $responseObject->error = "Error decrypting key";
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
                  $responseObject->error = "Error decrypting key";
                  echo json_encode($responseObject);
                  exit;
                }
                $crypted = null;    
                if(!openssl_public_encrypt($decrypted, $crypted, $pk_receiver)){
                    $responseObject->success = false;
                    $responseObject->error = "Error decrypting key";
                    echo json_encode($responseObject);
                    exit;
                }
                $key[] = $crypted;
            }



            $responseObject->success = true;
            $responseObject->options = $options;


            $responseObject->key = $key;

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