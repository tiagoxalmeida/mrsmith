<script src="/js/jsencrypt.min.js"></script>
<script>
function RSAencrypt(publicKey, text){
    var crypt = new JSEncrypt();
    crypt.setPublicKey(publicKey);
    return crypt.encrypt(text);
}

function RSAdecrypt(privateKey, text){
    var crypt = new JSEncrypt();
    crypt.setPrivateKey(privateKey);
    return crypt.decrypt(text);
}

var v = RSAencrypt(localStorage.getItem('Pk_encrypt'),"texto");
console.log(v);
var d = RSAdecrypt(localStorage.getItem('pk_encrypt'), v);
console.log(d);

</script>