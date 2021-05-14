<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
      $privateKey = openssl_pkey_get_private($_POST['pk']);
      openssl_private_decrypt(base64_decode($_POST['crypted']),$decrypted,$privateKey);
      echo $decrypted;
      exit;
    }
    ?>
<html>
    <head>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    </head>
    <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/js/jsencrypt.min.js"></script>
    <div class="row">
    
      <div class="panel panel-default">
        <div class="panel-heading"><h1>Online RSA Key Generator</h1></div>
        <div class="panel-body">
          
          <div class="col-lg-2">
            <div class="btn-group">
              <div class="input-group">
                <span class="input-group-addon">Key Size</span>
                <button class="btn btn-default dropdown-toggle" id="key-size" type="button" data-value="1024"
                        data-toggle="dropdown">1024 bit <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a class="change-key-size" data-value="512" href="#">512 bit</a></li>
                  <li><a class="change-key-size" data-value="1024" href="#">1024 bit</a></li>
                  <li><a class="change-key-size" data-value="2048" href="#">2048 bit</a></li>
                  <li><a class="change-key-size" data-value="4096" href="#">4096 bit</a></li>
                </ul>
              </div>
            </div>
            <br>&nbsp;<br>
            <button id="generate" class="btn btn-primary">Generate New Keys</button>
            <br>&nbsp;<br>
            <span><i><small id="time-report"></small></i></span>
            <br>&nbsp;<br>
            <label for="async-ck"><input id="async-ck" type="checkbox"> Async</label>
          </div>
          <div class="col-lg-10">
            <div class="row">
              <div class="col-lg-6">
                <label for="privkey">Private Key</label><br/>
                <small>
                  <textarea id="privkey" rows="15" style="width:100%"></textarea>
                </small>
              </div>
              <div class="col-lg-6">
                <label for="pubkey">Public Key</label><br/>
                <small><textarea id="pubkey" rows="15" style="width:100%" readonly="readonly"></textarea></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading"><h3>RSA Encryption Test</h3></div>
        <div class="panel-body">
          <div class="col-lg-5">
            <label for="input">Text to encrypt:</label><br/>
            <textarea id="input" name="input" style="width: 100%" rows="4">This is a test!</textarea>
          </div>
          <div class="col-lg-2">
            <label>&nbsp;</label><br/>
            <button id="execute" class="btn btn-primary">Encrypt / Decrypt</button>
          </div>
          <div class="col-lg-5">
            <label for="crypted">Encrypted:</label><br/>
            <textarea id="crypted" name="crypted" style="width: 100%" rows="4"></textarea>
          </div>
        </div>
      </div>
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

        function encrypt(publicKey, text){
          var crypt = new JSEncrypt();
          crypt.setPublicKey(publicKey);
          return crypt.encrypt(text);
        }

        function generateKeys(keySize){
          $('#time-report').html('<div class="text-center"><div class="spinner-border m-1" role="status"></div><p>Generating Keys ...</p></div>');
          var crypt = new JSEncrypt({default_key_size: keySize});
          var dt = new Date();
          var time = -(dt.getTime());
          crypt.getKey(function () {
            dt = new Date();
            time += (dt.getTime());
            console.log('Generated in ' + time + ' ms');
            localStorage.setItem('pk',crypt.getPrivateKey());
            localStorage.setItem('Pk',crypt.getPublicKey());
            sendToServer(localStorage.getItem('Pk'););            
            
          });
          return;
        }

        function sendToServer(){
          
          var data = {
            pk: localStorage.getItem('Pk')
          }; 
          $.ajax({
            type: "POST",
            url: '/teste.php',
            data: data,
            /*dataType: "JSON",*/
            success: function (html){$('#time-report').html(html);},
            error: function (html){$('#time-report').html(html);}
          });
        }

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
        $('#generate').click(function (){generateKeys(512)});
        generateKeys(512);
        
      });
    </script>
    
    </body>
</html>