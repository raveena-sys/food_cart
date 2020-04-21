@extends('store::layouts.app')
@section('content')
<?php $current = 'Product' . $category->user_type . ' View' ?>
<main class="main-content view-page bar-detail-page inner-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Back</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
        </div>
        <div class="bid-wraper">
            <div class="box-shadow common-padding-box bid-user-detail mb-40">
                <div class="common-list-head list-header mb-3 mb-md-4">
                    <div class="list-heading">
                        <h4 class="h-20 font-black">Product</h4>
                    </div>
                </div>
                <div class="common-user-list active">
                    
                    <div class="mx-auto">
                        <div class="">
                            <img src="{{getUserImage($category->image,'products')}}" alt="user-img" class="img-fluid ">
                        </div>
                        
                    </div>
                  
                    <div class="form-group">
                        <label>Name</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{$category->name}}</strong>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>${{round($category->price,2)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Topping Form</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>
                                    <?php if ($category->topping_from == "topping_pizza") {
                        $result = "Pizza Toppings";
                    }
                    if ($category->topping_from == "topping_wing_flavour") {
                        $result = "Wing Flavour Toppings";
                    }
                    if ($category->topping_from == "topping_dips") {
                        $result = "Other Toppings";
                    }
                    if ($category->topping_from == "topping_donair_shawarma_mediterranean") {
                        $result = "Donair Shawarma Mediterranean";
                    }

                    if ($category->topping_from == "none") {
                        $result = "Not Required";
                    }?>{{$result}}</strong>
                            </div>

                        </div>
                    </div>
                  <!--   <div class="form-group">
                        <label>Store Master</label>
                        <div class="row">
                        @php
                        $sizequery = \App\Models\StoreMaster::query();
                        $size = $category->store_id;

                        $getsize = $sizequery->where('id', '=', $size)->get();

                        @endphp
                        @foreach($getsize as $compay)

                        <div class="pr-2 col ">
                            <strong>{{$compay->name}}</strong>
                        </div>
                        @endforeach

                        </div>
                    </div> -->
                    <div class="form-group">
                        <label>Size Master</label>
                        <div class="row">
                        @php
                        $sizequery = \App\Models\SizeMaster::query();
                        $size = $category->size_master_id;

                        $getsize = $sizequery->where('id', '=', $size)->get();

                        @endphp
                        @foreach($getsize as $compay)

                        <div class="pr-2 col ">
                            <strong>{{$compay->name}}</strong>
                        </div>
                        @endforeach

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <div class="row">
                    
                        <div class="pr-2 col ">
                            <strong>{{$category->cat_name}}</strong>
                        </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>SubCategory</label>
                        <div class="row">
                       
                        <div class="pr-2 col ">
                            <strong>{{$category->subcat_name}}</strong>
                        </div>

                        </div>
                    </div>


                    
                    <div class="form-group">
                        <label>Food Type</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucwords(str_replace('_', ' ', $category->food_type))}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($category->description)}}</strong>
                            </div>
                        </div>
                    </div>
                    <!-- <ul class="list-unstyled mb-0 with-icon">
                        <li class="list-inline-item list-user">

                            <span>Name</span>
                            <strong>{{$category->name}}</strong>
                        </li>
                        <li class="list-inline-item">
                            <span>Description</span>
                            <strong>{{$category->Description}}</strong>
                        </li>

                    </ul> -->
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
