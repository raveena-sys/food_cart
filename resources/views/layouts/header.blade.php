
@if(Request::segment(1)=='menu' || Request::segment(1)=='checkout' || Request::segment(1)=='save_user_detail')

<nav class="navbar navbar-default navbar-me" id="header">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed menu-collapsed-button" data-toggle="collapse" data-target="#navbar-primary-collapse"
          aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand site-logo" href="{{url('/')}}"><img src="{{ asset('img/logo.png')}}" class="logo"></a>
      </div>

      <div class="collapse navbar-collapse navbar-right  header-right-menu" id="navbar-primary-collapse">
        <ul class="nav navbar-nav ">
          <li class="page-scroll">
            <a class="header" href="javascript:"><img src="{{asset('img/order_type_ico.png')}}"> <span>Order Type</span>

            </a>
            @if(Session::has('store_id'))
              @php
              $store = \App\Models\StoreMaster::where('id', Session::get('store_id'))->first();
              @endphp            
            @endif
            <div class="dropdown order-type">
              <button class="btn-order-type dropdown-toggle" type="button" data-toggle="dropdown">{{Session::has('orderType')?ucfirst(Session::get('orderType')):''}}
                <span class="caret"></span></button>
              @if(isset($store->pickup_delivery) && Request::segment(1)!=='save_user_detail')
              <ul class="dropdown-menu">
                @if($store->pickup_delivery == 'pickup' || $store->pickup_delivery == 'both')
                <li><a href="{{ URL::To('pickup/menu/0/header') }}">Pick Up</a></li>
                @endif
                @if($store->pickup_delivery == 'delivery' || $store->pickup_delivery == 'both')
                <li><a href="{{ URL::To('delivery/menu/'.$store->delivery_charge.'/header') }}" >Delivery</a></li>
                @endif   
              </ul>
             @endif
              <!-- <select class="btn-order-type dropdown-toggle" id="order_type" >               
                <option style="background-color: black;" value="pickup" {{(Session::has('orderType')) && (Session::get('orderType') == 'pickup')?'selected':''}}>Pick Up</option>

              	<option style="background-color: black;" value="delivery" {{(Session::has('orderType')) && (Session::get('orderType') == 'delivery')?'selected':''}}>Delivery</option>
              </select> -->
            </div>
          </li>
          @if(Session::has('userinfo'))
          <li>
            <a class="del_add" href="javascript:"><img src="{{asset('img/map_pin.png')}}">{{Session::has('userinfo')?ucfirst(Session::get('userinfo')['address']).','. ucfirst(Session::get('userinfo')['city']). ' ('. ucfirst(Session::get('userinfo')['state']).') ':''}}</a>
            <button class="btn-chng-add" onclick= window.location.href="{{url('checkout/user')}}">Change</button>
          </li>
          @endif
          <li>
            <a class="" href="{{url('contact_us')}}"><img src="{{asset('img/contact_ico.png')}}"> Contact Us</a>
          </li>

        </ul>
      </div><!-- /.navbar-collapse -->
    </div>
</nav>
<script>
    $(document).ready(function(){
        var headerHeight = $('#header, header').height();
        var footerHeight = $('#footer').height();
        var winHeight = $(window).height() - (headerHeight+footerHeight);
        $('#mainContent').css('min-height', winHeight);
    })
</script>
@else
<header>
    <a href="{{ URL::To('/') }}">
        <img  src="{{ asset('img/logo.png')}}" class="logo"/>
    </a>
</header>
<script>
    $(document).ready(function(){
        var footerHeight = $('#footer').height();
        var winHeight = $(window).height() - footerHeight;
        $('#mainContent').css('min-height', winHeight);
    })
</script>
@endif


