<?php


    include "../header.php";
    include "../inc/con_inc.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $mail = $_POST['mail'];
        $us = $_POST['username'];
        $pwd = $_POST['pwd'];
        $repwd = $_POST['repwd'];

        $salt = md5(random_bytes(256),false);
        $rep = hash("sha256",$pwd.$salt,false);

        if(!empty($mail) && !empty($us) && !empty($pwd) && $pwd == $repwd){
            $query = "INSERT INTO users (u_name,u_email,u_salt,u_pwd) VALUES ('$us','$mail','$salt','$rep')";
            if($result = mysqli_query($conn,$query)){
                echo "sucesso";
            }else{
                echo mysqli_error($conn);
            }
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
                        <p class="h3">Sign up</p>
                        <div class="mb-3">
                        <input type="email" class="form-control" id="exampleInputEmail1" name="mail" aria-describedby="emailHelp" placeholder="E-mail">
                        </div>
                        <div class="mb-3">
                        <input type="username" class="form-control" id="username" name="username" aria-describedby="emailHelp" placeholder="Username">
                        </div>
                        <div class="mb-3">
                        <input type="password" class="form-control" id="exampleInputPassword1" name="pwd" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="exampleInputPassword2" name="repwd" placeholder="Repeat Password">
                        </div>
                        <button type="submit"  class="btn btn-primary">Sign up</button>
                        <a class="btn btn-primary" href="/login/" role="button">Return to login</a>
                    </form>
                </section>
            </section>
        </section>
<?php
    include "../inc/close_con.php";
    include "../footer.php";
?>