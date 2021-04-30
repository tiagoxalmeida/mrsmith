<?php
    include "../header.php";
?>
<section class="container-fluid"> 
    <section class="row justify-content-center">
        <section class="col-12 col-sm-6 col-md-3">
            <div class="titulo">
                <h1 class="text-justify">Mr. Smith</h1>          
            </div>
            <form class="form-container">
                <p class="h3">Login</p>
                <div class="mb-3">
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <button type="submit"  class="btn btn-primary">Submit</button>
                <a class="btn btn-primary" href="/sign-up/" role="button">Sign up</a>
            </form>
        </section>
    </section>
</section>

<?php
    include "../footer.php";
?>