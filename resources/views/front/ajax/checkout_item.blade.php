  <div class="col-lg-8 ">
    @php
      $cartArray = Session::get('cartItem');
      $subtotal = 0;
    @endphp

    @if(!empty($cartArray))
    @foreach($cartArray as $k => $v)
      <div class="cartbox__inner" id="scroll_{{$k}}">
        <div class="cartBox">
          <img src="{{!empty($v['image'])?asset('uploads/products/'.$v['image']):asset('img/no-image.jpg')}}" />
          <div class="cartBox__detail">
            <h4>{{isset($v['name'])?$v['name']:''}}</h4>
            <p>{{isset($v['description'])?$v['description']:''}}</p>
          </div>
        </div>
        @if(isset($v['special']))
        <div class="containers">
          @if(!empty($v['extra']))
          @foreach($v['extra'] as $key=> $value1)

          {!!isset($value1['product_name'])?'<p><strong>Pizza Type:</strong> '.$value1['product_name'].'</p>':''!!}
          {!!isset($value1['size_master_name'])?'<p><strong>Pizza Size:</strong> '.$value1['size_master_name'].'</p>':''!!}

          {!!isset($value1['crust_master_name'])?'<p><strong>Pizza Crust:</strong> '.$value1['crust_master_name'].'</p>':''!!}

          {!!isset($value1['sauce_master_name'])?'<p><strong>Pizza Sauce:</strong> '.$value1['sauce_master_name'].'</p>':''!!}

          @if(!empty($value1['dip_master_name']))
          <p>
            <strong>Dips:</strong>
            {{implode(', ',$value1['dip_master_name'])}}
            <!-- @foreach($value1['dip_master_name'] as $val)
            {{rtrim($val,",")}}</br>
            @endforeach -->
            <!-- (${{isset($v['dip_master_price'])?$v['dip_master_price']:0}}) -->
          </p>
          @endif

          @if(!empty($value1['topping_master_name']))
          <p>
            <strong>{{(!empty($value1['topping_from']) && $value1['topping_from']=='topping_wing_flavour')?"Wings Flavour":"Toppings"}}:</strong>
            {{implode(', ',$value1['topping_master_name'])}}
            <!-- ($ {{isset($v['topping_master_price'])?$v['topping_master_price']:0}}) -->
          </p>
          @endif
          @if(!empty($value1['topping_sauce_master_name']))
          <p>
            <strong>Sauces:</strong>
             {{implode(', ', $value1['topping_sauce_master_name'])}}
            
            <!-- ($ {{isset($v['topping_master_price'])?$v['topping_master_price']:0}}) -->
          </p>
          @endif
          @if(!empty($value1['extra_cheese_name']))
          <p>
            <strong>Cheese:</strong> Extra Cheese
             <!-- (${{isset($v['extra_cheese_name'])?$v['extra_cheese_name']:0}}) -->
          </p>
          @endif
          <hr>
          @endforeach
          @endif
        </div>
        @else
        <div class="containers">

          {!!isset($v['size_master_name'])?'<p><strong>Pizza Size:</strong> '.$v['size_master_name'].'</p>':''!!}

          {!!isset($v['crust_master_name'])?'<p><strong>Pizza Crust:</strong> '.$v['crust_master_name'].'</p>':''!!}

          {!!isset($v['sauce_master_name'])?'<p><strong>Pizza Sauce:</strong> '.$v['sauce_master_name'].'</p>':''!!}

          @if(!empty($v['dip_master_name']))
          <p>
            <strong>Dips:</strong>
            {{implode(', ',$v['dip_master_name'])}}
            <!-- (${{isset($v['dip_master_price'])?$v['dip_master_price']:0}}) -->
          </p>
          @endif

          @if(!empty($v['topping_master_name']))
          <p>
            <strong>{{(!empty($v['topping_from']) && $v['topping_from']=='topping_wing_flavour')?"Wings Flavour":"Toppings"}}:</strong>
           {{implode(', ',$v['topping_master_name'])}}
            <!-- ($ {{isset($v['topping_master_price'])?$v['topping_master_price']:0}}) -->
          </p>
          @endif
          @if(!empty($v['topping_sauce_master_name']))
          <p>
            <strong>Sauces:</strong>{{implode(', ',$v['topping_sauce_master_name'])}}
            <!-- ($ {{isset($v['topping_master_price'])?$v['topping_master_price']:0}}) -->
          </p>
          @endif
          @if(!empty($v['extra_cheese_name']))
          <p>
            <strong>Cheese:</strong> Extra Cheese <!-- (${{isset($v['extra_cheese_name'])?$v['extra_cheese_name']:0}}) -->
          </p>
          @endif
        </div>
        @endif
        <div class="cartBox-footer">
          <div class="countWrap">
            <button type="button" id="sub" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" class="sub_item" data-custom="{{isset($v['custom'])?1:0}}">-</button>
            <input class="count" type="text" id="item_count_{{isset($v['product_id'])?$v['product_id']:''}}" value="{{isset($v['quantity'])?$v['quantity']:''}}" min="1" max="100">
            <button type="button" id="add" class="add_item" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" data-custom="{{isset($v['custom'])?1:0}}">+</button>

          </div>
          <div class="rightSide">
            <span class="price">${{isset($v['price'])?number_format($v['price'],2):0.00}}</span>
          </div>
        </div>
      </div>  
      @php
      $subtotal 
      +=(isset($v['price'])?number_format($v['price'], 2):0.00);
      $total = $subtotal; 
      @endphp 
    @endforeach
      <div class="row">
        <div class="col-md-5">
          <a href="{{url(Session::get('orderType').'/menu/0')}}" class="btn btn-success btn-sm">Continue Shopping</a>
        </div>
        <div class="col-md-5">
          
          <input class="form-control" name="coupon_code" id="coupon_code" placeholder="Coupon Code" value="{{Session::has('coupon_code')?Session::get('coupon_code'):''}}">
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-success btn-sm" name="apply" id="applyCoupon">Apply</button>
            
        </div>
      </div>
    @else
      <div class="">
        <div class="col-lg-12">
          <div class="cartRight cart_item">
            <div class="totalPrice"> 
              <div class="" style="color:#337ab7">
              <h3>Your Cart is empty</h3>
                <p>Please add some items from the menu.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
  @if($subtotal)     
  <div class="col-lg-4  col-xs-12">
    
    @if(Session::has('userinfo'))
      <!-- <div class="addressBox">

       <h4>CURRENT ADDRESS</h4>
       <p>{{Session::has('userinfo')?ucfirst(Session::get('userinfo')['address']).",". ucfirst(Session::get('userinfo')['city']). ' ('. ucfirst(Session::get('userinfo')['state']).') '. Session::get('userinfo')['zipcode']:''}}</p>
       <a href="{{url('checkout/user')}}">Edit Address</a>
      </div> -->
    @endif
    <div class="totalPrice">
      <div class="totalPrice__inner">
        <div class="left">
          Sub Total:
        </div>
        <div class="right">
          ${{number_format($subtotal,2)}}
        </div>
      </div>
      <div class="totalPrice__inner">
        <div class="left">
          Discount:
        </div>
        <div class="right">
          @if(Session::has('discount'))
          @php
            $discount = Session::get('discount');
            $coupon_type = Session::get('coupon_type');
            if($coupon_type == 'fixed_discount'){

              $total = $subtotal-$discount;
              echo '$'.number_format($discount,2);
            }else{
              $discountPrice = ($subtotal*$discount)/100;
              $total = $subtotal-$discountPrice;
              echo '$'.number_format($discountPrice,2);

            }
          @endphp
          @else
          ${{number_format(0,2)}}
          @endif
        </div>
      </div>
      <div class="totalPrice__inner">
        <div class="left">
          Total:
        </div>
        <div class="right">
          
          ${{number_format($total,2)}}
        </div>
      </div>

      <a href="{{url('save_user_detail')}}" class="btn btn-success btn-block">Checkout <!-- Place Order --></a>
    </div>
  </div>
  @endif  

  @if(empty($cartArray))

  <script>
    setTimeout(function(){
      window.location.href= "{{url('menu/'.Session::get('category_id'))}}";
    },1000);
  </script>
  @endif