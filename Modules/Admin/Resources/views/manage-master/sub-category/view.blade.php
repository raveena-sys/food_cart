@extends('admin::layouts.app')
@section('content')
<?php $current = 'SubCategory ' . $SubCategory->user_type . ' View' ?>
<main class="main-content view-page bar-detail-page inner-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
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
                        <h4 class="h-20 font-black">SubCategory Details</h4>
                    </div>
                </div>
                <div class="common-user-list active">

                    <div class="form-group">
                        <label>Name</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{$SubCategory->name}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Category Name</label>
                        <div class="row">
                        @php
                        $sizequery = \App\Models\category::query();
                        $id = $SubCategory->category_id;

                        $getsize = $sizequery->where('id', '=', $id)->first();

                        @endphp
                        <div class="pr-2 col ">
                            <strong>{{$getsize->name}} ({{isset($getsize->store->name)?$getsize->store->name:'Admin'}})</strong>
                        </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div class="row">
                            <div class="pr-2 col ">

                                <strong>{{$SubCategory->description}}</strong>

                            </div>

                        </div>
                    </div>
                    <!-- <ul class="list-unstyled mb-0 with-icon">
                        <li class="list-inline-item list-user">

                            <span>Name</span>
                            <strong>{{$SubCategory->name}}</strong>
                        </li>
                        <li class="list-inline-item">
                            <span>Description</span>
                            <strong>{{$SubCategory->description}}</strong>
                        </li>

                    </ul> -->
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
