@extends('admin::layouts.app')
@section('content')
<?php $current = 'Store Master  '; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> Store Master </li>
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
                <h4 class="mb-0"> Store Master </h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">

                    <form id="add_category_form" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate autocomplete="false" action="{{URL::To('admin/manage-store/update')}}">
                        {{csrf_field()}}

                        <input class="form-control" name="id" type="hidden" value="{{$detail->id}}">

                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($detail->image,'users')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <input type="file" name="image" id="uploadImage" accept="image/*">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" name="name" disabled="disabled" readonly="true" type="text" value="{{$detail->name}}" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label>Short Name</label>
                            <input class="form-control" name="short_name" readonly="true" disabled="disabled" type="text" value="{{$detail->short_name}}" placeholder="Short Name">
                        </div>

                        <div class="form-group">
                            <label>Address 1</label>
                            <input class="form-control" name="address1" readonly="true" disabled="disabled" type="text" value="{{$detail->address1}}" placeholder="Address 1">
                        </div>

                        <div class="form-group">
                            <label>Address 2</label>
                            <input class="form-control" name="address2" readonly="true" disabled="disabled" type="text" value="{{$detail->address2}}" placeholder="Address 2">
                        </div>

                        <div class="form-group">
                            <label>Country</label>
                            <input class="form-control" name="country" readonly="true" disabled="disabled" type="text" value="{{$detail->country->name}}" placeholder="Country">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input class="form-control" name="state" readonly="true" disabled="disabled" type="text" value="{{$detail->state->name}}" placeholder="State">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control" name="city" readonly="true" disabled="disabled" type="text" value="{{$detail->city->name}}" placeholder="City">
                        </div>

                        <div class="form-group">
                            <label>Pin code</label>
                            <input class="form-control" name="pincode" readonly="true" disabled="disabled" type="text" value="{{$detail->pincode}}" placeholder="Pin code">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" name="email" readonly="true" disabled="disabled" type="text" value="{{$detail->email}}" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label>Phone number</label>
                            <input class="form-control" name="phone_number" readonly="true" disabled="disabled" type="text" value="{{$detail->phone_number}}" placeholder="Phone number">
                        </div>


                        <div class="form-group">
                            <label>Phone number code</label>
                            <input class="form-control" name="phone_number_country_code" readonly="true" disabled="disabled" type="text" value="{{$detail->phone_number_country_code}}" placeholder="Phone number code">
                        </div>


                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="5" readonly="true" disabled="disabled" type="text" placeholder="Description">{{$detail->description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Delivery/Pickup</label>
                            <input class="form-control" name="phone_number_country_code" readonly="true" disabled="disabled" type="text" value="{{isset($detail->pickup_delivery)?ucfirst($detail->pickup_delivery):''}}">
                        </div>
                        <div class="form-group" style="{{(isset($detail->pickup_delivery) && ($detail->pickup_delivery == "delivery" || $detail->pickup_delivery == "both"))?'display:block;':'display:none;'}}">
                            <label>Free delivery upto (KM)</label>
                            <input class="form-control" name="phone_number_country_code" readonly="true" disabled="disabled" type="text" value="{{isset($detail->free_del_upto)?$detail->free_del_upto:''}}">
                        </div>
                        <div class="form-group" style="{{(isset($detail->pickup_delivery) && ($detail->pickup_delivery == "delivery" || $detail->pickup_delivery == "both"))?'display:block;':'display:none;'}}">
                            <label>Delivery charge</label>
                            <input class="form-control" name="phone_number_country_code" readonly="true" disabled="disabled" type="text" value="{{isset($detail->delivery_charge)?$detail->delivery_charge:''}}">
                        </div>
                        <div class="form-group text-center mb-0">
                            <a id="btnCancel" class="btn btn-danger ripple-effect text-uppercase min-w130" href="{{ URL::To('admin/manage-store') }}">Cancel<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></a>

                            <!--  -->
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditStoreMasterRequest','#add_category_form')
                    !!}
                </div>

            </div>
        </div>
        <!-- xx -->
    </div>
    </div>
</main>

@endsection

@section('js')
<script>
    /** Show image preview */

    $("#uploadImage").change(function() {
        readURL(this);
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
</script>
@endsection
