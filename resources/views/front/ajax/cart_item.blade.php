  @php
   $cartArray = Session::get('cartItem');
   $subtotal = 0;
  @endphp


  @if(!empty($cartArray) && count($cartArray)>0)

  <div class="cartWrapper">
    <input type="hidden" value="{{Request::segment(1)}}" id="url_param"> 
   @foreach($cartArray as $k => $v)

   <div class="cartbox__inner">
    <div class="cartBox">
      <img src="{{isset($v['image'])?asset('uploads/products/'.$v['image']):''}}" />
      <div class="cartBox__detail">
        <h4>{{isset($v['name'])?$v['name']:''}}</h4>
        <p>{{isset($v['description'])?$v['description']:''}}</p>
      </div>
    </div>
    <div class="containers">

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
        <strong>Cheese:</strong>Extra cheese
         <!-- (${{isset($v['extra_cheese_name'])?$v['extra_cheese_name']:0}}) -->
      </p>
      @endif
    </div>
   


    <div class="cartBox-footer">
      <div class="countWrap">
        <button type="button" id="sub" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" class="sub sub_item" data-custom="{{isset($v['custom'])?1:0}}" data-sub="1">-</button>
        <input class="count" type="text" id="item_count_{{isset($v['product_id'])?$v['product_id']:''}}" value="{{isset($v['quantity'])?$v['quantity']:''}}" min="1" max="100">
        <button type="button" id="add" class="add add_item" data-product_id="{{isset($v['product_id'])?$v['product_id']:0}}" data-price="{{isset($v['price'])?$v['price']:0}}" data-custom="{{isset($v['custom'])?1:0}}" data-sub="0">+</button>
      </div>
      <div class="rightSide">
        <span class="price">${{isset($v['price'])?round($v['price'],2):0}}</span>
        @php
        $subtotal 
        +=(isset($v['price'])?round($v['price'], 2):0);
        @endphp
      </div>
    </div>
   </div>
   @endforeach
  </div>
  @endif 

  @if($subtotal)
  <div class="totalPrice">
    <div class="totalPrice__inner">
      <div class="left">
        Subtotal
      </div>
      <div class="right">
        ${{$subtotal}}
      </div>
    </div>
    <a href="{{url('checkout')}}" class="btn btn-success btn-block">CHECKOUT</a>
  </div>
  @else
  @if(!empty($products) && count($products)>0)
  <div class="totalPrice emptyCart"> 
    <div class="">
      <h3>Your Cart is empty</h3>
        <p>Please add some items from the menu.</p>
      </div>
    <!-- <a href="javascript:void(0);" class="btn btn-success btn-block">Add Item</a> -->
  </div>
  @endif
  @endif  

