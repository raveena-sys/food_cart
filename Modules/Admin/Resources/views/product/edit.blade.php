@extends('admin::layouts.app')
@section('content')
<?php $current = 'Edit Product  '; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
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
                <h4 class="mb-0">Edit Product</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">

                    <form id="add_category_form" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate autocomplete="false" action="{{URL::To('admin/product/update')}}">
                        {{csrf_field()}}

                        <input class="form-control" name="id" type="hidden" value="{{$detail->id}}">
                         <input class="form-control" name="storeid" type="hidden" value="{{$detail->store_id}}">
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($detail->image,'products')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                               
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                         <input type="file" name="image" id="uploadImage" accept="image/*" style="visibility: hidden;position:absolute;">
                         <input type="text" style="visibility: hidden;position:absolute;" name="oldimage" id="oldimage" value="{{$detail->image}}">
                         <!-- <p><strong>Note:</strong>Image size must be of 696*270</p> -->
                        <div class="form-group">
                            <label>Product Name</label>
                            <input class="form-control" maxlength="250" name="name" type="text" value="{{$detail->name}}" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label>Product Description</label>
                            <textarea class="form-control" maxlength="250" name="description" rows="5" type="text" placeholder="Description">{{$detail->description}}</textarea>
                        </div>
<!-- 
                        <div class="form-group">
                            <label>Store</label>
                            @php
                            $store_query = \App\Models\StoreMaster::query();
                            $store_query_result = $store_query->where('status', '!=', 'deleted')->get();
                            @endphp
                            <select class="form-control selectpicker1" name="store_id" id="store_list" title="Select Store" data-size="4">
                                @foreach($store_query_result as $data)
                                <option value="{{$data->id}}" @if($data->id == $detail->store_id) selected="selected" @endif >{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
 -->
                        <div class="form-group">
                            <label>Category {{$detail->cat_id}}</label>
                            @php
                            $category_query = \App\Models\Category::query();
                            $category_query_result = $category_query->where('status',  'active')->get();
                            @endphp
                            <select class="form-control selectpicker" name="category_id" id="category_list" title="Select Category" data-size="4" >
                                @foreach($category_query_result as $data)
                                <option value="{{$data->id}}" @if($data->id == $detail->cat_id) selected="selected" @endif>{{$data->name}}</option>
                                <!-- <option value="{{$data->id}}" @if($data->id ==1) selected="selected" @endif>{{$data->name}}</option> -->
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sub Category </label>
                            @php
                            $sub_category_query = \App\Models\SubCategory::query();
                            $sub_category_query_result = $sub_category_query->where('status',  'active')->where('category_id', $detail->cat_id)->get();
                            @endphp
                            <select class="form-control selectpicker" name="sub_category_id" id="sub_category_id" title="Select Sub Category " data-size="4" >
                                @foreach($sub_category_query_result as $data)
                                <option value="{{$data->id}}" @if($data->id == $detail->sub_category_id) selected="selected" @endif >{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Size </label>
                            @php
                            $size_master_query = \App\Models\SizeMaster::query();
                            $size_master_query_result = $size_master_query->where('status', '!=', 'deleted')->get();
                            @endphp
                            <select class="form-control selectpicker" name="size_master_id" id="size_master_list" title="Select Size " data-size="4">
                                @foreach($size_master_query_result as $data)
                                <option value="{{$data->id}}" @if($data->id == $detail->size_master_id) selected="selected" @endif >{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" name="price" max="10000" value="{{round($detail->price,2)}}" type="number" placeholder="Price">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input class="form-control" name="quantity" max="10000" value="{{$detail->quantity}}" type="number" placeholder="Quantity" min='1'>
                        </div>
                        <div class="form-group">
                            <label>Food Type</label>
                            <select class="form-control selectpicker" name="food_type" id="foodtype" title="Select Food Type " data-size="4">
                                <option value="veg" @if('veg'==$detail->food_type) selected="selected" @endif>Veg</option>
                                <option value="non_veg" @if('non_veg'==$detail->food_type) selected="selected" @endif>Non Veg</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Select Topping Option From </label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="none" checked="true" name="topping_from" class="custom-control-input" value="none" @if($detail->topping_from == 'none') checked="true" @endif>
                                <label class="custom-control-label" for="none">Not Required</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="topping_pizza" name="topping_from" class="custom-control-input" value="topping_pizza" @if($detail->topping_from == 'topping_pizza') checked="true" @endif>
                                <label class="custom-control-label" for="topping_pizza">Pizza</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="topping_wing_flavour" name="topping_from" class="custom-control-input" value="topping_wing_flavour" @if($detail->topping_from == 'topping_wing_flavour') checked="true" @endif>
                                <label class="custom-control-label" for="topping_wing_flavour">Wing Flavour</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="topping_dips" name="topping_from" class="custom-control-input" value="topping_dips" @if($detail->topping_from == 'topping_dips') checked="true" @endif>
                                <label class="custom-control-label" for="topping_dips">Dips</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="topping_donair_shawarma_mediterranean" name="topping_from" class="custom-control-input" value="topping_donair_shawarma_mediterranean" @if($detail->topping_from == 'topping_donair_shawarma_mediterranean') checked="true" @endif>
                                <label class="custom-control-label" for="topping_donair_shawarma_mediterranean">Donair shawarma mediterranean</label>
                            </div>
                        </div>
                        <div class="form-group text-center mb-0">
                            <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" type="submit">UPDATE<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                            <a id="btnCancel" class="btn btn-danger ripple-effect text-uppercase min-w130" href="{{ URL::To('admin/product') }}">Cancel<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></a>

                            <!--  -->
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditProductRequest','#add_category_form')
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
    var _URL = window.URL || window.webkitURL;
    $("#uploadImage").change(function(e) {   
        var image = this;    
        var file, img;
        
        if ((file = this.files[0])) {
            var type = image.files[0].type;
            var size = image.files[0].size;
            var height = image.files[0].height;
            var width = image.files[0].width;
            img = new Image();
            var objectUrl = _URL.createObjectURL(file);
            if (type == 'image/png' || type == 'image/jpg' || type == 'image/jpeg') {
                img.onload = function () {    
                
            
                    if (size <= 2097152) {
                        /*if(this.width != 696 && this.height!= 270 ){
                            toastr.error('The product image must be of size: (w*h)(696*270)');
                            $('#uploadImage').val("");
                        }else{     */           
                            readURL(image);
                        //}
                    }
                    else {
                        toastr.error('The profile picture may not be greater than 2MB.');
                        ('error', 'The profile picture may not be greater than 2MB.');
                        $('#uploadImage').val("");
                    }
                };
            } else {
                toastr.error('The profile picture must be a file of type: jpeg, png, jpg.');
                $('#uploadImage').val("");
            }
            img.src = objectUrl;
        }  else {
                    toastr.error('The profile picture must be a file of type: jpeg, png, jpg.');
                    $('#uploadImage').val("");
                }   
    });

    function readURL(input) {
       
        var reader = new FileReader();
        reader.onload = function(e) {
            console.log("e", e);
            console.log("e", e.height);

            $('.rounded-circle').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);                
    }

    $(document).ready(function() {
        $('select[name="category_id"]').on('change', function() {
          var category_id = $(this).val();
        // category_id = 1;
        var url = "{{url('admin/product/sub-category')}}";
        var sub_category_id = "{{$detail->sub_category_id}}"
        if (category_id) {
            $.ajax({
                type: "GET",
                url: url + '/' + category_id,
                dataType: "json",
                success: function(data) {

                    $('select[name="sub_category_id"]').empty();
                    var html = '';
                    $.each(data, function(key, value) {
                        html += '<option value="' + key + '">' + value + '</option>';
                    });

                    $('select[name="sub_category_id"]').empty().html(html);
                    $('select[name="sub_category_id"]').selectpicker('refresh')
                    // To select drop down value of select picker : https://github.com/snapappointments/bootstrap-select/issues/1925
                    $('select[name="sub_category_id"]').selectpicker('val', sub_category_id);

                }
            });
        } else {
            $('select[name="sub_category_id"]').empty();
        }
        });
    });

    // $('select[name="category_id"]').trigger('change');
</script>
@endsection
