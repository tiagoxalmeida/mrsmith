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
<section class="col-sm-8 col-md-6 col-xl-4 p-4 mt-4 border border-dark rounded-2 mx-auto">
    <h1 class="text-center">Mr. Smith</h1>          
    <form method="post" action="" class="form-container needs-validation" novalidate>
        <p class="h3 text-left">Sign up</p>
        <div class="mb-3">
            <span>Username:</span>
            <input type="username" class="form-control m-1" id="username" name="username" placeholder="Username" required>
            <div class="invalid-feedback">
                Please provide a valid username.
            </div>
        </div>
        <div class="mb-3">
            <span>E-mail:</span>
            <input type="email" class="form-control m-1" id="exampleInputEmail1" name="mail" placeholder="E-mail" required>
            <div class="invalid-feedback">
                Please provide a valid email.
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
            <span>Verify Password:</span>
            <input type="password" class="form-control m-1" id="exampleInputPassword2" name="repwd" placeholder="Repeat the Password" required>
            <div class="invalid-feedback">
                Please provide a password verification.
            </div>
        </div>
        <div class="text-center">
            <button type="submit"  class="btn btn-primary">Submit</button>
            <a class="btn btn-secondary" href="/login/" role="button">Login</a>
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