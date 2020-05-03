  <div class="col-lg-8 ">
    @php
      $cartArray = Session::get('cartItem');
      $subtotal = 0;
    @endphp

    @if(!empty($cartArray))
    @foreach($cartArray as $k => $v)
      <div class="cartbox__inner">
        <div class="cartBox">
          <img src="{{!empty($v['image'])?asset('uploads/products/'.$v['image']):asset('img/no-image.jpg')}}" />
          <div class="cartBox__detail">
            <h4>{{isset($v['name'])?$v['name']:''}}</h4>
            <p>{{isset($v['description'])?$v['description']:''}}</p>
          </div>
        </div>
        <div class="container">

          {!!isset($v['size_master_name'])?'<p><strong>Pizza Size:</strong> '.$v['size_master_name'].'</p>':''!!}

          {!!isset($v['crust_master_name'])?'<p><strong>Pizza Crust:</strong> '.$v['crust_master_name'].'</p>':''!!}

          {!!isset($v['sauce_master_name'])?'<p><strong>Pizza Sauce:</strong> '.$v['sauce_master_name'].'</p>':''!!}

          @if(!empty($v['dip_master_name']))
          <p>
            <strong>Dips:</strong>
            @foreach($v['dip_master_name'] as $val)
            {{$val}}
            @endforeach
            <!-- (${{isset($v['dip_master_price'])?$v['dip_master_price']:0}}) -->
          </p>
          @endif

          @if(!empty($v['topping_master_name']))
          <p>
            <strong>Toppings:</strong>
            @foreach($v['topping_master_name'] as $val1)
            {{$val1}}
            @endforeach
            <!-- ($ {{isset($v['topping_master_price'])?$v['topping_master_price']:0}}) -->
          </p>
          @endif
          @if(!empty($v['extra_cheese_name']))
          <p>
            <strong>Extra cheese</strong> <!-- (${{isset($v['extra_cheese_name'])?$v['extra_cheese_name']:0}}) -->
          </p>
          @endif
        </div>
        <div class="cartBox-footer">
          <div class="countWrap">
            <button type="button" id="sub" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" class="sub_item" data-custom="{{isset($v['custom'])?1:0}}">-</button>
            <input class="count" type="text" id="item_count_{{isset($v['product_id'])?$v['product_id']:''}}" value="{{isset($v['quantity'])?$v['quantity']:''}}" min="1" max="100">
            <button type="button" id="add" class="add_item" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" data-custom="{{isset($v['custom'])?1:0}}">+</button>

          </div>
          <div class="rightSide">
            <span class="price">${{isset($v['price'])?round($v['price'],2):0}}</span>
          </div>
        </div>
      </div>  
      @php
      $subtotal 
      +=(isset($v['price'])?round($v['price'], 2):0);
      @endphp 
    @endforeach
    <a href="{{url('menu/'.session::get('category_id'))}}" class="btn btn-success btn-sm">Continue Shopping</a>
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
      <div class="addressBox">

       <h4>CURRENT ADDRESS</h4>
       <p>{{Session::has('userinfo')?ucfirst(Session::get('userinfo')['address']).','. ucfirst(Session::get('userinfo')['city']). ' ('. ucfirst(Session::get('userinfo')['state']).') '. Session::get('userinfo')['zipcode']:''}}</p>
       <a href="{{url('checkout/user')}}">Edit Address</a>
      </div>
    @endif
    <div class="totalPrice">
      <div class="totalPrice__inner">
        <div class="left">
          Sub Total
        </div>
        <div class="right">
          ${{$subtotal}}
        </div>
      </div>
      <div class="totalPrice__inner">
        <div class="left">
          Discount
        </div>
        <div class="right">
          $0
        </div>
      </div>
      <div class="totalPrice__inner">
        <div class="left">
          Grand Total
        </div>
        <div class="right">
          ${{$subtotal}}
        </div>
      </div>

      <a href="{{url('checkout/user')}}" class="btn btn-success btn-block">Place Order</a>
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