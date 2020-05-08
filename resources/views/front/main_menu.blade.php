@extends('layouts.app')

@section('content')
<style>
    .order_type li {
        list-style: none;
        display: inline-block;
        width: 32.5%;
        text-align: center;
        background-color: #fb2525;
        margin-top: 45px;
        margin-bottom: 30px;
        white-space: normal;
        position: relative;
    }
    .d_flex-div{
        display:flex;
        flex-wrap:wrap;
    }
    .home_icon1 {
    text-align: center;
    margin: 150px auto;
    background-color: #08080836;
}
.home_icon1 h2 {
    background-color: #000;
    color: #fff;
    font-size: 22px;
    text-transform: uppercase;
    margin: 0;
    font-weight: 500;
    padding: 15px 20px;
    text-align: center;
}
</style>
<section class="home_bg" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">
        <div class="home_icon1">
            @if(!empty($category) && count($category)>0)
           <h2>{{isset($cms->page_title)?$cms->page_title:'Choose Dish type'}}</h2>
           <!-- <ul class="d_flex-div">
            @foreach($category as $data)
            <li>
                <a href="{{ URL::To('menu/'.$data->id) }}">
                    <img src="{{ asset('img/pick-up.png')}}">
                    <h3>{{$data->name}}</h3>
                </a>
            </li>
            @endforeach
            </ul> -->
            
          
            @foreach($category as $data)
            <div class="home_icon_list">
                <a href="{{ URL::To('menu/'.$data->id) }}">
                    <div class="home_icon_list_icon">
                        <img src="{{ asset('img/pick-up.png')}}" />
                    </div>
                    <div class="home_icon_list_details">
                        <span>{{$data->name}}</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
            </div>
            @endforeach
            
            @else
            <h2>Menu is not available for now</h2>
            
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<div class="clearfix"></div>
@endsection