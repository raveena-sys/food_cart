@extends('admin::layouts.app')
@section('content')
<?php $current = 'Add Store Master  '; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Store Master </li>
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
                <h4 class="mb-0">Add Store Master </h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">

                    <form id="add_category_form" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate autocomplete="false" action="{{URL::To('admin/manage-store/save')}}">
                        {{csrf_field()}}
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($adminDetail->profile_image,'users')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <input type="file" name="image" id="uploadImage" accept="image/*">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Store Name</label>
                            <input class="form-control" maxlength="250" name="name" type="text" placeholder="Name">
                        </div>

                        <div class="form-group">
                            <label>Short Name</label>
                            <input class="form-control" maxlength="250" name="short_name" type="text" placeholder="Short Name">
                        </div>

                        <div class="form-group">
                            <label>Address 1</label>
                            <input class="form-control" maxlength="250" name="address1" type="text" placeholder="Address 1">
                        </div>

                        <div class="form-group">
                            <label>Address 2</label>
                            <input class="form-control" maxlength="250" name="address2" type="text" placeholder="Address 2">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control" name="country" type="text" id="country">
                                <option>Please select</option>
                                @foreach($country as $v)
                                    <option value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>State</label>
                            <select class="form-control" name="state" type="text" id="state">
                                <option>Please select</option>     
                            </select>                            
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <select class="form-control" name="city" type="text" id="city">
                                <option>Please select</option>     
                            </select>
                        </div>                        

                        <div class="form-group">
                            <label>Pin code</label>
                            <input class="form-control" maxlength="250" name="pincode" type="text" placeholder="Pin code">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" maxlength="250" name="email" type="text" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" maxlength="50" name="password" type="text" placeholder="Password">
                        </div>

                        <div class="form-group">
                            <label>Phone number</label>
                            <input class="form-control" maxlength="50" name="phone_number" type="text" placeholder="Phone number">
                        </div>


                        <div class="form-group">
                            <label>Phone number code</label>
                            <input class="form-control" maxlength="50" name="phone_number_country_code" type="text" placeholder="Phone number code">
                        </div>


                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" maxlength="250" name="description" rows="5" type="text" placeholder="Description"></textarea>
                        </div>
                     
                        <div class="form-group">
                            <label>Store Open/Close Time</label>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <label>Day</label>
                                        <div class="">Sunday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Open Time</label>
                                        <div class=""><input type="text" name='open_time_[sunday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                     <div class="col-md-4">
                                        <label>Close Time</label>
                                        <div class=""><input type="text" name='close_time_[sunday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Monday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[monday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[monday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Tuesday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[tuesday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[tuesday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Wednesday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[wednesday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[wednesday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Thursday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[thursday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[thursday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Friday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[friday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[friday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-4">
                                        <div class="">Saturday</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='open_time_[saturday]' id="open_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class=""><input type="text" name='close_time_[saturday]' id="close_time" value="00:00" class="form-control timepicker"></div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="form-group">
                            <label for="pickup_delivery">Delivery/Pickup</label>
                             <select name="pickup_delivery" id="pickup_delivery" class="form-control" >
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                                <option value="both">Both</option>
                             </select>
                            
                        </div>
                        <!-- <div class="form-group delivery_charge" style="display:none;">
                            <label for="delivery_charge" >Free Delivery upto (km)</label>
                            <input class="form-control" name="free_del_km" id="free_del_km" type="number" placeholder="1 km" min="0">
                            
                        </div> -->
                        <div class="form-group delivery_charge" style="display:none;">
                            <label for="delivery_charge" >Delivery charge</label>
                            <input class="form-control" name="delivery_charge" type="text" placeholder="0.00">
                            
                        </div>

                        <div class="form-group text-center mb-0">
                            <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" type="submit">SAVE<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                            <a id="btnCancel" class="btn btn-danger ripple-effect text-uppercase min-w130" href="{{ URL::To('admin/manage-store') }}">Cancel<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></a>

                            <!--  -->
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddStoreMasterRequest','#add_category_form')
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

$('.timepicker').timepicker({
    timeFormat: 'HH:mm',
    interval: 30,
    minTime: '00:00',
    maxTime: '23:30',
    defaultTime: '00:00',
    startTime: '00:00',
    dynamic: false,
    dropdown: true,
    scrollbar: true
});

$(document).on('change','#pickup_delivery', function(){
    if($(this).val()=='delivery' || $(this).val()=='both'){
        $('.delivery_charge').show();
    }else{
        $('.delivery_charge').hide();
    }
});

$(document).on('change','#country', function(){
    var $id =  $(this).val();
    $.ajax({
        url:"{{url('/admin/getStateList')}}",
        data:{id:$id},
        type:'get',
        success:function(data){
            $('#state').empty().html(data);
        },error:function(e){
            console.log("e", e);
        }
    })
});


$(document).on('change','#state', function(){
    var $id =  $(this).val();
    $.ajax({
        url:"{{url('/admin/getCityList')}}",
        data:{id:$id},
        type:'get',
        success:function(data){
            $('#city').empty().html(data);
        },error:function(e){
            console.log("e", e);
        }
    })
});

</script>
@endsection
