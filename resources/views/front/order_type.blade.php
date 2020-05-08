@extends('layouts.app')
@section('content')
<section class="home_bg" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">
        <div class="order_type">
            <h2>{{isset($cms->page_title)?$cms->page_title:'Choose your order Type'}}</h2>
            @if(isset($store->pickup_delivery))
                <li>
                    @if($store->pickup_delivery == 'pickup' || $store->pickup_delivery == 'both')
                    <a href="{{ URL::To('pickup/menu/0') }}">
                         <img src="{{ asset('img/pickup.png')}}">
                        <h3>Pickup</h3> 
                    </a>
                    @else
                     <a  disabled='disabled'>
                        <img src="{{ asset('img/pickup.png')}}">
                        <h3>Pickup</h3>    
                    </a>  
                    <p style="color: white;">Currently service is not available</p>              
                    @endif
                </li>
                <li>
                    @if($store->pickup_delivery == 'delivery' || $store->pickup_delivery == 'both')
                    <a href="{{ URL::To('delivery/menu/'.$store->delivery_charge) }}">
                        <img src="{{ asset('img/delivery.png')}}">
                        <h3>Delivery</h3>
                    </a>
                    @else
                     <a disabled='disabled'>
                        <img src="{{ asset('img/delivery.png')}}">
                        <h3>Delivery</h3>
                    </a>
                        <p style="color: white;">Currently service is not available</p>
                    @endif                    
                        
                </li>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<div class="clearfix"></div>
@endsection
