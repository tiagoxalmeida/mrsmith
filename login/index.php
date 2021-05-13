<?php
    include "../header.php";
    include "../inc/con_inc.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $us = $_POST['username'];
        $pwd = $_POST['pwd'];


        if(!empty($us) && !empty($pwd)){ //Executar só se os campos estiverem preenchidos

            $query_salt = "SELECT u_salt FROM users WHERE u_name = '$us'"; //query para ir buscar o salt pelo username

            $result_salt = mysqli_query($conn,$query_salt);
        
            if(mysqli_num_rows($result_salt)>0){
                $row = mysqli_fetch_assoc($result_salt);
                $salt = $row["u_salt"]; //salt obtido
            }else{
                die("User not found");
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
                        echo "online";
                    }else{
                        echo "not online";
                    }

                }else{
                    echo "erro";
                }

            }else{
                echo "erro";
            }
            mysqli_free_result($result_pwd);

        }else{
            echo "preencher campos";
        }
    }
?>

<section class="container-fluid"> 
    <section class="row justify-content-center">
        <section class="col-12 col-sm-6 col-md-3">
            <div class="titulo">
                <h1 class="text-justify">Mr. Smith</h1>          
            </div>
            <form class="form-container" method="post" action="">
                <p class="h3">Login</p>
                <div class="mb-3">
                    <input type="username" class="form-control" id="username" name="username" placeholder="Username">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="exampleInputPassword1" name="pwd" placeholder="Password">
                </div>
                <button type="submit"  class="btn btn-primary">Submit</button>
                <a class="btn btn-primary" href="/sign-up/" role="button">Sign up</a>
            </form>
        </section>
    </section>
</section>

<?php
    include "../inc/close_con.php";
    include "../footer.php";
?>