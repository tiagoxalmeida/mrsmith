<?php
    include "../inc/con_inc.php";
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $user = $_POST['user'];//id de quem quer fazer conexao
        $userid = $_POST['userid'];//id de quem vai ser pedido
        $key = $_POST['key'];
        $options = $_POST['options'];
        $last_file =$_POST['last_file'];
        $last_file_ext =$_POST['last_file_ext'];
        $encrypted = $_POST['encrypted'];
       
        if(isset($_POST['verificar'])){

            $query = "SELECT * from connected where ('$user' = c_receiver and '$userid' = c_sender) or ('$userid' = c_receiver and '$user' = c_sender))";//query para inserir as cenas

            if( mysqli_query($conn,$query)){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }
            else{
                $responseObject->success = false;
                echo json_encode($responseObject);
                exit;
            }}
            if(isset($_POST['criar'])){

                $query = "INSERT INTO `connected`(c_last_file, c_encrypted, c_last_file_ext, c_options, c_sender, c_receiver) VALUES ('$last_file','$encrypted','$last_file_ext','$options','$user','$userid')";//query para inserir as cenas
    
                if( mysqli_query($conn,$query)){
                    $responseObject->success = true;
                    echo json_encode($responseObject);
                    exit;
                }
                else{
                    $responseObject->success = false;
                    echo json_encode($responseObject);
                    exit;
                }}
                if(isset($_POST['eliminar'])){

                    $query = "DELETE FROM connected where  ('$user' = c_receiver and '$userid' = c_sender) or ('$userid' = c_receiver and '$user' = c_sender)))";
        
                    if( mysqli_query($conn,$query)){
                        $responseObject->success = true;
                        echo json_encode($responseObject);
                        exit;
                    }
                    else{
                        $responseObject->success = false;
                        echo json_encode($responseObject);
                        exit;
                    }}
                    if(isset($_POST['update'])){

                        $query = "UPDATE connect set c_last_file = '$last_file', c_last_file_ext = '$last_file_ext' where  ('$user' = c_receiver and '$userid' = c_sender) or ('$userid' = c_receiver and '$user' = c_sender)))";
            
                        if( mysqli_query($conn,$query)){
                            $responseObject->success = true;
                            echo json_encode($responseObject);
                            exit;
                        }
                        else{
                            $responseObject->success = false;
                            echo json_encode($responseObject);
                            exit;
                        }}


        }
        
           
    include "../inc/close_con.php";