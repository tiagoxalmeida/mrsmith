<?php
    include "../inc/con_inc.php";
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $user = $_POST['user'];//id de quem quer fazer conexao
        $userid = $_POST['userid'];//id de quem vai ser pedido
        
        $opt = $_POST['opt'];
        $public_key = serialize($_POST['Pk_encrypt']);
        $private_key = serialize($_POST['pk_encrypt']);
        

        //if(!empty($user) && !empty($userid) && !empty($public_key) && !empty($private_key)){ //Executar só se os campos estiverem preenchidos

            //$query = "INSERT INTO 'request_connection' ('rc_key_encrypted' ,'rc_sender', 'rc_receiver') VALUES ('asdasdsadasd','2', '$3')'";//query para inserir as cenas

            if( mysqli_query($conn,"INSERT INTO request_connection(rc_key_encrypted, rc_sender, rc_receiver) VALUES ('sadsadsadasd','$user','$userid')")){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }
            else{
                $responseObject->success = false;
                echo json_encode($responseObject);
                exit;
            }}//}
        
           
    include "../inc/close_con.php";