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
  <form  action="{{url('save_order')}}" method='post' name="save_order" id="save_order">

  <div class="col-lg-6">
    <div class="order_box">
      <h2>ORDER REVIEW</h2>
      <div class="order_box_inner">
        <p>
          Please review your order below, choose a payment type, then click "Place Your Order"
        </p>
      </div>
    </div>

    <div class="order_box">
      <h2>ORDER SETTINGS</h2>
      <div class="order_box_inner">
        <h3>Service Method & Location</h3>
        <h4>DELIVERY to test</h4>
        <p>Delivery Instructions For Driver</p>
        <textarea name="delivery_ins"></textarea>
        <p>(Note: Gate code, ring the door bell, etc.)</p>
        <h3>Order Timing - Now</h3>
        <p>
          Your order will be ready 20 - 30 MINUTES for PICKUP and DELIVERED between 30 - 50 MINUTES.
          Note: Delivery time may vary based on location, quantity of order and few other factors. 
        </p>
        <h3>TO PLACE YOUR ORDER, complete the fields below.</h3>
      </div>
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
              <th>PRICE</th>
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
              <td>${{isset($v['price'])?round($v['price'],2):0}} </td>        
            </tr>
            @php
            $subtotal 
            +=(isset($v['price'])?round($v['price'], 2):0);
            @endphp 
            @endforeach
            @endif
            <tr>
              <td>GST(0%)</td>
              <td>-</td>
              <td> $0</td>
            </tr>
            @if(Session::has('orderType') && Session::has('orderType') =='delivery')
            <tr>
              <td>Delivery fee</td>
              <td>-</td>
              <td> ${{Session::get('delCharge')}}</td>
            </tr>
            
            @endif
            @php
          
            $total = (float)$subtotal+(float)Session::get('delCharge');
            @endphp
            <tr>
              <td>Subtotal</td>
              <td>&nbsp;</td>
              <td> ${{$subtotal}}</td>
            </tr>
            <tr>
              <td><a href="{{url('checkout/user')}}" class="btn_chng_add">Change Your Address</a></td>
              <td>&nbsp;</td>
              <td>Total: <strong> ${{$total}}</strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="clearfix"></div>
    @csrf
    <div class="col-lg-12">
      <div class="order_box">
        <h2>PAYMENT INFORMATION</h2>
        <div class="order_box_inner">
          <h3>Balance Due :  ${{$total}}</h3>
          <p>
            <span style="color:red">*</span>Payment Type
          </p>


          <ul id="squarespaceModal" class="pymnt_md">
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="cod"> 
                  <span class="label-text">Pay with Cash at the door</span>
                </label>
                <input type="hidden" name="subtotal" value="{{$subtotal}}"> 
                <input type="hidden" name="total" value="{{$total}}"> 
              </div>
            </li>
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="card_payment"> <span class="label-text">Pay with Credit / Debit at the door</span>
                </label>
              </div>
            </li>
            <label id="pay_method-error" class="error" for="pay_method"></label>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-12">
      <button type='submit' name="save_order_btn" id="save_order_btn" class="btn_place_order">Place Order</button>
    </div>
  </form>
</div>
@endsection