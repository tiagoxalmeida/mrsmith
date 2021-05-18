<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      //print_r($_FILES);
    // Decrypt data with private key
    /*$privateKey = file_get_contents('inc/priv.pem');
    print_r(count($_POST['pk_encrypt']));
    $encryptedKey = $_POST['pk_encrypt'];
    $html = "";
    foreach($encryptedKey as $part){
      $decrypted = null;
      if(!openssl_private_decrypt(base64_decode($part), $decrypted, $privateKey)){
        echo 'Failed';exit;
      }
      $html .= $decrypted;
    }
    echo $html;
    /*
    echo 'Decrypted data', PHP_EOL;
    echo $decrypted, PHP_EOL;*/
      exit;
    }
    ?>
<html>
    <head>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    </head>
    <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/js/crypto-js.min.js" ></script>
    <script src="/js/jsencrypt.min.js"></script>
    <div>
    <form action="" method="post">
      <input type="file" name="files" id="file-input">
      <div id="thelist"></div>
      <input type="button" value="true">
    </form>
      
    </div>
    <script type="text/javascript">
      $(function () {

        //Change the key size value for new keys
        $(".change-key-size").each(function (index, value) {
          var el = $(value);
          var keySize = el.attr('data-value');
          el.click(function (e) {
            var button = $('#key-size');
            button.attr('data-value', keySize);
            button.html(keySize + ' bit <span class="caret"></span>');
            e.preventDefault();
          });
        });

        // Execute when they click the button.
        $('#execute').click(function () {

          // Create the encryption object.
          var crypt = new JSEncrypt();

          // Set the private.
          crypt.setPrivateKey($('#privkey').val());
          //return;
          // If no public key is set then set it here...
          var pubkey = $('#pubkey').val();
          if (!pubkey) {
            $('#pubkey').val(crypt.getPublicKey());
          }

          // Get the input and crypted values.
          var input = $('#input').val();
          var crypted = $('#crypted').val();

          // Alternate the values.
          if (input) {
            $('#crypted').val(crypt.encrypt(input));
            $('#input').val('');
          }
          else if (crypted) {
            var decrypted = crypt.decrypt(crypted);
            if (!decrypted)
              decrypted = 'This is a test!';
            $('#input').val(decrypted);
            $('#crypted').val('');
          }
        });


        /*var generateKeys = function () {
          var sKeySize = $('#key-size').attr('data-value');
          var keySize = parseInt(sKeySize);
          var crypt = new JSEncrypt({default_key_size: keySize});
          var async = $('#async-ck').is(':checked');
          var dt = new Date();
          var time = -(dt.getTime());
          if (async) {
            $('#time-report').text('.');
            var load = setInterval(function () {
              var text = $('#time-report').text();
              $('#time-report').text(text + '.');
            }, 500);
            crypt.getKey(function () {
              clearInterval(load);
              dt = new Date();
              time += (dt.getTime());
              $('#time-report').text('Generated in ' + time + ' ms');
              $('#privkey').val(crypt.getPrivateKey());
              $('#pubkey').val(crypt.getPublicKey());
            });
            return;
          }
          crypt.getKey();
          dt = new Date();
          time += (dt.getTime());
          $('#time-report').text('Generated in ' + time + ' ms');
          $('#privkey').val(crypt.getPrivateKey());
          $('#pubkey').val(crypt.getPublicKey());
        };*/

        // If they wish to generate new keys.
        //$('#generate').click(function (){sendToServer(localStorage.getItem("Pk"));});
        //generateKeys(512);
        /**
         * Function to encrypt
         * 
         * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
         * @param cleantext Text to encrypt
         * @param keySize size of the key (128,192,256)
         * @param hexKey key in hexadecimal value
         * @param 
         */
        function encrypt(algoName="",cleantext,keySize,hexKey,modeName="",dashiv=null){
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
              options = {mode: arrMode[modeid]};
          else
              options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};

          var key = HexToWord(hexKey);

          if(algoid <= 2)
            return arrFunc[algoid](cleantext,key,options).toString();

          return arrFunc[algoid](cleantext,key).toString();
        }
        /**
         * Function to encrypt
         * 
         * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
         * @param cleantext Text to encrypt
         * @param keySize size of the key (128,192,256)
         */

        function decrypt(algoName="", cyphertext, keySize, hexKey, modeName="", dashiv=null){
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
              options = {mode: arrMode[modeid]};
          else
              options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};

          var key = HexToWord(hexKey);
          if(algoid <= 2)
            return arrFunc[algoid](cleantext,key,options).toString(CryptoJS.enc.Utf8);

          return arrFunc[algoid](cleantext,key).toString(CryptoJS.enc.Utf8);
        }

        function HexToWord(word){return CryptoJS.enc.Hex.parse(word);}
        var enc = CryptoJS.AES.encrypt("Message", "Ola").toString();
        console.log(enc);
        console.log(CryptoJS.AES.decrypt(enc, "Ola").toString(CryptoJS.enc.Utf8));
      });
    </script>
    <script> // type="text/javascript" is unnecessary in html5

    // Short version of doing `$(document).ready(function(){`
    // and safer naming conflicts with $
    jQuery(function($) { 

        $('#file-input').on('change', function() {

            // You can't use the same reader for all the files
            // var reader = new FileReader

            $.each(this.files, function(i, file) {

                // Uses different reader for all files
                var reader = new FileReader();

                reader.onload = function() {
                    // reader.result refer to dataUrl
                    // theFile is the blob... CryptoJS wants a string...
                    var encrypted = CryptoJS.AES.encrypt(reader.result, '12334');
                    var decrypted = CryptoJS.AES.decrypt(encrypted.toString(), '12334');
                    $("#thelist").html(decrypted.toString(CryptoJS.enc.Utf8));
                }

                reader.readAsDataURL(file);
                $('#thelist').append('FILES: ' + file.name + '<br>');
            });
        });
        $("[type=button]").on('click',function (){
          download("ola.js",$("#thelist").html());
        });

        function download(filename,data){
          var element = document.createElement('a');
          element.setAttribute('href', data);
          element.setAttribute('download', filename);

          element.style.display = 'none';
          document.body.appendChild(element);

          element.click();

          document.body.removeChild(element);
        }
    })
    </script>
    </body>
</html>