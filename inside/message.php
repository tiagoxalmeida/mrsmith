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
        <div id="files-uploaded" style="overflow: auto">
            
        </div>
        <div class="align-self-end w-100 p-3 text-right align-items-end">
            <input type="file" placeholder="Chose a file to send" aria-label="Chose a file to send" class="form-control" id="fileChoosed">
            <div class="mt-3 position-relative">
                <div class="btn-group" role="group" aria-label="Chose what you want to do with your file">
                    <input type="radio" class="btn-check" name="btnradioenc" id="normal" value="0" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="normal">Normal</label>
                    <input type="radio" class="btn-check" name="btnradioenc" value="1" id="encrypt" autocomplete="off">
                    <label class="btn btn-outline-primary" for="encrypt">Encrypt</label>
                    <input type="radio" class="btn-check" name="btnradioenc" value="2" id="sign" autocomplete="off">
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
        $.ajax({
            type: "POST",
            url: 'conex.php',
            data: { disconnect:true, userid: <?php echo $connectionid ?>},
            dataType: "JSON",
            success: function (html){
                if(!html.success){
                    window.location.href = '\?'; //redirecionar para a pagina index
                }
            },
            error: function (html){
                console.log(html);
            }
        });
    }

    function verify(fileContents,pubKey,signature){
        var verify = new JSEncrypt();
        verify.setPublicKey(pubKey);
        var verified = verify.verify(fileContents, signature, CryptoJS.SHA256);
        return verified;
    }

    function conexao(){
        $.ajax({
            type: "POST",
            url: 'conex.php',
            data: { testConnection:true, userid: <?php echo $connectionid ?>},
            dataType: "JSON",
            success: function (html){console.log(html);
                if(!html.success){
                    window.location.href = '\?'; //redirecionar para a pagina index
                }else{
                    switch(html.fileEncrypted){
                        case "0":  // normal with hmac

                        break;
                        case "1": // encrypted
                            var lastname = localStorage.getItem('lastFileName');
                            if(lastname != html.fileName){
                                localStorage.setItem('lastFileName',html.fileName);
                                var cleantext = decrypt(localStorage.getItem('sessAlgo'),html.fileContents, localStorage.getItem('sessKey'),localStorage.getItem('sessOpt'));
                                $('<div class="file d-flex flex-row justify-content-start">\
                                    <div class="border rounded p-3 m-3 mw-100 position-relative bg-light">\
                                        <div>\
                                            <small>\
                                                <div class="d-inline-block status-img"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-short" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/></svg></div>\
                                                <p class="status d-inline">Encrypted <a href="'+cleantext+'" download="'+html.fileName+'" onclick="received()">Download</a></p>'+
                                            '</small>'+
                                            '<p class="file-title m-0">'+html.fileName+'</p></div></div></div>').appendTo($('#files-uploaded')).get(0).scrollIntoView();
                            }
                            
                        break;
                        case "2": // signed
                            var lastname = localStorage.getItem('lastFileName');
                            if(lastname != html.fileName){
                                localStorage.setItem('lastFileName',html.fileName);
                                console.log(verify(html.fileContents,html.pubKey,html.signature));
                                if(verify(html.fileContents,html.pubKey,html.signature)){
                                    $('<div class="file d-flex flex-row justify-content-start">\
                                    <div class="border rounded p-3 m-3 mw-100 position-relative bg-light">\
                                        <div>\
                                            <small>\
                                                <div class="d-inline-block status-img"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-short" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"/></svg></div>\
                                                <p class="status d-inline">Verified <a href="'+html.fileContents+'" download="'+html.fileName+'" onclick="received()">Download</a></p>'+
                                            '</small>'+
                                            '<p class="file-title m-0">'+html.fileName+'</p></div></div></div>').appendTo($('#files-uploaded')).get(0).scrollIntoView();
                                }else{
                                    console.log('File not verified');
                                }
                                
                            }
                        break;
                    }
                }
            },
            error: function (html){console.log(html);
                //window.location.href = '\?';
            }
        });
    }
    function received(){
        console.log("received");
        $.ajax({
            type: "POST",
            url: 'conex.php',
            data: {received:true},
            dataType: "JSON",
            success: function (html){
                if(html.success){
                    $('.file.justify-content-start:last-child .status').html('Downloaded');
                    $('.file.justify-content-start:last-child .status-img').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-check" viewBox="0 0 16 16"><path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/><path d="M15.854 10.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.707 0l-1.5-1.5a.5.5 0 0 1 .707-.708l1.146 1.147 2.646-2.647a.5.5 0 0 1 .708 0z"/></svg>');
                    localStorage.setItem('lastFileName','');
                }
            },
            error: function (html){
                window.location.href = '\?';
            }
        });
    }
    

    setInterval(() => {
        conexao();
    }, 5000);

    function HexToWord(word){return CryptoJS.enc.Hex.parse(word);}
    /**
        * Function to decrypt
        * 
        * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
        * @param cleantext encrypted text to decrypt
        * @param hexKey key in hexadecimal value
        * @param modeName name of the module
        * @param dashiv iv vector 
        */

    function decrypt(algoName="", cyphertext, hexKey, modeName="", dashiv=null){
        var key = HexToWord(hexKey);
        var arrFuncName=["AES","3DES","DES","RC4"];
        var arrFunc=[CryptoJS.AES.decrypt,CryptoJS.TripleDES.decrypt,CryptoJS.DES.decrypt,CryptoJS.RC4.decrypt];
        var algoid = arrFuncName.indexOf(algoName);
        if(algoid == -1){
        console.log("Algo Not Found\n---Decrypting with AES algo---");
        algoid = 0;
        }
        var arrModeName = ["CBC","ECB"];
        var arrMode = [CryptoJS.mode.CBC, CryptoJS.mode.ECB];
        var options;
        var modeid = arrModeName.indexOf(modeName);
        if(modeid == -1){
            if(algoid <= 2)
                console.log("Mode Not Found\n---Decrypting with CBC mode---");
            
            modeid == 0;
        }
        if(dashiv == null)
            dashiv = HexToWord(CryptoJS.lib.WordArray.random(128 / 8));
            options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};
        
        var key = HexToWord(hexKey);
        if(algoid <= 2)
        return arrFunc[algoid](cyphertext,key,options).toString(CryptoJS.enc.Utf8);

        return arrFunc[algoid](cyphertext,key).toString(CryptoJS.enc.Utf8);
    }
    /**
        * Function to encrypt
        * 
        * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
        * @param cleantext Text to encrypt
        * @param hexKey key in hexadecimal value
        * @param modeName name of the module
        * @param dashiv iv vector 
        */
    function encrypt(algoName="",cleantext,hexKey,modeName="",dashiv=null){
        var key = HexToWord(hexKey);
        var arrFuncName=["AES","3DES","DES","RC4"];
        var arrFunc=[CryptoJS.AES.encrypt,CryptoJS.TripleDES.encrypt,CryptoJS.DES.encrypt,CryptoJS.RC4.encrypt];
        var algoid = arrFuncName.indexOf(algoName);
        if(algoid == -1){
        console.log("Algo Not Found\n---Encrypting with AES algo---");
        algoid = 0;
        }
        var arrModeName = ["CBC","ECB"];
        var arrMode = [CryptoJS.mode.CBC, CryptoJS.mode.ECB];
        var options;
        var modeid = arrModeName.indexOf(modeName);
        if(modeid == -1){
        if(algoid <= 2)
            console.log("Mode Not Found\n---Encrypting with CBC mode---");
        
        modeid == 0;
        }
        if(dashiv == null)
            dashiv = HexToWord(CryptoJS.lib.WordArray.random(128 / 8));
            options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};

        var key = HexToWord(hexKey);
        if(algoid <= 2)
        return arrFunc[algoid](cleantext,key,options).toString();

        return arrFunc[algoid](cleantext,key).toString();
    }
    
    var forceSend = false;
    $('#sendfile').on('click',function(){
        if($('#fileChoosed').get(0).files.length == 0){
            var toasterror = $('<div class="toast bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                    <div class="toast-header">\
                                        <strong class="me-auto">Warning!</strong>\
                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                    </div>\
                                    <div class="toast-body">\
                                        You have to select a file to send.\
                                    </div>\
                                </div>');
            $('.toast-container').append(toasterror);
            var terror = new bootstrap.Toast(toasterror);
            terror.show();
            return;
        }
        $.ajax({
            type: "POST",
            url: 'conex.php',
            data: { verifySender: true},
            dataType: "JSON",
            success: function (html){
                console.log(html);
                if(html.success || forceSend){
                    var encrypted;
                    
                    var file = $('#fileChoosed').prop('files')[0];
                    var filename = file.name;
                    if(forceSend){
                        $('.file.justify-content-end:last-child .status').html('Uploading...');
                        $('.file.justify-content-end:last-child .status-img').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"/></svg>');
                        $('.file.justify-content-end:last-child .file-title').html(filename);
                    }else{
                        console.log('ola');
                        var h = '<div class="file d-flex flex-row justify-content-end">\
                            <div class="border rounded p-3 m-3 mw-100 position-relative">\
                                <div>\
                                    <small>\
                                        <div class="d-inline-block status-img"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 10a.5.5 0 0 0 .5-.5V3.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 3.707V9.5a.5.5 0 0 0 .5.5zm-7 2.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1-.5-.5z"/></svg></div>\
                                        <p class="status d-inline">Uploading...</p>\
                                    </small>\
                                    <p class="file-title m-0">'+filename+'</p></div></div></div>';
                        $(h).appendTo($('#files-uploaded')).get(0).scrollIntoView();
                        //TODO - put the files into local storage
                    }
                    var enc = $('[name=btnradioenc]:checked').val();
                    console.log(enc);
                    var reader = new FileReader();
                    reader.onload = function() {
                        switch(enc){
                            case "0": //normal

                            break;
                            case "1": //encrypt
                                var key = localStorage.getItem('sessKey');
                                var mode = localStorage.getItem('sessOpt');
                                var algo = localStorage.getItem('sessAlgo');
                                var encrypted = encrypt(algo,reader.result,key,mode);
                                //var decrypted = decrypt(algo,encrypted,key,mode);
                                $.ajax({
                                    type: "POST",
                                    url: 'conex.php',
                                    data: {
                                        sendFileEnc: true,
                                        c_last_file: file.name,
                                        c_encrypted: enc,
                                        file: encrypted
                                    },
                                    dataType: "JSON",
                                    success: function (html){
                                        console.log(html);
                                        if(html.success){
                                            $('.file.justify-content-end:last-child .status').html('Encrypted and Uploaded');
                                            $('.file.justify-content-end:last-child .status-img').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud" viewBox="0 0 16 16"><path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/></svg>');
                                            forceSend = false;
                                        }else{
                                            var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                                                <div class="toast-header">\
                                                                    <strong class="me-auto">Error!</strong>\
                                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                                                </div>\
                                                                <div class="toast-body">\
                                                                    The message could not be sended - internal server error.\
                                                                </div>\
                                                            </div>');
                                            $('.toast-container').append(toasterror);
                                            var terror = new bootstrap.Toast(toasterror);
                                            terror.show();
                                        }
                                    },
                                    error: function (html){
                                        var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                                            <div class="toast-header">\
                                                                <strong class="me-auto">Error!</strong>\
                                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                                            </div>\
                                                            <div class="toast-body">\
                                                                The message could not be sended - internal server error.\
                                                            </div>\
                                                        </div>');
                                        $('.toast-container').append(toasterror);
                                        var terror = new bootstrap.Toast(toasterror);
                                        terror.show();
                                    }
                                });
                            break;
                            case "2": //sign
                                $.ajax({
                                    type: "POST",
                                    url: 'conex.php',
                                    data: {
                                        sendFileSign: true,
                                        file_name: file.name,
                                        file_contents: reader.result
                                    },
                                    dataType: "JSON",
                                    success: function (html){
                                        console.log(html);
                                        if(html.success){
                                            $('.file.justify-content-end:last-child .status').html('Uploaded and Signed');
                                            $('.file.justify-content-end:last-child .status-img').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud" viewBox="0 0 16 16"><path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/></svg>');
                                            forceSend = false;
                                        }else{
                                            var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                                                <div class="toast-header">\
                                                                    <strong class="me-auto">Error!</strong>\
                                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                                                </div>\
                                                                <div class="toast-body">\
                                                                    The message could not be sended - internal server error.\
                                                                </div>\
                                                            </div>');
                                            $('.toast-container').append(toasterror);
                                            var terror = new bootstrap.Toast(toasterror);
                                            terror.show();
                                        }
                                    },
                                    error: function (html){console.log(html);
                                        var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                                            <div class="toast-header">\
                                                                <strong class="me-auto">Error!</strong>\
                                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                                            </div>\
                                                            <div class="toast-body">\
                                                                The message could not be sended - internal server error.\
                                                            </div>\
                                                        </div>');
                                        $('.toast-container').append(toasterror);
                                        var terror = new bootstrap.Toast(toasterror);
                                        terror.show();
                                    }
                                });
                            break;
                        }
                        //retirar a chave
                    }
                    reader.readAsDataURL(file);
                    
                    forceSend = false;
                }else{
                    var toasterror = $('<div class="toast bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                            <div class="toast-header">\
                                                <strong class="me-auto">Warning!</strong>\
                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                            </div>\
                                            <div class="toast-body">\
                                                The message you sended isn\'t received, you will overwrite the message if you send this one. Do you want to continue?\
                                                <div class="mt-2 pt-2">\
                                                <button type="button" class="btn btn-primary btn-sm" onclick="forceSend=true;$(\'#sendfile\').click();">Continue</button>\
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">No</button>\
                                                </div>\
                                            </div>\
                                        </div>');
                    $('.toast-container').append(toasterror);
                    var terror = new bootstrap.Toast(toasterror);
                    terror.show();
                }
            },
            error: function (html){
                console.log(html);
                var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                                        <div class="toast-header">\
                                            <strong class="me-auto">Error!</strong>\
                                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                                        </div>\
                                        <div class="toast-body">\
                                            The message could not be sended - internal server error.\
                                        </div>\
                                    </div>');
                    $('.toast-container').append(toasterror);
                    var terror = new bootstrap.Toast(toasterror);
                    terror.show();
            }
        });
    });

</script>

<?php