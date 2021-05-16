<?php


    include "../header.php";
    
?>
<script>$('head title').text($('head title').text()+" - Sign Up");</script>
<section class="col-sm-8 col-md-6 col-xl-4 p-4 mt-4 border border-dark rounded-2 mx-auto">
    <h1 class="text-center">Mr. Smith</h1>          
    <form method="post" action="" class="form-container needs-validation" novalidate>
        <p class="h3 text-left">Sign up</p>
        <div class="mb-3">
            <span>Username:</span>
            <input type="username" class="form-control m-1" id="username" name="username" placeholder="Username" required>
            <div class="invalid-feedback">
                Please provide a valid username.
            </div>
        </div>
        <div class="mb-3">
            <span>E-mail:</span>
            <input type="email" class="form-control m-1" id="email" name="email" placeholder="E-mail" required>
            <div class="invalid-feedback">
                Please provide a valid email.
            </div>
        </div>
        <div class="mb-3">
            <span>Password:</span>
            <input type="password" class="form-control m-1" id="pwd1" name="pwd" placeholder="Password" required>
            <div class="invalid-feedback">
                Please provide a password.
            </div>
        </div>
        <div class="mb-3">
            <span>Verify Password:</span>
            <input type="password" class="form-control m-1" id="pwd2" name="repwd" placeholder="Repeat the Password" required>
            <div class="invalid-feedback">
                Please provide a valid password verification.
            </div>
        </div>
        <div class="mb-3">
            <span>Signing Key Size:</span>
            <select class="form-control m-1" id="keysize" required>
                <option value="" selected disabled>Select an option</option>
                <option value="512">512 Bits</option>
                <option value="1024">1024 Bits (Recommended)</option>
                <option value="2048">2048 Bits</option>
                <option value="4096">4096 Bits</option>
            </select>
            <div class="invalid-feedback">
                Please provide a Key Size.
            </div>
        </div>
        <div class="text-center">
            <button type="submit"  class="btn btn-primary">Submit</button>
            <a class="btn btn-secondary" href="/login/" role="button">Login</a>
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
<div class="toast-container position-absolute bottom-0 end-0 p-5"></div>
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
        if ($('#pwd1').val() === $('#pwd2').val()) {
            $('#pwd2').get(0).setCustomValidity('');
        } else {
            $('#pwd2').get(0).setCustomValidity('Passwords do not match');
        }
        if (form.checkValidity() === true) {
            var data = {
                username: $('#username').val(),
                pwd1: $('#pwd1').val(),
                pwd2: $('#pwd2').val(),
                email: $('#email').val(),
                keysize: $("#keysize").val()
            }
            $.ajax({
                type: "POST",
                url: 'signup.php',
                data: data,
                /*dataType: "JSON",*/
                success: function (html){console.log(html);
                    /*if(html.sucess){
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
                    }*/
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
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<?php
    include "../footer.php";
?>