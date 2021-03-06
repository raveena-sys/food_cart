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
</style>
<section class="home_bg homepage" style= 'background-image: url("{{asset(isset($cms->background_image)?'/uploads/cms/'.$cms->background_image:'/img/home_bg.jpg')}}") !important;'>
    <div class="container">

      

        <div class="home_icon">
          <div class="home_video">
          <video width="100%" height="100%" controls>
            <source src="{{asset('img/coverr-apples-1572169622777.mp4')}}" type="video/mp4">
            <source src="{{asset('img/coverr-apples-1572169622777.mp4')}}" type="video/ogg">
            Your browser does not support the video tag.
          </video>
          </div>
            
            <div class="home_icon_list">
                <a href="{{ URL::To('store_list') }}">
                    <div class="home_icon_list_icon">
                        <img src="{{asset('img/check_sample.png')}}" />
                    </div>
                    <div class="home_icon_list_details">
                        <span>{{isset($cms->left_content)?ucfirst($cms->left_content):'Check Sample Website'}}</span>
                        <span>
                            <img src="{{asset('img/next_arrow.png')}}" /></span>
                    </div>
                </a>
            </div>

            <div class="home_icon_list">
                <a href="{{ URL::To('contact_us') }}">
                    <div class="home_icon_list_icon">
                        <img src="{{asset('img/contact_us.png')}}" />
                    </div>
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


<div class="modal in" id="confirmOrderModal" tabindex="-1" role="dialog" backdrop="static" style="display: none;z-index: 9999;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#4e864d">
        <h2 class="modal-title">Order Confirmation</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>
            @if(Session::has('orderSuccessMsg'))
            {{Session::get('orderSuccessMsg')}}
            @endif
        </h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close" >Ok</button>
        
      </div>
    </div>
  </div>
</div>
@if( Session::has('orderSuccessMsg'))
   <script type="text/javascript">
      $(document).ready(function() {
        $('#confirmOrderModal').modal('show');
       setTimeout(function(){
        $('#confirmOrderModal').modal('hide');

       },5000);
      });
   </script>
   
@endif

@endsection
