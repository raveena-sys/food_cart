@extends('store::layouts.app')
@section('content')
<?php $current = 'change password'; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
        </div>
        <!-- page title section end -->
        <div class="card">
            <div class="card-header text-center border-0 align-items-center">
                <h4 class="mb-0">Change Password</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <form id="change_password_form" action="{{ url('store/update-password') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="current_password" class="form-control" placeholder="Current Password">
                                </div>
                            </div>
                            <!-- xxxxx -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                </div>
                            </div>
                            <!-- xxxxx -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Confirm password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password">
                                </div>
                            </div>


                            <!-- xxxxx -->
                        </div>
                        <div class="btn_row text-center">
                            <button id="change_password_button" class="btn btn-danger ripple-effect text-uppercase" data-url="{{URL::To('store/update-password')}}">Update
                                <span id="password-btn-loader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Store\Http\Requests\ChangePasswordRequest','#change_password_form') !!}
                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</main>
<script>
    $('#change_password_form').submit(function() {
        if ($('#change_password_form').valid()) {
            $('#password-btn-loader').show();
            $('#change_password_button').prop('disable', true);
        }
    });
</script>

@endsection