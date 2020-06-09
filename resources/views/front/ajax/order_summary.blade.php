    <div class="col-lg-6">
    <div class="order_box">
      <h2>ORDER SUMMARY</h2>
      <div class="order_box_inner table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>ITEM</th>
              <th style="text-align:center">QUANTITY</th>
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
              <td style="text-align:center">{{isset($v['quantity'])?$v['quantity']:0}}</td>
              <td style="text-align: right;">${{isset($v['price'])?number_format($v['price'],2):0}} </td>        
            </tr>
            @php
            $subtotal 
            +=(isset($v['price'])?number_format($v['price'], 2):0.00);
            @endphp 
            @endforeach
            @endif
            <tr>
              <td><strong>Product Subtotal:</strong></td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> ${{number_format($subtotal,2)}}</td>
            </tr>
            
            @php
              $deliveryCharge = Session::has('orderType') && Session::get('orderType') =='delivery'?number_format(Session::get('deliveryCharge'), 2):'0.00';

              $discount = Session::has('discount')?number_format(Session::get('discount'), 2):'0.00';
              $coupon_type = Session::get('coupon_type');
              if($coupon_type == 'fixed_discount'){

                $subtotal = $subtotal-$discount;
                $discount_val = '$'.number_format($discount,2);
              }else{
                $discountPrice = ($subtotal*$discount)/100;
                $subtotal = $subtotal-$discountPrice;
                $discount_val = '$'.number_format($discountPrice,2);

              }
            @endphp
            <tr>
              <td>Discount:</td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> {{$discount_val}}</td>
            </tr>
            <tr>
              <td><strong>Product Total:</strong></td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> ${{number_format($subtotal,2)}}</td>
            </tr>
            @php
            $subtotal += $deliveryCharge;
            $gst_price = Session::has('gst_per') && Session::get('gst_per')>0?(Session::get('gst_per')*$subtotal)/100:0.00;
            
            $total = (float)$subtotal+(float)$gst_price;
            @endphp
            
            <tr>
              <td>Delivery fee:</td>
              <td>-</td>
              <td style="text-align: right;"> ${{number_format($deliveryCharge,2)}}</td>
            </tr>
            <tr>
              <td><strong>Subtotal:</strong></td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> ${{number_format($subtotal,2)}}</td>
            </tr>
            <tr>
              <td>GST({{Session::has('gst_per')?Session::get('gst_per'):0}}%)</td>
              <td>&nbsp;</td>
              <td style="text-align: right;"> ${{number_format($gst_price,2)}}</td>
            </tr>
            <tr>
              <td></td>
              <td>&nbsp;</td>
              <td style="text-align: right;"><strong> Total:  ${{number_format($total,2)}}</strong></td>
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
          <h3>Balance Due :  ${{isset($total)?number_format($total,2):0}}</h3>
          <p>
            <span style="color:red">*</span>Payment Type
          </p>
         
          <ul id="squarespaceModal" class="pymnt_md">
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="cod" required> 
                  <span class="label-text">Pay with Paypal</span>
                </label>
                <input type="hidden" name="subtotal" value="{{isset($subtotal)?number_format($subtotal,2):0}}"> 
                <input type="hidden" name="gst_price" value="{{isset($gst_price)?number_format($gst_price,2):'0.00'}}"> 
                <input type="hidden" name="total" value="{{isset($total)?number_format($total,2):0}}"> 
              </div>
            </li>
            <li>
              <div class="form-check">
                <label>
                  <input type="radio" name="pay_method" value="card_payment"> <span class="label-text">Pay with Credit / Debit</span>
                </label>
              </div>
            </li>
            <label id="pay_method-error" class="error" style="color: red;" for="pay_method"></label>
          </ul>
           <button type='submit' name="save_order_btn" id="save_order_btn" class="btn_place_order">Place Order</button>
        </div>
      </div>
       <!--<a href="{{url('menu/'.session::get('category_id'))}}" class="btn btn-success btn-sm" style="margin-right:10px;">Continue Shopping</a>-->
    </div>


