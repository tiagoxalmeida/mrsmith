<html>
    <head></head>
    <body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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

    var generateKeys = function () {
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
    };

    // If they wish to generate new keys.
    $('#generate').click(generateKeys);
    generateKeys();
  });
</script>
</html>