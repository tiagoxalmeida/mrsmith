<?php
    if(session_status() == PHP_SESSION_NONE){
        //session has not started
        session_start();
    }
    if(isset($_SESSION['u_id'])){
        header("Location: https://mrsmith.ml/inside/");
        exit;
    }
    include "../header.php";
?>
<script>$('head title').text($('head title').text()+" - Login");</script>
<section class="col-sm-8 col-md-6 col-xl-4 p-4 mt-5 border border-dark rounded-2 mx-auto"> 
    <h1 class="text-center">Mr. Smith</h1>
    <form class="form-container needs-validation" method="post" action="" novalidate>
        <p class="h3">Login</p>
        <div class="mb-3">
            <span>Username:</span>
            <input type="username" class="form-control m-1" id="username" name="username" placeholder="Username" required>
            <div class="invalid-feedback">
                Please provide a valid username.
            </div>
        </div>
        <div class="mb-3">
            <span>Password:</span>
            <input type="password" class="form-control m-1" name="pwd" id="pwd" placeholder="Password" required>
            <div class="invalid-feedback">
                Please provide a password.
            </div>
        </div>
        <div class="mb-3">
            <span>Encryption Key Size:</span>
            <select class="form-control m-1" id="keysize" required>
                <option value="" selected disabled>Select an option</option>
                <option value="512">512 Bits</option>
                <option value="1024">1024 Bits</option>
                <option value="2048">2048 Bits</option>
                <option value="4096">4096 Bits</option>
            </select>
            <div class="invalid-feedback">
                Please provide a Key Size.
            </div>
        </div>
        <div class="text-center">
            <button type="submit"  class="btn btn-primary">Submit</button>
            <a class="btn btn-secondary" href="/sign-up/" role="button">Sign up</a>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <div class="d-inline-block text-left">
                <a type="button" class="btn btn-info" href="/" role="button">Home</a>
            </div>
            <div class="d-inline-block text-right">
                <a type="button" class="btn btn-light float-right" href="/help/" role="button">Help</a>
            </div>
        </div>
    </form>
</section>
<div class="toast-container position-absolute bottom-0 end-0 p-5">
    <div class="toast" id="loadingToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto"></strong>
            <small class="text-muted"><span>0</span> s</small>
        </div>
        <div class="toast-body">
        </div>
    </div>
</div>
<script>
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
        if (form.checkValidity() === true) {
            generateKeys($('keysize').val());
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
$('#loadingToast').on('hidden.bs.toast',function(){
    $('.toast-container').attr('class',' toast-container position-absolute bottom-0 end-0 p-5');
    $('#loadingToast .text-muted').html("<span>0</span> s");
    $(this).removeClass();
    $(this).addClass('toast');
});
var options = {
    animation: true
};
var toast = new bootstrap.Toast($('#loadingToast'),options);
function RSAencrypt(publicKey, text){
    var crypt = new JSEncrypt();
    crypt.setPublicKey(publicKey);
    return crypt.encrypt(text);
}

function generateKeys(keySize){
    $('#loadingToast .toast-body').html('<div class="text-center my-1">\
                <div class="spinner-border" role="status">\
                </div> \
            </div>\
            <div class="text">Your browser is creating the RSA encription keys at the keysize you choosed. Wait a moment please.</div>');
    $('#loadingToast .me-auto').html('Creating Keys');
    toast.show();
    //$('#time-report').html('<div class="text-center"><div class="spinner-border m-1" role="status"></div><p>Generating Keys ...</p></div>');
    var crypt = new JSEncrypt({default_key_size: keySize});
    var dt = new Date();
    var time = -(dt.getTime());
    var load = setInterval(function () {
        var text = $('#loadingToast .text-muted span').html();
        $('#loadingToast .text-muted span').html((parseInt(text) + 1));
    }, 1000);
    crypt.getKey(function () {
        clearInterval(load);
        dt = new Date();
        time += (dt.getTime());
        localStorage.setItem('pk_encrypt', crypt.getPrivateKey());
        localStorage.setItem("Pk_encrypt", crypt.getPublicKey());
        $('.toast.showing').removeClass('showing');
        sendToServer(localStorage.getItem("Pk_encrypt"),localStorage.getItem("pk_encrypt"));
        toast.hide();
    });
    return;
}
function sendToServer(publicKey,privateKey){

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
        username: $('#username').val(),
        pwd: $('#pwd').val()
    }; 
    $.ajax({
        type: "POST",
        url: 'login.php',
        data: data,
        dataType: "JSON",
        success: function (html){console.log(html);
            if(html.success){
                var options = {
                    animation: true,
                    autohide: false
                };
                var toasterror = $('<div class="toast bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                    <div class="toast-header">\
                        <strong class="me-auto">Success</strong>\
                        <small class="text-muted">Just Now</small>\
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                    </div>\
                    <div class="toast-body text-center">\
                        <h6 class="d-inline-block">We are taking you to the main page.</h6>\
                    </div>\
                </div>');
                $('.toast-container').append(toasterror);
                var terror = new bootstrap.Toast(toasterror);
                terror.show();
                var forms = document.getElementsByClassName('was-validated')[0];
                forms.className = "needs-validation";
                $('#username').val('');
                $('#pwd').val('');
                window.setTimeout(() => {
                    window.location.href = "/inside/";
                }, 3000);
            }else{
                var options = {
                    animation: true,
                    autohide: false
                };
                var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                    <div class="toast-header">\
                        <strong class="me-auto">Error</strong>\
                        <small class="text-muted">Just Now</small>\
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                    </div>\
                    <div class="toast-body text-center">\
                        <h6 class="d-inline-block">'+html.error+'</h6>\
                    </div>\
                </div>');
                $('.toast-container').append(toasterror);
                var terror = new bootstrap.Toast(toasterror);
                terror.show();
                var forms = document.getElementsByClassName('was-validated')[0];
                forms.className = "needs-validation";
                $('#username').val('');
                $('#pwd').val('');
            }
        },
        error: function (html){console.log(html);
            var options = {
                animation: true,
                autohide: false
            };
            var toasterror = $('<div class="toast bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">\
                <div class="toast-header">\
                    <strong class="me-auto">Error</strong>\
                    <small class="text-muted">Just Now</small>\
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>\
                </div>\
                <div class="toast-body text-center">\
                    <h6 class="d-inline-block">Internal Server Error</h6>\
                </div>\
            </div>');
            $('.toast-container').append(toasterror);
            var terror = new bootstrap.Toast(toasterror);
            terror.show();
            var forms = document.getElementsByClassName('was-validated')[0];
            forms.className = "needs-validation";
            $('#username').val('');
            $('#pwd').val('');
        }
    });
}
</script>

<?php
    
    include "../footer.php";
?>