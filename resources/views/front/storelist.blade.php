    @extends('layouts.app')



    @section('content')
    <!-- <div class="side_decorum_left">
        <img src="{{ asset('img/side-decorum-1.png')}}">
    </div>
    <div class="side_decorum_right">
        <img src="{{ asset('img/side-decorum-2.png')}}">
    </div> -->
    <section class="store_list"
        style='background-image: url("{{asset(isset($cms->header_image)?'/uploads/cms/'.$cms->header_image:'/img/home_bg.jpg')}}") !important;'>
        <div class="container">
            <h2>{{isset($cms->page_title)?$cms->page_title:'Lorem Ipsum'}}</h2>
            <h3>{!!isset($cms->page_content)?$cms->page_content:'Lorem Ipsum'!!}</h3>
            <div class="clearfix"></div>
        </div>
    </section>


    <div class="container store_list_inner">
        <div class="row">

            <div class="col-lg-4 col-md-3 hidden-xs col-sm-4">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                    <ol class="carousel-indicators">
                        @php
                         $key = 0;
                        @endphp
                        @if(!empty($coupon) && count($coupon)>0)
                        @foreach($coupon as $k => $v)
                        @if(!empty($v->coupon_image) && $v->expired_at.' 23:59:59' >= date('Y-m-d H:i:s'))
                        <li data-target="#myCarousel" data-slide-to="{{$key}}" class="{{$key==0?'active':''}}"></li>
                        @php
                         $key++;
                        @endphp
                        @endif
                        @endforeach
                        @endif
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        @php
                         $key1 = 0;
                        @endphp
                        @if(!empty($coupon) && count($coupon)>0)
                        @foreach($coupon as $k => $v)
                        @if(!empty($v->coupon_image) && $v->expired_at.' 23:59:59' >= date('Y-m-d H:i:s'))

                          <div class="item {{$key1==0?'active':''}}">
                            <a href="{{url('order_type/'.$v->store_id)}}">
                                <img src="{{asset('/uploads/coupon/'.$v->coupon_image)}}" alt="Los Angeles" class="img-responsive" style="width:100%;">
                            </a>
                          </div>
                        @php
                         $key1++;
                        @endphp
                        @endif
                        @endforeach
                        @endif
                    </div>
                   <!--  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                      <span class="sr-only">Next</span>
                    </a> -->
              </div><!-- 


                <img src="{{ asset('img/store_banner_1.png')}}" class="img-responsive"><br>
                <br>
                <img src="{{ asset('img/store_banner_2.png')}}" class="img-responsive"><br> -->
            </div>
            <div class="col-lg-8 col-md-9 col-sm-8">
                <div class="row">
                    @if(!empty($details) && count($details)>0)
                    @foreach ($details as $val)
                    <div class="col-sm-6 col-md-12 col-xs-6 col">
                        <div class="store_list_grid custom_listing">
                            <div class="col-lg-4 col-md-3 pr-0">
                                <!-- <img src="{{ !empty($val->image)?asset('/uploads/users/'.$val->image):asset('img/store_pic_1.jpg') }}" class="img-responsive"> -->
                                <div class="thumb_img">
                                    <img src="{{getUserImage($val->image,'users')}}"
                                        class="img-responsive">
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6 custom_listing_mdl">
                                <h2><?php echo $val->name; ?></h2>
                                <ul class="ratting">
                                    <!-- <li><img src="{{asset('img/star.png')}}"></li>
                            <li><img src="{{asset('img/star.png')}}"></li>
                            <li><img src="{{asset('img/star.png')}}"></li>
                            <li><img src="{{asset('img/star.png')}}"></li>
                            <li><img src="{{asset('img/star.png')}}"></li> -->
                                    <li class="address">
                                        {{isset($val->address1)?'#'.$val->address1. ', '. $val->city->name.', '.$val->state->name. ', '.$val->pincode.' | ':''}}
                                        {{isset($val->phone_number)?$val->phone_number:''}}

                                    </li>
                                </ul>

                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        Store Timing <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                                        <ul class="list-unstyled">
                                            <?php 
                                date_default_timezone_set('Asia/Kolkata');
        
                                $open_time = json_decode($val->open_time, true);
                                $close_time = json_decode($val->close_time, true);
                                $date = date('Y-m-d H:i:s');
                                $day = date('D', strtotime($date));
                                $currentTime = date('H:i', strtotime($date));
                                $showOrderNow = false;
                                foreach ($open_time['open_time'] as $key => $value) {
                                    foreach ($close_time['close_time'] as $k => $v) {

                                        if($key == $k) {
                                            $str = compareDay($day,$key);

                                            if($str!='No' && $currentTime >$value &&  $currentTime <$v){
                                                $showOrderNow = true;
                                               
                                            }
                                            echo $string[$key] = '<li>'. ucfirst($key) .': '.date("g:i A", strtotime($value)).' to '.date("g:i A", strtotime($v)). '</li>'; 
                                        }                
                                    }
                                }
                               

                            ?>
                                            </li>
                                        </ul>

                                    </div>
                                </div>

                            </div>
                            @if($showOrderNow)
                            <div class="col-lg-3 col-md-3 pl-0">
                                <a href="{{ URL::To('order_type/'.$val->id) }}" class="btn btnOrderNow">
                                    Order Now <img src="{{ asset('img/next_arrow_white.png')}}">
                                </a>
                            </div>
                            @else

                            <div class="col-lg-3 pl-0">
                                <a href="javascript:;" class="btnOrderNow" data-toggle="modal"
                                    data-target="#myModal">Closed
                                    Now<img src="{{ asset('img/next_arrow_white.png')}}"></a>
                            </div>

                            @endif

                            <div class="col-sm-12 custom-list-footer">
                                <div class="">
                                    <ul class="list-inline">
                                        <li>
                                            <a href="{{isset($val->social->fb_url)?$val->social->fb_url:('javascript:void(0)')}}" target="_blank"><i class="fa fa-facebook-square"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="{{isset($val->social->twitter_url)?$val->social->twitter_url:('javascript:void(0)')}}" target="_blank"><i class="fa fa-twitter-square"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a href="{{isset($val->social->linkedin_url)?$val->social->linkedin_url:('javascript:void(0)')}}" target="_blank"><i class="fa fa-linkedin-square"
                                                    aria-hidden="true"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="">
                    <div class="col-lg-12">
                        <div class="totalPrice">
                            <div class="" style="color:#337ab7;  text-align: center; ">
                                <h3>Coming Soon</h3>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                @endif

                {{$details->links()}}
            </div>
        </div>
    </div>

    <div class="modal in" id="myModal" tabindex="-1" role="dialog" backdrop="static"
        style="display: none;z-index: 9999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Closed</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>
                        We're sorry the store you selected is closed now.
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Ok</button>

                </div>
            </div>
        </div>
    </div>
    @endsection