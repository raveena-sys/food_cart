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
                <div class="col-lg-6 col-xs-12 sliderBox1">
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
                    <a class="btn btn-success" href="{{ URL::To('branded_food_ordering_platform') }}">Branded Food Ordering Platform</a>
                    <a class="btn btn-success" href="{{ URL::To('integrated_digital_marketing') }}"> Integrated Digital Marketing </a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Integrated Business
                        Intelligent
                        POS</a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Integrated Delivery
                        Application</a>
                    <a class="btn btn-success" title="Launching soon" data-toggle="tooltip">Franchise Development</a>
                </div>
                <div class="col-lg-6 col-xs-12">
                <div class="aboutUsSlider">
                    <div class="item"> <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height:430px;"></div>
                    <div class="item"> <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height:430px;"></div>
                    <div class="item"> <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height:430px;"></div>
                    <div class="item"> <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height:430px;"></div>
                    <div class="item"> <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height:430px;"></div>
                </div>
                   
                </div>
            </div>
        </section>
        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 351px;width: 100%;">
                </div>
                <div class="col-lg-6 col-xs-12">
                    <h2>
                        Our Vision
                    </h2>
                    <p>
                        Free Food Cart will become a leading industry specific platform that delivers a complete
                        solution to our clients’ needs. We will achieve our vision through:
                    </p>
                    <ul class="list-unstyled">
                        <li>Rapid development and scaling</li>
                        <li>Building close relationships within the ecosystem</li>
                        <li>Appreciating our customers diversity</li>
                        <li>Continually enhancing customer experience by always putting our customer first</li>
                        <li>Respecting, nurturing and supporting our pool of experts through positive workplace practices</li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
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
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 426px;">
                </div>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-lg-6 col-xs-12">
                    <img src="{{asset('images/dummy.jpg')}}" class="img-responsive img-sec" style="height: 526px;">
                </div>
                <div class="col-lg-6 col-xs-12">
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

    </div>
</div>
@endsection