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
  <form  action="{{url('save_order')}}" method='post' name="save_order" id="checkout_form">

  <div class="col-lg-12">
    <div class="order_box">
      <h2>ORDER REVIEW</h2>
      <div class="order_box_inner">
        <p>
          Please review your order below, choose a payment type, and then place your order.
        </p>
      </div>
    </div>

   <!--  <div class="order_box">
      <h2>ORDER SETTINGS</h2>
      <div class="order_box_inner">
        <h3>Service Method & Location</h3>
        <h4>DELIVERY to test</h4>
        <p>Delivery Instructions For Driver</p>
        <textarea name="delivery_ins"></textarea>
        <p>(Note: Gate code, ring the door bell, etc.)</p>
        <h3>Order Timing - Now</h3>
        <p>
          Your order will be ready in 20 - 30 minutes for pickup and delivered between 30 - 50 minutes based on location, quantity of order and few other factors.
         
        </p>
        <h3>TO PLACE YOUR ORDER, complete the fields below.</h3>
      </div>
    </div> -->
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
            <input type="text" name="zipcode" id="zipcode" maxlength="6" required="" value="{{Session::has('userinfo')?Session::get('userinfo')['zipcode']:''}}">           
        </li>
        <li>
          <label>Additional Notes</label>
          <textarea name="additional_notes" id="additional_notes">{{Session::has('userinfo')?Session::get('userinfo')['additional_notes']:''}}</textarea>
        </li>
      </div>
    </div>
    <div class="col-lg-6">
    <div class="order_box">
      <h2>ORDER SUMMARY</h2>
      <div class="order_box_inner table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ITEM</th>
              <th>QTY</th>
              <th style="text-align: right;">PRICE</th>
            </tr>
          </thead>
          <tbody>
            @php
            $cartArray = Session::get('cartItem');
            $subtotal = 0;
            @endphp


            @if(!empty($cartArray))

            @foreach($cartArray as $k => $v)
            <tr>
              <td>{{isset($v['name'])?$v['name']:''}}</td>
              <td>{{isset($v['quantity'])?$v['quantity']:0}}</td>
              <td style="text-align: right;">${{isset($v['price'])?number_format($v['price'],2):0}} </td>        
            </tr>
            @php
            $subtotal 
            +=(isset($v['price'])?number_format($v['price'], 2):0.00);
            @endphp 
            @endforeach
            @endif


            @php
              $delCharge = Session::has('orderType') && Session::get('orderType') =='delivery'?number_format(Session::get('delCharge'), 2):'0.00';
            
            @endphp
            <tr>
              <td>Delivery fee</td>
              <td>-</td>
              <td style="text-align: right;"> ${{$delCharge}}</td>
            </tr>
            
            @php
            $subtotal += $delCharge;
            $gst_price = Session::has('gst_per') && Session::get('gst_per')>0?(Session::get('gst_per')*$subtotal)/100:0.00;
            
            $total = (float)$subtotal+(float)$delCharge+(float)$gst_price;
            @endphp
            <tr>
              <td>Subtotal</td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> ${{number_format($subtotal,2)}}</td>
            </tr>
            <tr>
              <td>GST({{Session::has('gst_per')?Session::get('gst_per'):0}}%)</td>
              <td>-</td>
              <td style="text-align: right;"> ${{number_format($gst_price,2)}}</td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td style="text-align: right;">Total: <strong> ${{number_format($total,2)}}</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- <a href="{{url('checkout/user')}}" class="btn_chng_add">Change Your Address</a> -->
  
  </div>
    <div class="col-lg-6">
      <div class="order_box">
        <h2>PAYMENT INFORMATION</h2>
        <div class="order_box_inner">
          <h3>Balance Due :  ${{number_format($total,2)}}</h3>
          <p>
            <span style="color:red">*</span>Payment Type
          </p>


          <ul id="squarespaceModal" class="pymnt_md">
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="cod" required> 
                  <span class="label-text">Pay with Cash</span>
                </label>
                <input type="hidden" name="subtotal" value="{{number_format($subtotal,2)}}"> 
                <input type="hidden" name="total" value="{{number_format($total,2)}}"> 
              </div>
            </li>
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="card_payment"> <span class="label-text">Pay with Credit / Debit</span>
                </label>
              </div>
            </li>
            <label id="pay_method-error" class="error" for="pay_method"></label>
          </ul>
           <button type='submit' name="save_order_btn" id="save_order_btn" class="btn_place_order">Place Order</button>
        </div>
      </div>
       <!--<a href="{{url('menu/'.session::get('category_id'))}}" class="btn btn-success btn-sm" style="margin-right:10px;">Continue Shopping</a>-->
    </div>
   



    <div class="col-lg-12">
     
    </div>
  </form>

  
</div>
@endsection