<?php
    include '../header.php';
    include "../inc/con_inc.php";
   include "../inc/testSession.php";
    function upd($a){
        $tes = mysqli_query($a,"SELECT u.u_name, u.u_id FROM users u, online o where u.u_id = o.u_id");
        while($row =$tes->fetch_assoc()){
      
          $array[] = $row;
          }
          
          return json_encode($array);
    }

 
    
?>
<style>
.btn svg{
    display: inline-block;
    vertical-align: -.125em;
}
button[type='button'] {
   text-align: left;
}
::-webkit-scrollbar {
    width: 10px;
    background-color: #f5f5f5b4;
    margin: 0 5px;
}

::-webkit-scrollbar-thumb {
    border-radius: 3px;
    -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
    background-color: rgba(221, 221, 221, 0.705); 
    position: absolute;
}

</style>
<div class="row min-vh-100 w-100 m-0 position-relative">
    <section class="content col-md-8 col-xl-9 min-vh-100 d-flex position-relative" style="max-height:100vh; overflow: auto">
        <div class="toast-container position-absolute top-0 start-50 translate-middle-x p-3" style="z-index:100000">
            <!-- Then put toasts within -->
            <div class="toast bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Warning!</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    The message you sended isn't received, you will overwrite the message if you send this one. Do you want to continue?
                    <div class="mt-2 pt-2">
                    <button type="button" class="btn btn-primary btn-sm">Continue</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">No</button>
                    </div>
                </div>
            </div>
        </div>
        <?php if(!isset($_GET['/connecting/']) && !isset($_GET['/connected/'])){
            echo '
            <section class="col-sm-8 border mx-auto my-5 rounded p-5 d-flex align-items-center justify-content-center text-center"  style="max-height:100%; overflow:auto">
                <div>
                    <h4 class="text-center text-success">You are online</h4>
                    <p class="text-center">Chose a friend to encrypt and send files or just simply send signed files. You can also wait for a friend invitation to comunicate with you.</p>
                    <p class="text-center">Before you connect chose one of the algos and keys available to encrypt your messages.</p>
                    <div class="mt-3">
                        <p class="form-text">Encryption algorithm:</p>
                        <div class="btn-group" role="group" aria-label="Chose what algorigthm you want">
                            <input type="radio" class="btn-check" name="algo" id="aes" value="AES" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary" for="aes">AES</label>
                            <input type="radio" class="btn-check" name="algo" id="3des" value="3DES" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="3des">3DES</label>
                            <input type="radio" class="btn-check" name="algo" id="des" value="DES" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="des">DES</label>
                            <input type="radio" class="btn-check" name="algo" id="rc4" value="RC4" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="rc4">RC4</label>
                        </div>
                    </div>
                    <div class="mt-3" id="enc-option">
                        <p class="form-text">Encryption option:</p>
                        <div class="btn-group" role="group" aria-label="Chose what encryption option you want">
                            <input type="radio" class="btn-check" name="opt" id="ecb" value="ECB" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary" for="ecb">ECB</label>
                            <input type="radio" class="btn-check" name="opt" id="cbc" value="CBC" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="cbc">CBC</label>
                        </div>
                    </div>
                    <div class="mt-3" id="key-option">
                        <p class="form-text">Key size:</p>
                        <div class="btn-group" role="group" aria-label="Chose what key size you want">
                            <input type="radio" class="btn-check" name="key" id="128" value="128" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary" for="128">128 Bits</label>
                            <input type="radio" class="btn-check" name="key" id="192" value="192" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="192">192 Bits</label>
                            <input type="radio" class="btn-check" name="key" id="256" value="256" autocomplete="off">
                            <label class="btn btn-outline-secondary" for="256">256 Bits</label>
                        </div>
                    </div>
                    <p class="mt-4">If you connect to a friend through invitation, the encryption algo and options are choosed by him.</p>
                </div>
            </section>
            
            ';
        }else if(isset($_GET['/connecting/'])){
            $userid =  $_GET['userid'];
            $user = $_GET['user'];
            echo '
            <section class="col-sm-8 border mx-auto my-5 rounded p-5 d-flex align-items-center justify-content-center text-center"  style="max-height:100%; overflow:auto">
                <div>
                    <div class="spinner-border text-secondary my-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Wait a moment please...</p>
                    <p>We are waiting for your friend\'s permission.</p>
                </div>
            </section>
            ';
        }else if(isset($_GET['/connected/'])){
            $connectionid = $_GET['/connected/'];
            $userid =  $_GET['userid'];
            $user = $_GET['user'];
            echo '
            <section class="col-sm-8 border mx-auto my-5 rounded d-flex flex-column align-items-stretch">
                <h4 class="text-left text-success w-100 bg-primary text-white p-3 mb-3 align-self-start position-relative">Session started with '+$user+' <button type="button" class="btn-close position-absolute end-0 top-0 p-3" aria-label="Close" onclick="window.location.href=\'?\';"></button></h4>
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
        }
        ?>
        
    </section><script>
      function RSAencrypt(publicKey, text){
    var crypt = new JSEncrypt();
    crypt.setPublicKey(publicKey);
    return crypt.encrypt(text);
}
    function sendToServer(publicKey,privateKey,user, userid,opt,key,algo){
var public_key_server = "<?php echo str_replace(array("\n","\r"), '', file_get_contents("../inc/pub.pem")); ?>";
var partitionedPublic = [];
var partitionedPrivate = [];
var i = 0;
while (i < publicKey.length){
    partitionedPublic.push(publicKey.slice(i,i+181));
    i=i+181;
}
var encryptedPublic = [];
partitionedPublic.forEach(function(el,i){
    var enc = RSAencrypt(public_key_server,el);
    encryptedPublic.push(enc);
    
});

i=0;
while (i < privateKey.length){
    partitionedPrivate.push(privateKey.slice(i,i+181));
    i=i+181;
}
var encryptedPrivate = [];
partitionedPrivate.forEach(function(el,i){
    var enc = RSAencrypt(public_key_server,el);
    encryptedPrivate.push(enc);
});

var data = {
    Pk_encrypt: encryptedPublic,
    pk_encrypt: encryptedPrivate,
    user: user,
    userid: userid,
    algo: algo,
    opt: opt,
    key: key,

};

$.ajax({
    type: "POST",
    url: 'conex.php',
    data: data,
    dataType: "JSON",
    success: function (html){console.log(html);
        if(html.success){
            window.location.href ="?/connecting/="+userid;
        }
        else{
            
        }
    },error: function (html){console.log(html);}
  
});
}
    function inviteUser(el){
        var userid = el.id;
        var algo = $('[name=algo]:checked').val();
        var opt = $('[name=opt]:checked').val();
        var key = $('[name=key]:checked').val();
        //TODO - CREATE A KEY AND ENCRYPT IT 
        //TODO - SEND THINGS TO SERVER 
        //TODO - WAIT FOR THE CONNECTION TO BE ESTABLISHED
        var pub = localStorage.getItem("Pk_encrypt");
        var priv =   localStorage.getItem("pk_encrypt");
        sendToServer(pub,priv,1,userid,opt,key);
        
    }
    var options = {
        animation: true
    };
    var toast = new bootstrap.Toast($('.toast'),options);
    
    $('[name=algo]').on('change',function (){
        if($('[name=algo]:checked').val() == "RC4"){
            $('#enc-option').hide();
        }else{
            $('#enc-option').show();
        }
        if($('[name=algo]:checked').val() == "AES"){
            $('#key-option').show();
        }else{
            $('#key-option').hide();
        }
    });
       


    
    function connectionEstablished(userid){
    $.ajax({
    type: "POST",
    url: 'criar.php',
    data: { userid: userid, user:<?php echo $_SESSION['u_id'] ?>}, //aqui substuir mais tarde pelo php session
    dataType: "JSON",
    success: function (html){console.log(html);
        if(html.success){
         var ficheiros = JSON.parse(localStorage.getItem("ficheiros"));//vai buscar ao local storage o array nome dos ficheiros mandados, para inserir localStorage.setItem("ficheiros", JSON.stringify(ficheiros));
        }
        else{
            
        }
        
    },error: function (html){console.log(html);}
  
});
        return true;
    }
    <?php if(isset($_GET['/connecting/']))
        echo '
        var loading = setInterval(() => {
            if(connectionEstablished('.$_GET['/connecting/'].')){
                clearInterval(loading);
                window.location.href ="?/connected/='.$_GET['/connecting/'].'"; 
            }
        //
        }, 5000);
        ';
    ?>
    $('#sendfile').on('click',function(){
        toast.show();
    });

  
    </script>


    <section class="online col-md-4 col-xl-3 m-0 bg-light min-vh-100" style="max-height:100vh;">
        <h4 class="text-center py-4 m-0">People Online</h4>
        <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
            </svg>
            </span>
            <input type="text" class="form-control" placeholder="Search People" aria-label="Search People" aria-describedby="basic-addon1">
        </div>
        <div class="online-content px-1" style="max-height: 80%; overflow-y: auto;" data-mdb-perfect-scrollbar='true' >
            <button type="button" class="btn btn-info w-100 py-2 my-1" id="1" onclick="inviteUser(this);">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>
                </svg>
                <h6 class="d-inline-block m-0">usernameperson1</h6> 
            </button>
        </div>
    </section>
    
</div>
<script>
  
function atualiza(){
    $.ajax({
    type: "POST",
    url: 'online.php',
    data: { getOnline:true, id:<?php echo $_SESSION['u_id'] ?>}, //aqui substuir mais tarde pelo php session
    dataType: "JSON",
    success: function (html){console.log(html);
        if(html.success){
            var users = html.table;
            $(".online-content").html("");
        for (var i = 0; i < users.length; i++) {
        var nome = users[i].u_name;
        var id =  users[i].u_id;
        var b = $(' <button type="button" class="btn btn-info w-100 py-2 my-1" id="'+id+'" onclick="inviteUser(this)" >\
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">\
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>\
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"></path>\
            </svg>\
            <h6 class="d-inline-block m-0" >'+nome+'</h6> \
            </button>');
            $(".online-content").append(b);
        }
        }
        else{
            
        }
        var req = html.pedidos;
    },error: function (html){console.log(html);}
  
});
        
        
        
     
       
      
      
        
     }
     
     var seeonline = setInterval(() => {
        atualiza();
    }, 5000);
    atualiza();
     </script>

<?php
    include '../footer.php';
?>