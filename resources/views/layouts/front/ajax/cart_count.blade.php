
    <div class="leftSide">
    	@php
    	$cartArray = Session::get('cartItem');
    	$cartCount = 0;
    	@endphp
    	
    	@if(!empty($cartArray) && count($cartArray)>0)
    	@foreach($cartArray as $k => $v)
    	@php
    		$cartCount += $v['quantity'];
    	@endphp
    	@endforeach
    	@endif
        Cart : <span> {{$cartCount?$cartCount:0}} Items </span>
    </div>
    <div class="rightSide">
        <a class="btn btn-success btn-block" href="{{url('checkout')}}">View Cart</a>
    </div>
	