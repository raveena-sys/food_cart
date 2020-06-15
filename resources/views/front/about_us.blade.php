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
                <div class="col-lg-6">
                    <h2>
                        About Us
                    </h2>
                    <p>
                        Many restaurant owners complain about the high commissions being charged by food delivery apps.
                        Moderately successful restaurants used to generate between 10% to 20% in net profit and now the
                        food
                        delivery apps have started charging 20% to 30% in commissions, leading either to a net loss or
                        maybe
                        to recuperate these commissions, you have decided to raise the menu prices on these apps -
                        making
                        your brand look expensive!
                        Sounds familiar?
                        We are joining forces with the restaurant owners by helping them take control by offering the
                        following solutions:
                    </p>
                    <a class="btn btn-success" href="http://freefoodcart.com">Branded Food Ordering Platform</a>
                    <a class="btn btn-success" href="http://freefoodcart.com"> Integrated Digital Marketing </a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Integrated Business
                        Intelligent
                        POS</a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Integrated Delivery
                        Application</a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Franchise Development</a>
                </div>
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>
                <div class="col-lg-6">
                    <h2>
                        Our Vision
                    </h2>
                    <p>
                        Free Food Cart will become a leading industry specific platform that delivers a complete
                        solution to our clients’ needs. We will achieve our vision through:
                    </p>
                    <ul class="list-unstyled">
                        <li>We place our clients first</li>
                        <li>We strive for continuous self-improvement</li>
                        <li>We are passionate and determined</li>
                        <li>We treat everyone with respect and dignity</li>
                        <li>We respect our work and our team</li>
                        <li>We deliver on our promises</li>
                        <li>We exceed expectations by listening, caring and serving</li>
                        <li>We strive for direct, open and honest communication</li>
                        <li>We promote integrity, honesty, trust and ethics</li>
                        <li>We embrace creativity, innovation and risk-taking</li>
                        <li>We are accountable and do what we say we’ll do</li>
                        <li>We do the right thing</li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-lg-6">
                    <h2>
                        Our Mission
                    </h2>
                    <p>
                        We are an innovative company that delivers complete and cost-effective solutions. We bring a
                        world of strategic, technical, design and implementation expertise to our customers, increasing
                        the effectiveness of their marketing initiatives and helping them meet the challenges of todays’
                        constantly changing environment. Our Mission is to:
                    </p>
                    <ul class="list-unstyled">

                        <li>Deliver a complete solution through an integrated approach</li>
                        <li>Foster innovation, creativity and out-of-the-box thinking</li>
                        <li>Keep you always a step ahead of the competition</li>
                        <li>Help you grow your business through understanding of your market, of your customers, of your
                            competition, and by using this knowledge to create commercial advantage</li>
                        <li>Pay attention to small details</li>
                        <li>Maximize your return on investment</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>
                <div class="col-lg-6">
                    <h2>
                        Our Core Values
                    </h2>
                    <p>
                        We always strive to deliver above-expectation outcomes for our clients, to build long-lasting
                        relationships, and to provide a positive working environment for our team. The core values that
                        guide all our actions and interactions are:
                    </p>
                    <ul class="list-unstyled">

                        <li>We place our clients first</li>
                        <li>We strive for continuous self-improvement</li>
                        <li>We are passionate and determined</li>
                        <li>We treat everyone with respect and dignity</li>
                        <li>We respect our work and our team</li>
                        <li>We deliver on our promises</li>
                        <li>We exceed expectations by listening, caring and serving</li>
                        <li>We strive for direct, open and honest communication</li>
                        <li>We promote integrity, honesty, trust and ethics</li>
                        <li>We embrace creativity, innovation and risk-taking</li>
                        <li>We are accountable and do what we say we’ll do</li>
                        <li>We do the right thing</li>
                    </ul>
                </div>

            </div>
        </section>


        <section>
            <div class="row">
                <div class="col-lg-6">
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
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>

            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-lg-6">
                    <img src="{{asset('images/rice_bowl.jpg')}}" class="img-responsive img-sec">
                </div>
                <div class="col-lg-6">
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