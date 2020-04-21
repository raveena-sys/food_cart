<!doctype html>
<html lang="en">

<head>
    <title>Login</title>
    @include('store::layouts.head')
</head>

<body class="login-body">
    <?php $current = 'login'; ?>
    <main class="login-page d-flex align-items-center justify-content-center">
        <div class="login-wrap mx-auto position-relative">
            <div class="login common">
                <div class="logo text-center">
                    <input type="hidden" value="{{URL::To('store')}}" id="dashboard-url">
                    <a href="dashboard.php"><img src="{{ asset('img/logo_black.png')}}" class="logo"') }}" alt="logo"></a>
                </div>
                <h3 class="title">Log In</h3>
                <form id="loginForm" action="{{ url('store/login') }}" method="post" class="needs-validation" novalidate autocomplete="false">
                    @csrf
                    <div class="form-group">
                        <input type="email" placeholder="Email" value="{{(isset($_COOKIE['email'])?$_COOKIE['email']:'')}}" name="email" autocomplete="false" class="form-control" required="" />
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Password" value="{{(isset($_COOKIE['password'])?$_COOKIE['password']:'')}}" name="password" autocomplete="false" class="form-control" required="" />
                    </div>
                    <div class="form-group position-relative">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember_me" class="custom-control-input" id="remeberme" @if (isset($_COOKIE["email"])) checked="checked" @endif>
                            <label class="custom-control-label" for="remeberme">
                                <span>Remember me</span>
                            </label>
                        </div>
                        <div class="forgotpassword ">
                            <span>Forgot Password?</span>
                            <a href="javascript:void(0);" onclick="forgotPassword()"> </a>
                        </div>
                    </div>

                    <div class="form-group text-center mb-0 pt-sm-3 ">
                        <button type="button" id="loginButton" onclick="submitLogin()" class="btn btn-danger text-uppercase min-width ripple-effect-dark">Login
                            <span id="loginBtnLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                        </button>
                    </div>
                </form>
                {!! JsValidator::formRequest('Modules\Store\Http\Requests\LoginRequest','#loginForm') !!}
            </div>
            <div class="common forgot_password">
                <h6>Forgot Password</h6>
                <form id="forgotPasswordForm" class="needs-validation" novalidate autocomplete="false">                  
                    {{csrf_field()}}
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email" autocomplete="false" class="form-control" required="" />
                    </div>
                    <div class="form-group position-relative">
                        <div class="forgotpassword d-block d-xl-none">
                            <span>Login</span>
                            <a href="javascript:void(0);" onclick="forgotPassword()"> </a>
                        </div>
                    </div>
                    <div class="form-group text-center mb-0 pt-sm-3">
                        <button id="forgotButton" type="submit" onclick="submitForgotPassword()" class="btn btn-danger text-uppercase min-width ripple-effect-dark">Send
                        <span id="forgotBtnLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                    </button>
                    </div>
                </form>
                {!! JsValidator::formRequest('Modules\Store\Http\Requests\ForgotPasswordRequest','#forgotPasswordForm') !!}
            </div>
        </div>
    </main>

</body>

</html>

<script>
    //validations
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                    $('.fa-spin').css('display', 'inline-block');
                    setTimeout(function() {
                        $('.fa-spin').css('display', 'none');
                    }, 2000);
                }, false);
            });
        }, false);
    })();

    function forgotPassword() {
        $(".forgot_password").toggleClass("open");
        $(".login").toggleClass("disabled");
        $(".forgotpassword").toggleClass("forgotpassword_hover");
    };

    $('#loginForm').keydown(function(e) {
        if (e.keyCode === 13) {
            $('#loginButton').trigger('click');
        }
    });

    //Function for admin login
    function submitLogin() {
        if ($('#loginForm').valid()) {
            $('#loginButton').prop('disabled', true); // disable login button	
            $('#loginBtnLoader').show();
            url = "{{url('store/login')}}"; 
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: $('#loginForm').serialize(),
                success: function(result) {
                    $('#loginButton').prop('disabled', false); // enable login button
                    $('#loginBtnLoader').hide();
                    if (result.success == false) {
                        toastr.error(result.message);
                    } else {
                        window.location.href = $('#dashboard-url').val();
                        toastr.success(result.message);
                    }
                },
                error: function(er) {
                    $('#loginButton').prop('disabled', false); // enable login button
                    $('#loginBtnLoader').hide();
                    var errors = $.parseJSON(er.responseText);
                    $.each(errors.errors, function(key, val) {
                        $("#" + key + "-error").text(val);
                    });
                },
                complete: function() {
                    // showButtonLoader('login-button', 'Login', 'enable');
                }
            });
        }
    };

    //Function for forgot password
    function submitForgotPassword() {
        if ($('#forgotPasswordForm').valid()) {
            $('#forgotButton').attr('disabled', true);
            $('#forgotBtnLoader').show();
            var url = "{{url('store/forgot')}}"
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: $('#forgotPasswordForm').serialize(),
                success: function(result) {
                    $('#forgotButton').prop('disabled', false);
                    $('#forgotBtnLoader').hide();
                    if (result.success) {
                        $("#forgotPasswordForm")[0].reset();
                        toastr.success(result.message);
                        $(".forgot_password").toggleClass("open");
                        $(".login").toggleClass("disabled");
                        $(".forgotpassword").toggleClass("forgotpassword_hover");
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(er) {
                    $('#forgotBtnLoader').hide();
                    $("#email_val-error").text(er.message);
                },
                complete: function() {
                    // showButtonLoader('forgot-button', 'Send', 'enable');
                }
            });
        }
    };
</script>