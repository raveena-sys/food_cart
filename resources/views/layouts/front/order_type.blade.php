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
            <h2>{{isset($cms->page_title)?$cms->page_title:'Choose your order Type'}}</h2>
            @if(isset($store->pickup_delivery))
               
                
            <div class="home_icon_list">
                @if($store->pickup_delivery == 'pickup' || $store->pickup_delivery == 'both')
                <a href="{{ URL::To('pickup/menu/0') }}">
                    <div class="home_icon_list_icon">
                        <div class="menu_icon"><img src="{{ asset('images/Pickup.png')}}" /></div>
                    </div>
                    <div class="home_icon_list_details">
                        <span>Pickup</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
                @else
                <a disabled='disabled'>
                    <div class="home_icon_list_icon">
                        <div class="menu_icon"><img src="{{ asset('images/Pickup.png')}}" /></div>
                    </div>
                    <div class="home_icon_list_details">
                        <span>Currently service is not available</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
                @endif
            </div>
            <div class="home_icon_list">
               @if($store->pickup_delivery == 'delivery' || $store->pickup_delivery == 'both')
                <a href="{{ URL::To('pickup/menu/0') }}">
                    <div class="home_icon_list_icon">
                        <div class="menu_icon"><img src="{{ asset('images/Delivery.png')}}" /></div>
                    </div>
                    <div class="home_icon_list_details">
                        <span>Delivery</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
                @else
                <a disabled='disabled'>
                    <div class="home_icon_list_icon">
                        <div class="menu_icon"><img src="{{ asset('images/Delivery.png')}}" /></div>
                    </div>
                    <div class="home_icon_list_details">
                        <span>Currently service is not available</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
                @endif
            </div>
            
            @endif    
                
                
                
                
                
                 <?php /* ?><li>
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
            @endif <?php */ ?>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<div class="clearfix"></div>
@endsection
