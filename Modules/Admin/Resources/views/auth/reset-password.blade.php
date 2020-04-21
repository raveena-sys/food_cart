<!--
    * Author : [myUser ]
    * Date   : February 2019
    * Description : This page contains are form input for reset password contents.
-->
@include('admin::layouts.head')

<!-- reset password page -->
<body class="login-body">
<main class="login-page d-flex align-items-center justify-content-center">
            <div class="login-wrap mx-auto position-relative">
                <div class="login common">
                    <div class="logo text-center">
                        <a href="dashboard.php"><img src="{{ asset('img/logo_black.png')}}" class="logo" alt="logo"></a>
                    </div>
                    <h3 class="title">Reset Password</h3>
                    <form id="resetPasswordForm" action="{{url('admin/reset-password')}}" class="needs-validation" novalidate autocomplete="false">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="verify_token" value="{{$token}}">
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Create New Password" autocomplete="false" class="form-control" required="" />                           
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" placeholder="Confirm New Password" autocomplete="false" class="form-control" required=""/>                           
                        </div>
                        
                        <div class="form-group text-center mb-0 pt-sm-3 ">
                            <button type="submit" class="btn btn-danger text-uppercase min-width ripple-effect-dark">Submit</button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\ResetPasswordRequest','#resetPasswordForm') !!}
                </div>
                
            </div>
        </main>
        </body>

        <script>
        //validations
        $(document).on('submit', '#resetPasswordForm', function (e) {
        e.preventDefault();
        if ($('#resetPasswordForm').valid()) {
            $('#btnResetPassword').prop('disabled', true);					// disable reset button	
            $('#btnResetLoader').show();
            $.ajax({
                url: "{{url('admin/reset-password')}}",
                data: $('#resetPasswordForm').serialize(),					// get all form data
                type: 'POST',
                dataType: 'JSON',
                success: function (response) {
                    if (response.success) {
                        toastr.clear(); Command: toastr['success'](response.message);
                        window.location.href = "{{ url('/admin') }}";
                    } else {
                        toastr.clear(); Command: toastr['error'](response.message);
                        $('#btnForgotPassword').prop('disabled', false);	// Enable reset button
                    }
                    
//                    $('#btnResetLoader').hide();							// hide forgot Form Loader
                },
                error: function (err) {
                    var obj = jQuery.parseJSON(err.responseText);
                    for (var x in obj) {
                        $('#btnResetLoader').hide();						// hide forgot Form Loader
                        $('#btnResetPassword').prop('disabled', false);		// Enable reset button
                        toastrAlertMessage('error', obj[x]);				// show error message
                    }
                }
            });
        }
    });
        </script>
