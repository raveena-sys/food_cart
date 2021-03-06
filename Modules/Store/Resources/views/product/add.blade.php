@extends('store::layouts.app')
@section('content')
<?php $current = 'Add Product  '; ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Product </li>
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
                <h4 class="mb-0">Add Product </h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">

                    <form id="add_category_form" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate autocomplete="false" action="{{URL::To('store/product/save')}}">
                        {{csrf_field()}}
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($adminDetail->profile_image,'product')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <input type="file" name="image" id="uploadImage" accept="image/*" style="visibility: hidden;position:absolute;">
                        <!-- <p><strong>Note:</strong>Image size must be of 696*270</p> -->
                        <div class="form-group">
                            <label>Product Name</label>
                            <input class="form-control" maxlength="250" name="name" type="text" placeholder="Name">
                            <input  name="store_id" type="hidden" value="{{Auth::check()? Auth::user()->store_id:""}}">
                        </div>

                        <div class="form-group">
                            <label>Product Description</label>
                            <textarea class="form-control" maxlength="250" name="description" rows="5" type="text" placeholder="Description"></textarea>
                        </div>
                    <!--     <div class="form-group">
                            <label>Store</label>
                            @php
                            $store_query = \App\Models\StoreMaster::query();
                            $store_query_result = $store_query->where('status', '!=', 'deleted')->get();
                            @endphp
                            <select class="form-control selectpicker" name="store_id" id="store_list" title="Select Store" data-size="4">
                                @foreach($store_query_result as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div> -->
                        <div class="form-group">
                            <label>Category </label>
                            @php
                                $query = \App\Models\Category::query();
                                $category = $query->select('category.id', 'category.name')->join('store_category','store_category.cat_id', '=', 'category.id')->where('category.status', 'active')->where('store_category.store_id', Auth::user()->store_id)->get();

                            @endphp
                            <select class="form-control selectpicker" name="category_id" id="category_list" title="Select Category" data-size="4">
                                @foreach($category as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Sub Category </label>
                            @php
                            $sub_category_query = \App\Models\SubCategory::query();
                            $sub_category_query_result = $sub_category_query->where('status',  'active')->get();
                            @endphp
                            <select class="form-control selectpicker" name="sub_category_id" id="sub_category_id" title="Select Sub Category " data-size="4">
                                <!-- @foreach($sub_category_query_result as $data)
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach -->
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
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Price</label>
                            <input class="form-control" name="price" max="10000" type="number" placeholder="Price">
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input class="form-control" name="quantity" max="10000" type="number" placeholder="Quantity" value="1" min="1">
                        </div>

                        <div class="form-group">
                            <label>Food Type</label>
                            <select class="form-control selectpicker" name="food_type" id="foodtype" title="Select Food Type " data-size="4">
                                <option value="veg">Veg</option>
                                <option value="non_veg">Non Veg</option>
                            </select>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline" >
                            <input class="custom-control-input" name="customise_required" type="checkbox" value="" id="customise_required" @if(isset($detail->customise_required) && $data->customise_required == 'on') selected="checked" @endif>
                            <label class="custom-control-label" for="customise_required">Checked To make Customise Options required </label>
                        </div>

                        <div class="form-group">
                            <label>Select Required method to add product in cart</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="add_customisation_cart" checked="true" name="add_customisation" class="custom-control-input" value="0">
                                    <label class="custom-control-label" for="add_customisation_cart">Only Add To Cart</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="add_customisation_cust" name="add_customisation" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="add_customisation_cust">Only Customisation</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="add_customisation_both" name="add_customisation" class="custom-control-input" value="2">
                                    <label class="custom-control-label" for="add_customisation_both">Add to Cart and Customisation</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Topping Option From </label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="none" checked="true" name="topping_from" class="custom-control-input topping_option" value="none">
                                    <label class="custom-control-label" for="none">Not Required</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_pizza" name="topping_from" class="custom-control-input topping_option" value="topping_pizza">
                                    <label class="custom-control-label" for="topping_pizza">Pizza</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_wing_flavour" name="topping_from" class="custom-control-input topping_option" value="topping_wing_flavour">
                                    <label class="custom-control-label" for="topping_wing_flavour">Wing Flavour</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="topping_tops" name="topping_from" class="custom-control-input topping_option" value="topping_tops" >
                                <label class="custom-control-label" for="topping_tops">Pizza Topping Only</label>
                            </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_dips" name="topping_from" class="custom-control-input topping_option" value="topping_dips"> <label class="custom-control-label" for="topping_dips">Dips</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_donair_shawarma_mediterranean" name="topping_from" class="custom-control-input topping_option" value="topping_donair_shawarma_mediterranean">
                                    <label class="custom-control-label" for="topping_donair_shawarma_mediterranean">Donair shawarma mediterranean</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group default_topping"  style="display:none;">
                            <label>Select topping to show when no customisation created by user </label>
                            <div class="form-group topping_pizza topping_dips hide_options"  style="display: none;">
                               
                               <label >Pizza Size</label>
                               <select class="form-control selectpicker" name="pizza_size" id="pizza_size" title="Select size" data-size="4" >
                            
                                    @foreach($topping['size'] as $size)
                                    <option value="{{$size->id}}" @if(isset($detail->pizza_size) && $size->id == $detail->pizza_size) selected="selected" @endif>{{$size->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group topping_pizza topping_dips hide_options"  style="display: none;">
                                <label  >Pizza Sauce </label>
                                <select class="form-control selectpicker" name="pizza_sauce" id="pizza_sauce" title="Select Sauce" data-size="4" >
                            
                                    @foreach($topping['sauce'] as $sauce)
                                    <option value="{{$sauce->id}}" @if(isset($detail->pizza_sauce) && $sauce->id == $detail->pizza_sauce) selected="selected" @endif>{{$sauce->name}}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                            
                            <div class="form-group topping_pizza topping_dips hide_options"  style="display: none;">   
                               <label  >Pizza Crust</label>
                               <select class="form-control selectpicker" name="pizza_crust" id="pizza_crust" title="Select Crust" >
                            
                                    @foreach($topping['crust'] as $crust)
                                    <option value="{{$crust->id}}" @if(isset($detail->pizza_crust) && $crust->id == $detail->pizza_crust) selected="selected" @endif>{{$crust->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group topping_wing_flavour hide_options"  style="display: none;">
                               <label >Topping Wing Flavour</label>
                               <select class="form-control selectpicker" name="common_topping" id="topping_wing_flavour" title="Select Topping Wing Flavour" >
                            
                                    @foreach($topping['toppingWing'] as $toppingWing)
                                    <option value="{{$toppingWing->id}}" @if(isset($detail->common_topping) && $toppingWing->id == $detail->common_topping) selected="selected" @endif>{{$toppingWing->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group topping_donair_shawarma_mediterranean hide_options"  style="display: none;">
                         
                               <label >Topping Donair Shawarna Meditarrean</label>
                               <select class="form-control selectpicker" name="common_topping" id="topping_donair" title="Select Topping Donair Shawarna Meditarrean" >
                            
                                    @foreach($topping['toppingDonair'] as $toppingDonair)
                                    <option value="{{$toppingDonair->id}}" @if(isset($detail->common_topping) && $toppingDonair->id == $detail->common_topping) selected="selected" @endif>{{$toppingDonair->name}}</option>
                                    @endforeach
                                </select>
                            
                            </div>
                        </div>

                        <div class="form-group text-center mb-0">
                            <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" type="submit">SAVE<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                            <a id="btnCancel" class="btn btn-danger ripple-effect text-uppercase min-w130" href="{{ URL::To('store/product') }}">Cancel<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></a>

                            <!--  -->
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddProductRequest','#add_category_form')
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
                        }else{                */
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

    function onCategoryChange(event) {

        alert(event.value);
    }
     $(document).on('change','.topping_option', function(){
        id = $(this).attr('id');
        if(id!='none'){
            
            $('.hide_options').hide();
            $('.'+id).show();
            $('.default_topping').show();
        }else{
            $('.default_topping').hide();
            $('.hide_options').hide();

        }

    })
    $(document).ready(function() {
        $('select[name="category_id"]').on('change', function() {
            var id = $(this).val();
            var url = "{{url('store/product/sub-category')}}";

            if (id) {
                $.ajax({
                    type: "GET",
                    url: url + '/' + id,
                    dataType: "json",
                    success: function(data) {

                        $('select[name="sub_category_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="sub_category_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                        $('#sub_category_id').selectpicker('refresh')


                    }
                });
            } else {
                $('select[name="sub_category_id"]').empty();
            }
        });
    });
</script>
@endsection
