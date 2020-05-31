@extends('layouts.app')

@section('content')
<style>
    .home_video {
    
    text-align: center;
   
}
.home_bg .home_icon {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    /* min-height: 84.8vh; */
}
.home_icon {
    /* max-width: 800px; */
    text-align: center;
    /* margin: 10% auto; */
}

.home_icon_list_details {
    background-color: #0c0c0ccc;
    padding: 15px;
        border: 2px solid #fff;
    border-radius: 10px;
}
.home_icon_list_details span {
    font-weight: 700;
    color: #dcdcdc;
}
</style>
<section class="home_bg homepage" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">

      

        <div class="home_icon">
          <div class="home_video">
          <video width="100%" height="100%" controls>
            <source src="{{asset(isset($cms->home_video)?'/uploads/cms/'.$cms->home_video:'/img/coverr-apples-1572169622777.mp4')}}" type="video/mp4">
            <source src="{{asset(isset($cms->home_video)?'/uploads/cms/'.$cms->home_video:'/img/coverr-apples-1572169622777.mp4')}}" type="video/ogg">
            Your browser does not support the video tag.
          </video>
          </div>
            
            <div class="home_icon_list">
                <a href="{{ URL::To('store_list') }}">
                   <!-- <div class="home_icon_list_icon">
                        <img src="{{asset('img/check_sample.png')}}" />
                    </div>-->
                    <div class="home_icon_list_details">
                        <span>{{isset($cms->left_content)?ucfirst($cms->left_content):'Check Sample Website'}}</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
            </div>

            <div class="home_icon_list">
                <a href="{{ URL::To('contact_us') }}">
                    <!--<div class="home_icon_list_icon">
                        <img src="{{asset('img/contact_us.png')}}" />
                    </div>-->
                    <div class="home_icon_list_details">
                        <span>Contact Us</span>
                        <span><img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</section>
<div class="modal fade orderCompleteModal" data-keyboard="true" tabindex="-1" role="dialog" id="confirmOrderModal" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-content-inner">
        <div class="modal-header">
          <a href="javascript:void(0);" class="close" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i>
          </a>
        </div>
        <div class="modal-body text-center">
          <img src="{{asset('img/checked.svg')}}" class="img-fluid checkedIcon" />
          <h2>Order Complete </h2>
          <p>
            @if(Session::has('orderSuccessMsg'))
            {{Session::get('orderSuccessMsg')}}
            @endif
          </p>

          <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" >Ok</button>
        </div>
      </div>
    </div>
  </div>
</div>
@if( Session::has('orderSuccessMsg'))
   <script type="text/javascript">
      $(document).ready(function() {
        $('#confirmOrderModal').modal('show');        
        $('#confirmOrderModal').addClass('show');
        $('#confirmOrderModal').addClass('in');
       setTimeout(function(){
        $('#confirmOrderModal').modal('hide');
        $('#confirmOrderModal').removeClass('show');
        $('#confirmOrderModal').removeClass('in');
       },10000);
      });
   </script>
   
@endif

@endsection
