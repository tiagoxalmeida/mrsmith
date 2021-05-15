<?php
   // Test data
   $data = 'Hello world';

   // Encrypt data with public key
   $publicKey = file_get_contents('inc/pub.pem');
   $encrypted = null;
 
   openssl_public_encrypt($data, $encrypted, $publicKey);
 
   echo 'Encrypted data', PHP_EOL;
   echo base64_encode($encrypted), PHP_EOL;
 
   echo PHP_EOL;
 
   // Decrypt data with private key
   $privateKey = file_get_contents('inc/priv.pem');
   $decrypted = null;
 
   openssl_private_decrypt($encrypted, $decrypted, $privateKey);

 
    echo 'Decrypted data', PHP_EOL;
    echo $decrypted, PHP_EOL;
    $pass = "testkey";
    $config = array(
    "private_key_bits"=>512
    );
    echo '<pre>';
    // Create the keypair
    $res=openssl_pkey_new($config);
    openssl_pkey_export($res, $privkey);
    echo $privkey."\n";
    // Get public key
    $pubkey=openssl_pkey_get_details($res);
    //var_dump($res);
    $pubkey=$pubkey["key"];
    echo $pubkey;
    echo '</pre>';
    echo "<pre>";
    print_r(openssl_get_cipher_methods());
    echo "</pre>";