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
    </script>
    <script> // type="text/javascript" is unnecessary in html5

    // Short version of doing `$(document).ready(function(){`
    // and safer naming conflicts with $
    jQuery(function($) { 
        function HexToWord(word){return CryptoJS.enc.Hex.parse(word);}
        /**
         * Function to encrypt
         * 
         * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
         * @param cleantext Text to encrypt
         * @param keySize size of the key (128,192,256)
         * @param hexKey key in hexadecimal value
<<<<<<< HEAD
         * @param 
=======
         * @param modeName name of the module
         * @param dashiv iv vector 
>>>>>>> b5ca7977f9b3e052310c6491f11d24d510216b43
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
              dashiv = HexToWord(CryptoJS.lib.WordArray.random(128 / 8));
              options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};

          var key = HexToWord(hexKey);
          console.log(arrFunc[algoid](cleantext,key,options).toString());
          if(algoid <= 2)
            return arrFunc[algoid](cleantext,key,options).toString();

          return arrFunc[algoid](cleantext,key).toString();
        }
        /**
<<<<<<< HEAD
         * Function to encrypt
         * 
         * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
         * @param cleantext Text to encrypt
         * @param keySize size of the key (128,192,256)
=======
         * Function to decrypt
         * 
         * @param algoName Name of the algorithm (AES,3DES,DES,RABBIT,RC4,RC4DROP)
         * @param cleantext encrypted text to decrypt
         * @param keySize size of the key (128,192,256)
         * @param hexKey key in hexadecimal value
         * @param modeName name of the module
         * @param dashiv iv vector 
>>>>>>> b5ca7977f9b3e052310c6491f11d24d510216b43
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
              dashiv = HexToWord(CryptoJS.lib.WordArray.random(128 / 8));
              options = {mode: arrMode[modeid],iv: HexToWord(dashiv)};
          
          var key = HexToWord(hexKey);
          if(algoid <= 2)
            return arrFunc[algoid](cyphertext,key,options).toString(CryptoJS.enc.Utf8);

          return arrFunc[algoid](cyphertext,key).toString(CryptoJS.enc.Utf8);
        }
        $('#file-input').on('change', function() {

            // You can't use the same reader for all the files
            // var reader = new FileReader
            localStorage.setItem('encrypt',true);
            
              $.each(this.files, function(i, file) {

                  // Uses different reader for all files
                  var reader = new FileReader();
                  // para criar uma chave -> CryptoJS.SHA3(CryptoJS.lib.WordArray.random(128 / 8), { outputLength: 128 }).toString()
                  reader.onload = function() {
                      //retirar a chave
                      if(localStorage.getItem('encrypt')){
                        localStorage.setItem('sessKeySize','128');
                        localStorage.setItem('sessKey',CryptoJS.SHA3(CryptoJS.lib.WordArray.random(128 / 8), { outputLength: 128 }).toString());
                        localStorage.setItem('encMode','ECB');
                        localStorage.setItem('encAlgo','AES');
                        var keySize = localStorage.getItem('sessKeySize');
                        var key = localStorage.getItem('sessKey');
                        var mode = localStorage.getItem('encMode');
                        var algo = localStorage.getItem('encAlgo');
                        var encrypted = encrypt(algo,reader.result,keySize,key,mode);
                        var decrypted = decrypt(algo,encrypted,keySize,key,mode);
                        $("#thelist").html(decrypted.toString(CryptoJS.enc.Utf8));
                      }
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