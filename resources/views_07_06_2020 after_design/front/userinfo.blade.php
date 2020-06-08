@extends('layouts.app')
@section('content')
<!-- <form id="checkout_form" method="POST" class="needs-validation" action="{{URL::To('add-checkout')}}"> -->
  <form class="needs-validation" id="checkout_form" action="{{url('save_user_detail')}}" method="post">
    @csrf
    <div class="checkout_container">
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
            <input type="text" name="zipcode" id="zipcode" maxlength="6" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['zipcode']:''}}">           
        </li>
        <li>
          <label>Additional Notes</label>
          <textarea name="additional_notes" id="additional_notes">{{Session::has('userinfo')?Session::get('userinfo')['additional_notes']:''}}</textarea>

        <button type="submit" class="btn_checkout">Checkout</button>
    </div>
</form>
{!! JsValidator::formRequest('App\Http\Requests\CheckoutRequest','#checkout_form') !!}
@endsection