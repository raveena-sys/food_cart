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

        <section>
            <div class="row">
              
                <div class="col-lg-6 col-xs-12">
                    <h2 class="mb-2">
                        Integrated Digital Marketing
                    </h2>
                    <p class="sub-heading">
                        Marketing your online store is vital to bring awareness of your store to potential customers.
                        Our team of digital marketing experts will help you with:
                    </p>

                    <h3>
                        Social Media: Facebook | Instagram | Twitter | YouTube
                    </h3>
                    <ul class="list-unstyled">
                        <li>Branding &amp; organic development</li>
                        <li>Sponsored campaigns</li>
                    </ul>
                    <h3>Reputation Management</h3>
                    <ul class="list-unstyled">
                        <li>Reviews on Google &amp; Facebook</li>
                        <li>Ongoing Reputation Management</li>
                    </ul>
                   
                </div>
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 349px; width: 100%">
                </div>

            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 380px;  width: 100%;">
                </div>
                <div class="col-lg-6 col-xs-12">
                   
                    <h3>Search Engine Optimization &amp; Marketing</h3>
                    <ul class="list-unstyled">
                        <li>On-page &amp; off-page optimization</li>
                        <li>Sponsored campaigns</li>
                    </ul>
                    <h3>Branding &amp; Design Support</h3>
                    <ul class="list-unstyled">
                        <li>Engaging Graphics &amp; Videos</li>
                        <li>Menu Design</li>
                    </ul>
                    <h3>Food &amp; Beverage Marketing Kit</h3>
                    <ul class="list-unstyled">
                        <li>Take an integrated approach to marketing with our F&amp;B Marketing Kit which includes
                            engaging in-store screens and kiosks, menus, and all of our digital capabilities</li>
                    </ul>
                    <a class="btn btn-success" href="http://freefoodcart.com/contact_us">Contact Us Today!</a>
                </div>

            </div>
        </section>

    </div>
</div>
@endsection