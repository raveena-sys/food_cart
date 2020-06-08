@extends('layouts.app')
@section('content')

<style>
@media (max-width:600px){
.btn_chng_add {
    padding: 8px 8px;
    width: 148px;
    display: block;
    font-size: 85%;
    margin-left: -10px;
    }
  }
</style>
<div class="container order_summary">
  <form  action="{{url('save_order')}}" method='post' name="save_order" id="checkout_form" autocomplete="off">

  <div class="col-lg-12">
    <div class="order_box">
      <h2>ORDER REVIEW</h2>
      <div class="order_box_inner">
        <p>
          Please review your order below, choose a payment type, and then place your order.
        </p>
      </div>
    </div>

  </div>


    @csrf
     <div class="col-lg-6">
      <div class="checkout_container" style="margin: 0px auto; max-width:100%; margin-bottom:20px;">
        <h2>Enter Your Details</h2>
        <li>
            <label>Full Name</label>
            @csrf
            <input type="text" name="name" id="Name" placeholder="" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['name']:''}}">            
        </li>
        <li>
            <label>Address</label>
            <input type="text" name="address" id="address" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['address']:''}}">
        </li>
        <li>
            <label>Mobile No</label>
            <input type="text" name="mobile_no" id="mobile_no" maxlength="12" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['mobile_no']:''}}">
        </li>
        <li>
            <label>Email</label>
            <input type="email" name="email" id="email" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['email']:''}}">
        </li>
        <li>
            <label>City</label>
            <input type="text" name="city" id="city" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['city']:''}}">
        </li>
        <li>
            <label>Province</label>
            <input type="text" name="state" id="state" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['state']:''}}">
        </li>
        <li>
            <label>Postal Code</label>
            <input type="text" name="zipcode" id="zipcode" maxlength="6" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['zipcode']:''}}" >    
            <label id="zipcode-error" class="error" for="zipcode"></label>    
        </li>
        <li>
          <label>Additional Notes</label>
          <textarea name="additional_notes" id="additional_notes">{{Session::has('userinfo')?Session::get('userinfo')['additional_notes']:''}}</textarea>
        </li>
      </div>
    </div>
    <div class="order_cart_summary">
        {!!view('front.ajax.order_summary')->render()!!}
</div>
   
    <div class="col-lg-12">
     
    </div>
  </form>

  
</div>
@endsection