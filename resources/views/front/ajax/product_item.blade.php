@php
$cartArray = Session::get('cartItem');
$subtotal = 0;
@endphp

@if(!empty($products) && count($products)>0)
@if(!empty($subcategory))

@foreach($subcategory as $value)
<div class="row">
    <div class="col-md-12" id="{{str_replace(' ', '_', $value->name)}}">
        <div class="menu__subcategory__inner">
            <span class="subcategory__name">{{$value->name}}</span>
        </div>
    </div>
</div>

<div class="row">
    @foreach($products as $val)
    @php
    $showCart = false;
    //$quantity = 1;
    @endphp

    @if($val->sub_category_id == $value->id)
    <div class="col-lg-4 col-sm-6 col-xs-12" id="prod_{{$val->sub_category_id}}">
        <div class="card listGrid">
            <div class="imgWrapper">
                <div class="img-header">
                    @if(isset($val->food_type) && $val->food_type == 'non_veg')
                    <div class="food_type nonveg">
                    </div>
                    @else
                    <div class="food_type veg">
                    </div>
                    @endif
                    <!-- <a class="favBtn" href="javascript:;">
              <img src="{{asset('img/favorite.svg')}}" />                 
            </a> -->
                </div>
                <div class="img-div-wrap">
                    <img class="card-img-top img-responsive"
                        src="{{asset('uploads/products')}}/{{isset($val->image)?$val->image:''}}" alt="">
                </div>
                <div class="img-footer">
                    <div class="price">
                        ${{isset($val->custom_price)?round($val->custom_price, 2):(isset($val->price)?round($val->price, 2):'')}}
                    </div>
                    @if(isset($val->topping_from))
                    @if($val->topping_from == 'topping_pizza' || $val->topping_from == 'topping_dips' ||
                    $val->topping_from == 'topping_donair_shawarma_mediterranean' || $val->topping_from ==
                    'topping_wing_flavour')
                    <div class="price">
                        <a class="customise_item btn btn-default customise"
                            data-id="{{isset($val->id)?$val->id:0}}">Customise</a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            <div class="card-body">
                <h5 class="card-title">{{isset($val->name)?$val->name:''}}</h5>
                <?php 
            if(isset($val->description)){
              $string = strip_tags($val->description);
              if (strlen($string) > 50) {

                  // truncate string
                  $stringCut = substr($string, 0, 50);
                  $endPoint = strrpos($stringCut, ' ');

                  //if the string doesn't contain any space then it will cut without word basis.
                  $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                  $string .= "... <a href='javascript:void(0)' class='read_less' id=".$val->id.">Read More</a>";
              }
            }
            
            ?>
                {!!isset($val->description)?'<p class="content_'.$val->id.'" style="display: none;">
                    '.$val->description.' <a href="javascript:void(0)" class="read_less" id='.$val->id.'>Read less</a>
                </p>':''!!}
                <p class="short_content_{{$val->id}}">{!!isset($string)?$string:''!!} </p>
            </div>
            <div class="card-footer">
                @if(!empty($cartArray) && count($cartArray)>0)
                @php
                $quantity = 0;
                @endphp
                @foreach($cartArray as $k => $v)

                @if($v['product_id'] == $val->id)
                @php
                $showCart = true;
                $quantity = $quantity+$v['quantity'];
                $price = $v['price'];
                @endphp
                @endif
                @endforeach
                @endif
                @if($showCart)
                <div class="countWrap">
                    <button type="button" id="sub" data-product_id="{{isset($val->id)?$val->id:''}}"
                        class="sub sub_item" data-price="{{isset( $price)? $price:0}}">-</button>
                    <input class="count" type="text" id="item_count_{{isset($val->id)?$val->id:''}}"
                        value="{{isset($quantity)?$quantity:1}}" min="1" max="100">
                <?php
                  if(isset($val->price)){
                    $price = $val->price;
                  }else if(isset($val->custom_price)){
                    $price = $val->custom_price;
                  }else{
                    $price = 0;
                  }
                  ?>
                    <button type="button" id="add" class="add add_to_cart"
                        data-product_id="{{isset($val->id)?$val->id:''}}" data-price="{{$price}}">+
                    </button>
                </div>
                @else
                <div class="leftSide">
                    <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}"
                        data-price="{{isset($val->custom_price)?round($val->custom_price, 2):(isset($val->price)?round($val->price, 2):'')}}"
                        class="btn btn-success add_to_cart">
                        ADD TO CART
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>
@endforeach
@endif
@else

<div class="col-lg-12">
    <div class="totalPrice">
        <div class="" style="color:#337ab7; text-align: center;">
            <h3>Product is not available for now</h3>
        </div>
    </div>
</div>
@endif

<style>
.img-responsive {
    width: 100%;
}
</style>