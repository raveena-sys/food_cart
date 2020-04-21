@extends('store::layouts.app')
@section('content')
<?php $current = 'Profile setting'; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile Setting</li>
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
                <h4 class="mb-0">Profile Setting</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <form id="update_profile_form" action="{{ url('store/profile-update') }}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($adminDetail->profile_image,'users')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <input type="file" name="profile_image" id="uploadImage" accept="image/*">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{!empty($adminDetail)?$adminDetail->name:''}}" class="form-control" placeholder="Name">
                                </div>
                            </div>
                            <!-- xxxxx -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" value="{{!empty($adminDetail)?$adminDetail->email:''}}" class="form-control" disabled placeholder="Email">
                                </div>
                            </div>
                            <!-- xxxxx -->

                        </div>
                        <div class="btn_row text-center">
                            <button id="profile-btn" type="submit" class="btn btn-danger ripple-effect text-uppercase">
                                Update
                                <span id="profile-btn-loader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Store\Http\Requests\UpdateProfileRequest','#update_profile_form') !!}
                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</main>
<script>
    $('#update_profile_form').submit(function() {
        if ($('#update_profile_form').valid()) {
            $('#profile-btn-loader').show();
            $('#profile-btn').prop('disable', true);
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var type = input.files[0].type;
            var size = input.files[0].size;
            if (type == 'image/png' || type == 'image/jpg' || type == 'image/jpeg') {
                if (size <= 2097152) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.rounded-circle').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    toastr.error('The profile picture may not be greater than 2MB.');
                    ('error', 'The profile picture may not be greater than 2MB.');
                    $('#uploadImage').val("");
                }
            } else {
                toastr.error('The profile picture must be a file of type: jpeg, png, jpg.');
                $('#uploadImage').val("");
            }
        }
    }
    $("#uploadImage").change(function() {
        readURL(this);
    });
</script>
@endsection
