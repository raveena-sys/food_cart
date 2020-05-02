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
</style>
<section class="home_bg" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>>
    <div class="container">
        <div class="order_type">
            @if(!empty($category) && count($category)>0)
            <h2>{{isset($cms->page_title)?$cms->page_title:'Choose Dish type'}}</h2>
            <ul class="d_flex-div">
            @foreach($category as $data)
            <li>
                <a href="{{ URL::To('menu/'.$data->id) }}">
                    <img src="{{ asset('img/pick-up.png')}}">
                    <h3>{{$data->name}}</h3>
                </a>
            </li>
            @endforeach
            </ul>
            @else
            <h2>Menu is not available for now</h2>
            
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<div class="clearfix"></div>
@endsection