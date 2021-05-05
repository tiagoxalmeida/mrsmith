var PubKey;
var chave;


document.getElementById("gerar").onclick = function(){
    chave = cryptico.generateRSAKey("password",1024);
    PubKey = cryptico.publicKeyString(chave);
    document.getElementById("teste").innerHTML = PubKey;
};

document.getElementById("encrypt").onclick = function(){
    var plaintext = document.getElementById("input1").value;
    var ciphertext = cryptico.encrypt(plaintext,PubKey);
    var plaintext2 = cryptico.decrypt(ciphertext.cipher,chave);
    document.getElementById("teste").innerHTML = ciphertext.cipher;
    document.href = "?cipher=" + ciphertext + "&sk=" + chave; 
};

