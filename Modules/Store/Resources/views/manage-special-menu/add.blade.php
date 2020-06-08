@extends('store::layouts.app')
@section('content')
<?php $currentAdd = Request::segment(3)=='edit'?'Edit':'Add'; 
$current = Request::segment(4)==1?$currentAdd.' Double/Triple Product':(Request::segment(4)==2?$currentAdd.' Sides Product':$currentAdd.' Drink Product');  ?>
<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Manage Special Menu</li>
                        <li class="breadcrumb-item active" aria-current="page">{{(Request::segment(4)==1)?'Double/Triple Product':(Request::segment(4)==2?'Sides Product':'Drink Product')}} </li>
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
                <h4 class="mb-0">{{Request::segment(3)=='edit'?'Edit':'Add'}} {{Request::segment(4)==1?'Double/Triple Product':(Request::segment(4)==2?'Sides Product':'Drink Product')}}</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">

                    <form id="add_category_form" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate autocomplete="false" action="{{URL::To('store/manage-special-menu/create')}}">
                        {{csrf_field()}}
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage(isset($detail->image)?$detail->image:'','products')}}" alt="user-img" class="img-fluid rounded-circle">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <input type="file" name="image" id="uploadImage" accept="image/*" style="visibility: hidden;position:absolute;">
                        <input type="text" style="visibility: hidden;position:absolute;" name="oldimage" id="oldimage" value="{{isset($detail->image)?$detail->image:''}}">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" maxlength="250" name="name" type="text" placeholder="Name" value="{{isset($detail->name)?$detail->name:''}}">
                            <input  name="store_id" type="hidden" value="{{Auth::check()? Auth::user()->store_id:""}}">
                            <input  name="special_cat" type="hidden" value="{{Request::segment(4)}}">
                        </div>



                        @if(Request::segment(4)==3)
                        <div class="form-group">
                            <label>Categories </label>                            
                            <div class="row"> 
                                @php
                                    $query = \App\Models\Category::query();
                                    $category = $query->select('category.id', 'category.name')->join('store_category','store_category.cat_id', '=', 'category.id')->where('category.status', 'active')->where('store_category.store_id', Auth::user()->store_id)->get();

                                @endphp

                                @foreach($category as $k=> $data)
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="checkbox" id="category_list{{$data->id}}" name="drink_category[{{$k}}]" class="custom-control-input"  @if(isset($detail->cat_id) && $data->id == $detail->cat_id) selected="checked" @endif>
                                        <label class="custom-control-label" for="category_list{{$data->id}}">{{$data->name}}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">     
                                        
                                        @php
                                        $sub_category_query = \App\Models\SubCategory::query();
                                        $sub_category_query_result = $sub_category_query->where('status',  'active')->where('category_id', $data->id)->get();
                                        @endphp
                                        
                                        <select class="form-control selectpicker" name="drink_subcategory[{{$k}}]" id="sub_category_id" title="Select Sub Category " data-size="4">
                                            @if(!empty($sub_category_query_result))
                                            @foreach($sub_category_query_result as $data1)
                                            <option value="{{$data1->id}}" @if(isset($detail->sub_category_id) && $data1->id == $detail->sub_category_id) selected="selected" @endif>{{$data1->name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div> 

                        @endif
                    
                        <input class="form-control" name="id" type="hidden" value="{{isset($detail->id)?$detail->id:''}}">
                        <div class="form-group">
                            <label> Description</label>
                            <textarea class="form-control" maxlength="250" name="description" rows="5" type="text" placeholder="Description">{{isset($detail->description)?$detail->description:''}}</textarea>
                        </div>
                        @if(Request::segment(4)==1)
                        @php
                        $display = 'display:block;'
                        @endphp
                        @else
                        @php
                        $display = 'display:none;'
                        @endphp
                        @endif
                        <div class="form-group" style={{$display}}>
                            <label>Select Product to show in custom list </label>
                            @php
                                $query = \App\Models\Product::query();
                                $product = $query->select('product.id', 'product.name')->join('store_product_price','store_product_price.product_id', '=', 'product.id')->where('product.status', 'active')->where('product.special_cat', 0)->where('store_product_price.store_id', Auth::user()->store_id)->get();
                                $detail->cat_id = 
                            @endphp
                            <select class="form-control selectpicker" name="custom_product[]" id="custom_product" title="Select Product" data-size="4" multiple="">
                                
                                @foreach($product as $data)
                                <option value="{{$data->id}}" @if(isset($detail->cat_id) && $data->id == $detail->cat_id) selected="selected" @endif>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" {{(Request::segment(4)==1)?'style=display:none;':''}}>
                            <label>Price</label>
                            <input class="form-control" name="price" max="10000" type="number" placeholder="Price" value="{{(Request::segment(4)==1)?0:$data->custom_price}}">
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input class="form-control" name="quantity" max="10000" type="number" placeholder="Quantity" value="{{isset($detail->quantity)?$detail->quantity:(Request::segment(4)==1)?2:1}}" min="{{(Request::segment(4)==1)?2:1}}">
                        </div>
                        @if(Request::segment(4)!=3)
                        <div class="form-group">
                            <label>Category </label>
                            @php
                                $query = \App\Models\Category::query();
                                $category = $query->select('category.id', 'category.name')->join('store_category','store_category.cat_id', '=', 'category.id')->where('category.status', 'active')->where('store_category.store_id', Auth::user()->store_id)->get();

                            @endphp
                            <select class="form-control selectpicker" name="category_id" id="category_list" title="Select Category" data-size="4">
                                
                                @foreach($category as $data)
                                <option value="{{$data->id}}" @if(isset($detail->cat_id) && $data->id == $detail->cat_id) selected="selected" @endif>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Sub Category </label>
                            @if(isset($detail->cat_id))
                            @php
                            $sub_category_query = \App\Models\SubCategory::query();
                            $sub_category_query_result = $sub_category_query->where('status',  'active')->where('category_id', $detail->cat_id)->get();
                            @endphp
                            @endif
                            <select class="form-control selectpicker" name="sub_category_id" id="sub_category_id" title="Select Sub Category " data-size="4">
                                @if(!empty($sub_category_query_result))
                                @foreach($sub_category_query_result as $data)
                                <option value="{{$data->id}}" @if(isset($detail->sub_category_id) && $data->id == $detail->sub_category_id) selected="selected" @endif>{{$data->name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @endif
                        <input type="hidden" name="segment_name" value="{{Request::segment(4)}}">

                        <div class="form-group col-md-12" style="{{$display}}">
                            <label>Price for each Size </label>
                            @php
                            $size_master_query = \App\Models\PizzaSizeMaster::query();
                            $size_master_query_result = $size_master_query->where('status', 'active')->get();
                            if(isset($detail->size_master_price)){
                            $selectedSize = json_decode($detail->size_master_price); 
                            }
                            @endphp
                            @foreach($size_master_query_result as $data)
                            <div class="row">
                                <input type="text" name="size[]" class="form-control col-md-4"  value="{{$data->name}}">&nbsp
                                <input type="number" name="size_price[]" class="form-control col-md-4" value="" placeholder="Price">&nbsp
                                <div class="custom-control custom-radio custom-control-inline col-md-2">
                                    <input type="checkbox" id="add_size_{{$data->id}}" name="size_master_price[]" class="custom-control-input" value="{{$data->id}}" >
                                    <label class="custom-control-label" for="add_size_{{$data->id}}">Add</label>
                                </div>
                            </div>
                            @endforeach
                        </div>



                        
                       <div class="form-group" style="{{$display}}">
                            <label>Food Type</label>
                            <select class="form-control selectpicker" name="food_type" id="foodtype" title="Select Food Type " data-size="4">
                                <option value="veg" {{isset($data->food_type) && ($data->food_type=='veg')?'checked':''}}>Veg</option>
                                <option value="non_veg" {{isset($data->food_type) && ($data->food_type=='non_veg')?'checked':''}}>Non Veg</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="{{$display}}">
                            <label>Select Required method to add product in cart</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="add_customisation_cart" checked="true" name="add_customisation" class="custom-control-input" value="0">
                                    <label class="custom-control-label" for="add_customisation_cart">Only Add To Cart</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline" style="{{$display}}">
                                    <input type="radio" id="add_customisation_cust" name="add_customisation" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="add_customisation_cust">Only Customisation</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="add_customisation_both" name="add_customisation" class="custom-control-input" value="2">
                                    <label class="custom-control-label" for="add_customisation_both">Add to Cart and Customisation</label>
                                </div>

                            </div>
                        </div>
                        <div class="form-group"  style="{{$display}}">
                            <label>Select Topping Option From </label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="none" checked="true" name="topping_from" class="topping_option custom-control-input " value="none">
                                    <label class="custom-control-label" for="none">Not Required</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_pizza" name="topping_from" class="topping_option custom-control-input " value="topping_pizza">
                                    <label class="custom-control-label" for="topping_pizza">Pizza</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_wing_flavour" name="topping_from" class="custom-control-input topping_option " value="topping_wing_flavour">
                                    <label class="custom-control-label" for="topping_wing_flavour">Wing Flavour</label>
                                </div>

                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_dips" name="topping_from" class="topping_option custom-control-input " value="topping_dips"> <label class="custom-control-label" for="topping_dips">Dips</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="topping_donair_shawarma_mediterranean" name="topping_from" class="topping_option custom-control-input " value="topping_donair_shawarma_mediterranean">
                                    <label class="custom-control-label" for="topping_donair_shawarma_mediterranean">Donair shawarma mediterranean</label>
                                </div>
                            </div>
                        </div>
                      <!--   @if(Request::segment(4)==1)
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
                        @endif -->

                        <div class="form-group text-center mb-0">
                            <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" type="submit">SAVE<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                            <a id="btnCancel" class="btn btn-danger ripple-effect text-uppercase min-w130" href="{{ URL::To('store/product') }}">Cancel<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></a>

                            <!--  -->
                        </div>
                    </form>
                    @if(Request::segment(3)=='edit')
                    
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditProductRequest','#add_category_form')
                    !!}
                    @else
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddProductRequest','#add_category_form')
                    !!}
                    @endif

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
