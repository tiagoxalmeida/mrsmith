<?php
$connectionid = $_GET['/connected/'];
$queryUserName = "SELECT u_name FROM users WHERE u_id = '$connectionid'";
$result = mysqli_query($conn,$queryUserName);

$row = mysqli_fetch_assoc($result);
$username = $row["u_name"];
//$user = $_GET['user'];
echo '
<section class="col-sm-8 border mx-auto my-5 rounded d-flex flex-column align-items-stretch">
    <h4 class="text-left text-success w-100 bg-primary text-white p-3 mb-3 align-self-start position-relative">Session started with '.$username.' <button type="button" class="btn-close position-absolute end-0 top-0 p-3" aria-label="Close" onclick="onExit()"></button></h4>
    <div class="d-flex flex-column align-items-stretch justify-content-end h-100 align-self-stretch" style="max-height:100%; overflow:auto" >
        <div id="files-uploaded align-self-stretch" style="overflow: auto">
            
            <div class="file d-flex flex-row justify-content-end">
                <div class="border rounded p-3 m-3 mw-100 position-relative">
                    <div>
                    <small>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud" viewBox="0 0 16 16">
                            <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                        </svg>
                        Uploaded
                    </small>
                    <p class="m-0">Nome do ficheiro enviado.txt </p> 
                    </div>
                </div>
            </div>
            <div class="file d-flex flex-row justify-content-start">
                <div class="border rounded p-3 m-3 mw-100 position-relative  bg-light">
                    <div>
                        <small>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-check" viewBox="0 0 16 16">
                                <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/>
                                <path d="M15.854 10.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.707 0l-1.5-1.5a.5.5 0 0 1 .707-.708l1.146 1.147 2.646-2.647a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            Downloaded
                        </small>
                        <p class="m-0">Nome do ficheiro recebido.txt </p> 
                    </div>
                </div>
            </div>
            <div class="file d-flex flex-row justify-content-end">
                <div class="border rounded p-3 m-3 mw-100 position-relative ">
                    <div>
                        <small>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud" viewBox="0 0 16 16">
                                <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                            </svg>
                            Uploaded
                        </small>
                        <p class="m-0">Nome do ficheiro bastante enorme que não cabe numa linha.txt </p> 
                    </div>
                </div>
            </div>
            <div class="file d-flex flex-row justify-content-end">
                <div class="border rounded p-3 m-3 mw-100 position-relative">
                    <div>
                        <small>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            Uploading...
                        </small>
                        <p class="m-0">Nome do ficheiro.txt </p> 
                    </div>
                </div>
            </div>
            <div class="file d-flex flex-row justify-content-start">
                <div class="border rounded p-3 m-3 mw-100 position-relative  bg-light">
                    <div>
                        <small>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-short" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Downloading...
                        </small>
                        <p class="m-0">Nome do ficheiro.txt </p> 
                    </div>
                </div>
            </div>
            
        </div>
        <div class="align-self-end w-100 p-3 text-right align-items-end">
            <input type="file" placeholder="Chose a file to send" aria-label="Chose a file to send" class="form-control">
            <div class="mt-3 position-relative">
                <div class="btn-group" role="group" aria-label="Chose what you want to do with your file">
                    <input type="radio" class="btn-check" name="btnradio" id="normal" value="0" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="normal">Normal</label>
                    <input type="radio" class="btn-check" name="btnradio" id="encrypt" value="1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="encrypt">Encrypt</label>
                    <input type="radio" class="btn-check" name="btnradio" id="sign" value="2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="sign">Sign</label>
                </div>
                <button class="btn btn-primary position-absolute end-0 right-0" type="submit" id="sendfile">Send</button>
            </div>
        </div>
        
    </div>
</section>
';
?>

<script>
    function onExit(){
        window.location.href = '\?';
        //retirar a conexão
    }

    function conexao(){
        console.log(<?php echo $_SESSION['u_id'] ?>);
        $.ajax({
            type: "POST",
            url: 'conex.php',
            data: { testConnection:true, userid: <?php echo $connectionid ?>},
            dataType: "JSON",
            success: function (html){
                console.log(html);
                if(!html.success){
                    console.log("sair");
                    window.location.href = '\?'; //redirecionar para a pagina index
                }
            },
            error: function (html){
                console.log(html);
            }
        });
    }

    setInterval(() => {
        conexao();
    }, 5000);
    


</script>

<?php