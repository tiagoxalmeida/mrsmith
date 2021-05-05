<html>
    <head></head>
    <body>
        <script src="/js/cryptico.min.js"></script>
        <form class="form-container">
            <input type="email" class="form-control" id="input1" aria-describedby="emailHelp" placeholder="Username">
            <input type="password" class="form-control" id="input2" placeholder="Password">
            <button type="button" id="gerar" class="btn btn-primary">Gerar</button>
            <button type="button" id="encrypt"  class="btn btn-primary">Encrypt</button>
        </form>


        <?php
            if(isset($_GET['cipher']) && isset($_GET['sk'])){
                $cipher = $_GET['cipher'];
                $sk = $_GET['sk'];
                $plainText;
                openssl_private_decrypt($cipher,$plainText,$sk);
                echo $plainText;
            }
        ?>


        <div id="teste"></div>
    </body>
    <script src="teste.js">
    </script>
</html>