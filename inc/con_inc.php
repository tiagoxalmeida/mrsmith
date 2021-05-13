<?php

    $servername = "localhost";
    $username = "mradmin";
    $password = "mrsmithpassword";

    //Create connection

    $conn = mysqli_connect($servername,$username,$password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    mysqli_select_db($conn,"mrsmith");


?>