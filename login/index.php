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

<section class="col-sm-8 col-md-6 col-xl-4 p-4 mt-4 border border-dark rounded-2 mx-auto"> 
    <h1 class="text-center">Mr. Smith</h1>
    <form class="form-container needs-validation" method="post" action="" novalidate>
        <p class="h3">Login</p>
        <div class="mb-3">
            <span>Username:</span>
            <input type="username" class="form-control m-1" id="username" name="username" placeholder="Username" required>
            <div class="invalid-feedback">
                Please provide a valid username.
            </div>
        </div>
        <div class="mb-3">
            <span>Password:</span>
            <input type="password" class="form-control m-1" id="exampleInputPassword1" name="pwd" placeholder="Password" required>
            <div class="invalid-feedback">
                Please provide a password.
            </div>
        </div>
        <div class="mb-3">
            <span>Key Size:</span>
            <select class="form-control m-1" required>
                <option value="" selected disabled>Selec an option</option>
                <option value="512">512 Bits</option>
                <option value="1024">1024 Bits</option>
                <option value="2048">2048 Bits</option>
                <option value="4096">4096 Bits</option>
            </select>
            <div class="invalid-feedback">
                Please provide a Key Size.
            </div>
        </div>
        <div class="text-center">
            <button type="submit"  class="btn btn-primary">Submit</button>
            <a class="btn btn-secondary" href="/sign-up/" role="button">Sign up</a>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <div class="d-inline-block text-left">
                <a type="button" class="btn btn-info" href="/" role="button">Home</a>
            </div>
            <div class="d-inline-block text-right">
                <a type="button" class="btn btn-light float-right" href="/help/" role="button">Help</a>
            </div>
        </div>
    </form>
</section>
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php
    include "../inc/close_con.php";
    include "../footer.php";
?>