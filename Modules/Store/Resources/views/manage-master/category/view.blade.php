@extends('store::layouts.app')
@section('content')
<?php $current = 'Category View' ?>
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
                        <h4 class="h-20 font-black">Category Details</h4>
                    </div>
                </div>
                <div class="common-user-list active">

                    <div class="form-group">
                        <label>Name</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{$category->name}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div class="row">
                            <div class="pr-2 col ">

                                <strong>{{$category->description}}</strong>

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
                            <strong>{{$category->description}}</strong>
                        </li>

                    </ul> -->
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
