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
                        <p class="h3">Sign up</p>
                        <div class="mb-3">
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username">
                        </div>
                        <div class="mb-3">
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Repeat Password">
                        </div>
                        <button type="submit"  class="btn btn-primary">Sign up</button>
                        <a class="btn btn-primary" href="/login/" role="button">Return to login</a>
                    </form>
                </section>
            </section>
        </section>
<?php
    include "../footer.php";
?>