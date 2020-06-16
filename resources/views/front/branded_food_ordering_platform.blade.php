@extends('layouts.app')

@section('content')
<section class="store_menu_banner"
    style='background-image: url("{{asset(isset($cms->header_image)?'/uploads/cms/'.$cms->header_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">
        <div class="clearfix"></div>
    </div>
</section>

<div class="container about-us-page">
    
</div>
@endsection