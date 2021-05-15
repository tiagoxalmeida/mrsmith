<?php
    include "../inc/con_inc.php";
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $us = $_POST['username'];
        $pwd = $_POST['pwd'];
        $public_key = serialize($_POST['Pk_encrypt']);
        $private_key = serialize($_POST['pk_encrypt']);


        if(!empty($us) && !empty($pwd) && !empty($public_key) && !empty($private_key)){ //Executar só se os campos estiverem preenchidos

            $query_salt = "SELECT u_salt FROM users WHERE u_name = '$us'"; //query para ir buscar o salt pelo username

            $result_salt = mysqli_query($conn,$query_salt);
        
            if(mysqli_num_rows($result_salt)>0){
                $row = mysqli_fetch_assoc($result_salt);
                $salt = $row["u_salt"]; //salt obtido
            }else{
                $responseObject->success = false;
                $responseObject->error = "User not found";
                echo json_encode($responseObject);
                exit;
            }

            mysqli_free_result($result_salt);

            $rep = hash("sha256",$pwd.$salt,false); //representação da password para comparar
            $query_pwd = "SELECT * FROM users WHERE u_pwd = '$rep' AND u_name = '$us'"; //buscar elemento com aquele us e pwd
            $result_pwd = mysqli_query($conn,$query_pwd);
        
            if(mysqli_num_rows($result_pwd)>0){
                $row = mysqli_fetch_assoc($result_pwd);
                
                if($row != null){ //Se o array for diferente de null o elemento existe e sucesso
                    $id = $row['u_id'];
                    $query_online = "INSERT INTO online (u_id) VALUES ('$id')";
                    if($result_online = mysqli_query($conn,$query_online)){
                        
                        $query_keys = "UPDATE users SET u_public_encrypt_key='$public_key' ,u_private_encrypt_key='$private_key' WHERE u_id='$id';";
                        if($result_keys = mysqli_query($conn,$query_keys)){
                            $responseObject->success = true;
                            echo json_encode($responseObject);
                            session_start();
                            $_SESSION['u_id'] = $id;
                            exit;
                        }else{
                            $responseObject->success = false;
                            $responseObject->error = "Keys not saved";
                            echo json_encode($responseObject);
                            exit;
                        }

                    }else{
                        $responseObject->success = false;
                        $responseObject->error = "User already online";
                        echo json_encode($responseObject);
                        exit;
                    }

                }else{
                    $responseObject->success = false;
                    $responseObject->error = "User not found";
                    echo json_encode($responseObject);
                    exit;
                }

            }else{
                $responseObject->success = false;
                $responseObject->error = "Error querying database";
                echo json_encode($responseObject);
                exit;
            }
            mysqli_free_result($result_pwd);

        }else{
            $responseObject->success = false;
            $responseObject->error = "Fields not filled";
            echo json_encode($responseObject);
            exit;
        }
    }
    include "../inc/close_con.php";