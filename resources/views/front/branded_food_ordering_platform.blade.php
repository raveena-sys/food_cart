@extends('layouts.app')

@section('content')
<section class="store_menu_banner"
    style='background-image: url("{{asset(isset($cms->header_image)?'/uploads/cms/'.$cms->header_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">
        <div class="clearfix"></div>
    </div>
</section>

<div class="container about-us-page">
    <div class="store_list_inner">
        <!-- <h1>{!!isset($cms->page_title)?ucfirst($cms->page_title):'About Us'!!}</h1>
        {!!isset($cms->page_content)?ucfirst($cms->page_content):''!!} -->

        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <h2>
                        Branded Food Ordering Platform
                    </h2>
                    <p>
                        Checkout the exciting features of our branded food ordering platform:
                    </p>
                    <h3>
                        Exciting Product Display
                    </h3>

                    <ul class="list-unstyled">

                        <li>Create multiple stores with different menus</li>
                        <li>Custom menu for each store</li>
                        <li>Custom pricing for each location and menu</li>
                        <li>Custom headings and menu sub-sections for each location</li>
                        <li>Default choices with options to update</li>
                        <li>Available image galleries or provide your own graphics</li>
                        <li>Easy to upload and change menu items</li>
                    </ul>

                    <h3>
                        Custom Discounts & Offers
                    </h3>
                    <ul class="list-unstyled">
                        <li>Deals &amp; offers with various customizations</li>
                        <li>Coupons with start and expiration date</li>
                    </ul>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 438px;">
                </div>

            </div>
        </section>
         <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 443px;">
                </div>
                <div class="col-lg-6 col-xs-12">

                    <h3>
                        Notifications & Reporting
                    </h3>
                    <ul class="list-unstyled">
                        <li>Multiple store order notifications via email, store application &amp; text messages</li>
                        <li>Customer order notifications via email &amp; text messages</li>
                        <li>Notifications to delivery company via email &amp; text messages</li>
                        <li>Amazing reporting on daily, weekly &amp; monthly sales by store and menu item</li>
                    </ul>

                    <h3>
                        Other Functions
                    </h3>
                    <ul class="list-unstyled">
                        <li>Different delivery price for each postal code</li>
                        <li>Secure payment gateway integration, separate for each location</li>
                        <li>Customers will not be able to place order after the store closes</li>
                        <li>Custom URL for your brand (landing to checkout without 3rd party applications)</li>
                        <li>Develop iOS &amp; Android applications to compliment your branded platform</li>
                    </ul>

                    
                </div>
                

            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <h3>
                        Support
                    </h3>
                    <ul class="list-unstyled">
                        <li>Unlike many other platforms, we support you with initial upload and launch</li>
                        <li>We assist you with monthly scheduled updates</li>
                        <li>Our tech support team will integrate your custom offers &amp; deals</li>
                        <li>Payment is received on daily basis</li>
                    </ul>
                    <a class="btn btn-success" href="http://freefoodcart.com/contact_us">Contact Us Today!</a>
                </div>
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="    height: 226px; width: 100%;">
                </div>
            </div>
        </section>

    

    </div>
</div>
@endsection