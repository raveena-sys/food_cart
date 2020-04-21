@extends('store::layouts.app')
@section('content')
<?php $current = 'Topping Master'; ?>
<main class="main-content add-page">
        <div class="container-fluid">
             <!-- page title section start -->
            <div class="page-title-row">
                <div class="left-side">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/store')}}">Dashboard</a></li>
                             <li class="breadcrumb-item active" aria-current="page">Topping Master</li>
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
                    <h4 class="mb-0">Topping Master</h4>
                </div>
                <div class="card-body">
                    <div class="inner_cnt">
                       <form id="toppingform" action="{{ url('store/getData') }}"  method ="POST" enctype="multipart/form-data" >
                       {{csrf_field()}}
                            <div class="upload_photo mx-auto text-center col-12">
                            {{-- <div class="img-box">
                                <img src="" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <input type="file" name="profile_image" id="uploadImage" accept="image/*">
                                <i class="icon-photo_camera"></i>
                            </label> --}}
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Food Type</label>
                                    <select name="foodtype" id="foodtype" class="form-control" >
                                            <option value="">foodtype</option>
                                        <option value="veg">Veg</option>
                                        <option value="non_veg">Non Veg</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" id="name" value="" class="form-control" placeholder="Name" >
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="price" id="price" value="" class="form-control" placeholder="Price" >
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Thumb Image</label>
                                    <input type="file" name="thumb_img" id="thumb_img" value="" class="form-control" placeholder="Name" >
                                </div>
                            </div>
                            <!-- xxxxx -->
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Image</label>
                                    <input type="file" name="image" id="image" value="" class="form-control" placeholder="Name" >
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" id="status" class="form-control" >
                                        <option value="active">Active</option>
                                        <option value="pending">Pending</option>
                                      </select>
                                </div>
                            </div>
                            <!-- xxxxx -->

                        </div>
                        <div class="btn_row text-center">
                            <button id="profile-btn" type= "submit" name="submit" class="btn btn-danger ripple-effect text-uppercase">
                                Submit
                                <span id="profile-btn-loader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                       </form>
                       {!! JsValidator::formRequest('Modules\Store\Http\Requests\ToppingMasterRequest','#toppingform') !!}
                    </div>
                </div>
                <!-- xx -->
            </div>
        </div>
    </main>


{{-- <script>
    $(document).ready(function(){
        var token = $('#_token').val();

      $("#profile-btn1").click(function(){


    //    if (food_type_master_id = " "){
    //        alert("please fill foodtype." );
    //        return;
    //    }
    //    if ($('#name').val() = " "){
    //        alert("please fill name." );
    //        return;
    //    }
    //    if ($('#price').val() = " "){
    //        alert("please fill price." );
    //        return;
    //    }

            // var formdata=new FormData($("#topping_form")[0]);
            // console.log(formdata);
             var food_type_master_id= $('#foodtype').val();
            var name = $('#name').val();
            var price = $('#price').val();
            var thumb_image = $('#thumb_image').val();
            var image = $('#image').val();
            var status = $('#status').val();
        var url = "{{url('store/insert/getData')}}";

        $.ajax({
                type: 'post',
                dataType: "JSON",
                data: {
                    food_type_master_id: food_type_master_id,
                    name: name,
                    price: price,
                    thumb_image: thumb_image,
                    image: image,
                    status: status,
                    "_token": "{{ csrf_token() }}"

                },
                url: url,
                success:function(result){
                    if(result.status == "Success"){
                        alert(result.message);
                    }
                },

            });
      });
    });
    </script> --}}
@endsection
