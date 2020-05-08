@extends('layouts.app')

@section('content')
<section class="store_menu_banner" style= 'background-image: url("{{asset(isset($cms->header_image)?'/uploads/cms/'.$cms->header_image:'/img/home_bg.jpg')}}") !important;'>>
    <div class="container">
      <div class="clearfix"></div>
    </div>
</section>

<div class="container store_list_inner">
    <div class="row">
      <div class="col-lg-12">
        <h1>{!!isset($cms->page_title)?ucfirst($cms->page_title):'About Us'!!}</h1>
        {!!isset($cms->page_content)?ucfirst($cms->page_content):''!!}

      </div>
      <div class="clearfix"></div>
    </div>
</div>
@endsection
