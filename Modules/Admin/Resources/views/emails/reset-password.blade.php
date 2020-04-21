<!--
    * Author : [myUser ]
    * Date   : February 2019
    * Description : This page contains are form input for reset password contents.
-->
@extends('admin::layouts.login')
@section('content')
<!-- reset password page -->
<main class="login-page d-flex align-items-center justify-content-center">
            <div class="login-wrap mx-auto position-relative">
                <div class="login common">
                    <div class="logo text-center">
                        <a href="dashboard.php"><img src="{{asset('img/logo_black.png')}}" class="logo"')}}" alt="logo"></a>
                    </div>
                    <h3 class="title">Reset Password</h3>
                    <form action="dashboard.php" class="needs-validation" novalidate autocomplete="false">
                        <div class="form-group">
                            <input type="email" placeholder="Create New Password" autocomplete="false" class="form-control" required="" />
                            <div class="invalid-feedback">
                                Please enter your New Password.
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="password" placeholder="Confirm New Password" autocomplete="false" class="form-control" required=""/>
                            <div class="invalid-feedback">
                                Please enter your Confirm password.
                            </div>
                        </div>
                        
                        <div class="form-group text-center mb-0 pt-sm-3 ">
                            <button type="submit" class="btn btn-danger text-uppercase min-width ripple-effect-dark">Submit</button>
                        </div>
                    </form>
                </div>
                
            </div>
        </main>

<script>
    /** 
	 * Summary of the document.ready function: This function using for reset Password Form.
	 * Parameters   : formData
	 * Return Value : response.message
	 * Description: This function using for reset Password Form.
	 */ 
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
                    } else {
                        toastr.clear(); Command: toastr['success'](response.message);
                        $('#btnForgotPassword').prop('disabled', false);	// Enable reset button
                    }
                    window.location.href = "{{ url('/admin') }}";
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
@endsection