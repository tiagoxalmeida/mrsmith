<?php
    include "../inc/testSession.php";
    include '../header.php';
    include "../inc/con_inc.php";
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
                    <button type="button" class="btn btn-primary" onclick="Logout()">Logout</button>
                </div>
            </section>
            
            ';
        }else if(isset($_GET['/connecting/'])){
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
            include_once "message.php";
        }
        ?>
        
    </section><script>

    function Logout(){
        
        $.ajax({
            type: "POST",
            url: "online.php",
            data: {id:<?php echo $_SESSION['u_id'] ?>,apagar:true},
            dataType: "JSON",
            success: function (html){console.log(html);
                if(html.success){
                    console.log("aceitar");
                    window.location.href ="/";
                }
              
            },error: function (html){console.log(html);}
        
        });

    }
    function RSAencrypt(publicKey, text){
        var crypt = new JSEncrypt();
        crypt.setPublicKey(publicKey);
        return crypt.encrypt(text);
    }
    function sendToServer(sessionKey, receiverid, opt, algo){
        var partitionedSessionKey = [];
        var i = 0;
        while (i < sessionKey.length){
            partitionedSessionKey.push(sessionKey.slice(i,i+181));
            i=i+181;
        }
        var encryptedSessionKey = [];
        partitionedSessionKey.forEach(function(el,i){
            var enc = RSAencrypt(localStorage.getItem('Pk_encrypt'),el);
            encryptedSessionKey.push(enc);
            
        });

        var data = {
            session_encrypt: encryptedSessionKey,
            receiver: receiverid,
            opt: opt,
            algo: algo
        };
        $.ajax({
            type: "POST",
            url: "conex.php",
            data: data,
            dataType: "JSON",
            success: function (html){console.log(html);
                if(html.success){
                    window.location.href ="?/connecting/="+receiverid;
                }
                else{
                    var toasterror = $('<div class="toast bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                        <div class="toast-header">\
                            <strong class="me-auto">Error</strong>\
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                        </div>\
                        <div class="toast-body">\
                            An error occurred while connecting with the person you choose, please try again later.\
                        </div>\
                    </div>');
                    $('.toast-container').append(toasterror);
                    var terror = new bootstrap.Toast(toasterror);
                    terror.show();
                }
            },error: function (html){console.log(html);}
        
        });
    }
    function inviteUser(el){
        var receiverid = el.id;
        var algo = $('[name=algo]:checked').val();
        var opt = $('[name=opt]:checked').val();
        var keySize = $('[name=key]:checked').val();
        //CREATE A KEY
        if(keySize == ""){
            keySize = localStorage.getItem('sessKeySize');
        }
        var key = CryptoJS.SHA3(CryptoJS.lib.WordArray.random(128 / 8), { outputLength: keySize }).toString();
        //SAVE OPTIONS
        if(algo == "" || opt == "" ){
            sendToServer(key,receiverid, localStorage.getItem('sessOpt'), localStorage.getItem('sessAlgo'))
        }
        localStorage.setItem('sessKey', key);
        localStorage.setItem('sessKeySize', keySize);
        localStorage.setItem('sessAlgo', algo);
        localStorage.setItem('sessOpt',opt);
        //SEND THINGS TO SERVER
        sendToServer(key, receiverid, opt, algo);        
    }
    /*
    var options = {
        animation: true
    };
    var toast = new bootstrap.Toast($('.toast'),options);
    */
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

    <?php if(isset($_GET['/connecting/']))
        echo '
        var loading = setInterval(() => {
            $.ajax({
                type: "POST",
                url: \'conex.php\',
                data: { 
                    testConnection: true,
                    userid: '.$_GET['/connecting/'].'
                },
                dataType: "JSON",
                success: function (html){console.log(html);
                    if(html.success){
                        clearInterval(loading);
                        window.location.href ="?/connected/='.$_GET['/connecting/'].'"; 
                    }
                },error: function (html){console.log(html);}
            });
        //
        }, 5000);
        ';
    ?>

  
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
            
        </div>
    </section>
    
</div>
<script>
function RSAdecrypt(privateKey, text){
    var crypt = new JSEncrypt();
    crypt.setPrivateKey(privateKey);
    return crypt.decrypt(text);
}
function AcceptInvite(idSender){
    $.ajax({
        type: "POST",
        url: 'conex.php',
        data: { connect: true, userid: idSender},
        dataType: "JSON",
        success: function (html){console.log(html);
            if(html.success){
                var key = "";
                for(i=0; i< html.key.length;i++)
                    key += RSAdecrypt(localStorage.getItem('pk_encrypt'), html.key[i]);
                localStorage.setItem('sessKey',key);
                localStorage.setItem('sessAlgo', html.options[0]);
                localStorage.setItem('sessOpt',html.options[1]);
                window.location.href ="?/connected/="+idSender; 
            }
        },
        error: function (html){console.log(html);}
    });
}


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
        
        var req = html.pedidos;
        var oldReq = JSON.parse(localStorage.getItem("oldReq"));
        
        for (var i = 0;i < req.length;i++){
            var nomeSender = req[i].u_name;
            var idSender = req[i].rc_sender;
            if(!JSON.stringify(oldReq).includes(JSON.stringify(req[i]))){
                var tst = $('<div class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                    <div class="toast-header">\
                        <strong class="me-auto">Invite!</strong>\
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                    </div>\
                    <div class="toast-body">\
                        User '+ nomeSender+' wants to send you a file.\
                        Do you want to accept?\
                        <div class="mt-2 pt-2">\
                        <button type="button" class="btn btn-primary btn-sm" onclick="AcceptInvite('+idSender+')">Yes</button>\
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">No</button>\
                        </div>\
                    </div>');
                    $(".toast-container").append(tst);
                    var terror = new bootstrap.Toast(tst);
                    terror.show();
            }  
        }
        localStorage.setItem("oldReq",JSON.stringify(html.pedidos));
        }
        else{
            
        }
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