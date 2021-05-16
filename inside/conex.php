<?php
    include "../inc/con_inc.php";
    $responseObject = new stdClass();
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = $_POST['user'];//id de quem quer fazer conexao
        $userid = $_POST['userid'];//id de quem vai ser pedido
        $algo = $_POST['algo'];//dados dos butoes
        $opt = $_POST['opt'];
        $key = $_POST['key'];
        $public_key = serialize($_POST['Pk_encrypt']);
        $private_key = serialize($_POST['pk_encrypt']);


        if(!empty($user) && !empty($userid) && !empty($public_key) && !empty($private_key)){ //Executar só se os campos estiverem preenchidos

            $query = "INSERT INTO `request_connection` (`rc_sender`, `rc_receiver`) VALUES ('$us, $userid)'";//query para inserir as cenas

            if( mysqli_query($conn,$query_salt)){
                $responseObject->success = true;
                echo json_encode($responseObject);
                exit;
            }
            else{
                $responseObject->success = false;
                echo json_encode($responseObject);
                exit;
            }}}
        
           
    include "../inc/close_con.php";