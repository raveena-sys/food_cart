@php
$cartArray = Session::get('cartItem');
$subtotal = 0;
$sidesKey =false;
$trHtml ='';
@endphp

@if(!empty($products) && count($products)>0)
  @if(!empty($subcategory))

    @foreach($subcategory as $value)
    <div class="row" id="{{str_replace(' ', '_', $value->name)}}">
        <div class="col-md-12 sub_cat_list">
            <div class="menu__subcategory__inner">
                <span class="subcategory__name">{{$value->name}}</span>
            </div>
        </div>

 

        @foreach($products as $val)
        @php
        $showCart = false;
        //$quantity = 1;
       

        @endphp

        @if($val->sub_category_id == $value->id )
        @if($val->special_cat == 0 )
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
                  </div>
                  <div class="img-div-wrap">
                    <img class="card-img-top img-responsive"
                          src="{{asset('uploads/products')}}/{{isset($val->image)?$val->image:''}}" alt="">
                  </div>
                  <div class="img-footer">
                    <div class="price ">
                      ${{isset($val->custom_price)?number_format($val->custom_price, 2):(isset($val->price)?number_format($val->price, 2):'0.00')}}
                    </div>
                    @if(isset($val->topping_from))
                      @if($val->topping_from == 'topping_pizza' || $val->topping_from == 'topping_dips' ||
                      $val->topping_from == 'topping_donair_shawarma_mediterranean' || $val->topping_from ==
                      'topping_wing_flavour' || $val->topping_from ==
                      'topping_tops')

                        @if($val->add_customisation ==2)
                        <div class="">
                          <a class="customise_item btn btn-default customise" data-id="{{isset($val->id)?$val->id:0}}"> Customize
                          </a>
                        </div>
                        @endif
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
                    
                      @if($val->add_customisation == 0 || $val->add_customisation == 2)
                        <div class="leftSide">
                          <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}"
                              data-price="{{isset($val->custom_price)?number_format($val->custom_price, 2):(isset($val->price)?number_format($val->price, 2):'')}}"
                              class="btn btn-success add_to_cart">
                              ADD TO CART
                          </a>
                        </div>
                      @endif
                      @if($val->add_customisation == 1)
                        <div class="leftSide">
                          <a href="javascript:void(0);" 
                              data-id="{{isset($val->id)?$val->id:0}}"
                              class="btn btn-success customise_item">
                              Customize
                          </a>
                        </div>
                      @endif
                  </div>
            </div>
        </div>

        @elseif($val->special_cat==1)
        <div class="special-type">
            <div class="col-lg-6 col-sm-6 col-xs-12">
            <div class="img-header">
                        @if(isset($val->food_type) && $val->food_type == 'non_veg')
                        <div class="food_type nonveg">
                        </div>
                        @else
                        <div class="food_type veg">
                        </div>
                        @endif
                    </div>
            <div class="img-wrap">
                <img class="card-img-top img-responsive"
                    src="{{asset('uploads/products')}}/{{isset($val->image)?$val->image:''}}" alt="">
            </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12" id="prod_{{$val->sub_category_id}}">
                <div class="card listGrid">
                    <div class="imgWrapper">
                        
                        <table class="table table-bordered  table-hover menutbl">
                            <tbody>
                                <tr>
                                    <th colspan="4" style="background: #333; padding: 12px!important; color: #fff;">
                                        <h2 class="panel-title text-center"> {{isset($val->name)?$val->name:''}}</h2>
                                    </th>
                                </tr>
                                <tr>
                                  <!-- <th>#</th> -->
                                  <th>Size</th>
                                  <th class="text-center">$</th>
                                  <th class="text-center">Select</th>
                                </tr>
                                @if(!empty($val->size_master_price))
                                @foreach(json_decode($val->size_master_price) as $k => $v)
                                <tr>
                                    <td >{{$v->size}}</td>
                                    <td class="text-center">${{$v->price}}</td>
                                    <td class="text-center">
                                        <input type="radio" value="{{$v->id}}" name="special_prod_add{{$val->id}}" class="special_prod_add{{$val->id}}" data-price="{{$v->price}}" >
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                       
                            </tbody>
                        </table>
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
                        
                          @if($val->add_customisation == 0 || $val->add_customisation == 2)
                            <span id="combo_error{{$val->id}}" class="error"></span>
                            <div class="leftSide">
                                <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}" data-product_qty="{{isset($val->quantity)?$val->quantity:''}}" class="btn btn-success order_now">
                                    ADD TO CART
                                </a>
                            </div>
                          @endif
                          @if($val->add_customisation == 1)
                            <div class="leftSide">
                              <a href="javascript:void(0);" 
                                  data-id="{{isset($val->id)?$val->id:0}}"
                                  class="btn btn-success customise_item">
                                  Customize
                              </a>
                            </div>
                          @endif

                    </div>    
                      
                        <div class="modal fade" data-keyboard="true" tabindex="-1" role="dialog" id="add_special_product{{isset($val->id)?$val->id:''}}" aria-hidden="true" data-backdrop="static">
                          <div class="modal-dialog modal-dialog-centered modal-md">
                            <div class="modal-content">
                              <div class="modal-content-inner">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal"><i class="fa fa-close"></i></button>
                                  <h4 class="modal-title">Select Your Pizza</h4>
                                </div>

                                <div class="modal-body text-center">
                                  <form method="post" id="select_combo_pizza{{isset($val->id)?$val->id:''}}">
                                    <div class="row">
                                      @for($i=1; $i<=$val->quantity; $i++)
                                      <div class="{{$val->quantity>2?'col-sm-4':'col-sm-6'}}">
                                          <div class="text-center">
                                            <label>
                                            @php
                                            $numberFormatter = new NumberFormatter('en_US', NumberFormatter::ORDINAL);
                                            @endphp

                                            Customize your {{$numberFormatter->format($i)}} Pizza</label>
                                            <select name="special_prod_id[]" class="form-control customize_select_option" id="customize_select{{$val->id}}_{{$i}}" data-pizza="{{$numberFormatter->format($i)}}" data-key="{{$i}}">
                                              <option value="">Please Select</option>
                                             
                                              @foreach($products as $val1)
                                             
                                                @if(in_array($val1->id, explode(',',$val->custom_product)))
                                                <option value="{{$val1->id}}" data-required="{{$val1->customise_required}}" >{{$val1->name}}</option>
                                                @endif
                                              @endforeach
                                            </select>
                                          </div>
                                          <span class="error" id="customize_select_error{{$val->id}}_{{$i}}"></span>
                                          <div class="card-footer">
                                              
                                              <div class="leftSide">
                                                  <a href="javascript:void(0);" 
                                                      data-id="{{isset($val->id)?$val->id:0}}" data-key="{{$i}}"
                                                      class="btn btn-success customise_special_prod" >
                                                      Customize
                                                  </a>
                                              </div>
                                          </div>
                                      </div>
                                      @endfor
                                    </div>

                                    <div class="">
                                      <span class="error" id="customize_addcart_error{{$val->id}}"></span>
                                      <div class="leftSide">
                                          <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}"
                                              class="btn btn-success add_combo">
                                              ADD TO CART
                                          </a>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
               
                    
                </div>
            </div>
        </div>      
        @elseif($val->special_cat==2)
          @php
          $sidesKey = true;
          @endphp
        @elseif($val->special_cat==3)
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
                  </div>
                  <div class="img-div-wrap">
                    <img class="card-img-top img-responsive"
                          src="{{asset('uploads/products')}}/{{isset($val->image)?$val->image:''}}" alt="">
                  </div>
                  <div class="img-footer">
                    <div class="price ">
                      ${{isset($val->custom_price)?number_format($val->custom_price, 2):(isset($val->price)?number_format($val->price, 2):'0.00')}}
                    </div>
                    @if(isset($val->topping_from))
                      @if($val->topping_from == 'topping_pizza' || $val->topping_from == 'topping_dips' ||
                      $val->topping_from == 'topping_donair_shawarma_mediterranean' || $val->topping_from ==
                      'topping_wing_flavour' || $val->topping_from ==
                      'topping_tops')

                        @if($val->add_customisation ==2)
                        <div class="">
                          <a class="customise_item btn btn-default customise" data-id="{{isset($val->id)?$val->id:0}}"> Customize
                          </a>
                        </div>
                        @endif
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
                    
                      @if($val->add_customisation == 0 || $val->add_customisation == 2)
                        <div class="leftSide">
                          <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}"
                              data-price="{{isset($val->custom_price)?number_format($val->custom_price, 2):(isset($val->price)?number_format($val->price, 2):'')}}"
                              class="btn btn-success add_to_cart">
                              ADD TO CART
                          </a>
                        </div>
                      @endif
                      @if($val->add_customisation == 1)
                        <div class="leftSide">
                          <a href="javascript:void(0);" 
                              data-id="{{isset($val->id)?$val->id:0}}"
                              class="btn btn-success customise_item">
                              Customize
                          </a>
                        </div>
                      @endif
                  </div>
            </div>
        </div>
        @endif
        @endif 
        @endforeach

    </div>
    @endforeach

    @if($sidesKey)
    <div class="row">
      <div class="special-type">
        <div class="col-lg-6 col-sm-6 col-xs-12">
        <div class="img-header">
          @if(isset($val->food_type) && $val->food_type == 'non_veg')
          <div class="food_type nonveg">
          </div>
          @else
          <div class="food_type veg">
          </div>
          @endif
        </div>
        <div class="img-wrap">
          <img class="card-img-top img-responsive"
                src="{{asset('uploads/products')}}/sides.jpg" alt="">
        </div>
        </div>
        <div class="col-lg-6 col-sm-6 col-xs-12" id="prod_{{$val->sub_category_id}}">
            <div class="card listGrid">
                <div class="imgWrapper">
                    <table class="table table-bordered  table-hover menutbl">
                        <tbody>
                            <tr>
                                <th colspan="4" style="background: #333; padding: 12px!important; color: #fff;">
                                    <h2 class="panel-title text-center"> Menu 
                                    Card</h2>
                                </th>
                            </tr>
                            <tr>
                              <!-- <th>#</th> -->
                              <th >Name</th>
                              <th >Info</th>
                              <th class="text-center">Price($)</th>
                              <th class="text-center">Select</th>
                            </tr>
                            @foreach($products as $val)
                              @if($val->special_cat==2)
                              <tr>
                                <td>{{$val->name}}</td>
                                <td>{{$val->description}}</td>
                                <td class="text-center">{{($val->custom_price)?number_format($val->custom_price,2):$val->price}}</td>
                                <td class="text-center">
                                    <input type='checkbox' value="{{$val->id}}" name="sides_prod_add{{$val->id}}" class="sides_prod_add" data-price="{{$val->price}}" >
                                </td>
                              </tr>
                              @endif
                            @endforeach
              
                        </tbody>
                    </table>
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
                   
                      @if($val->add_customisation == 0 || $val->add_customisation == 2)
                        <span id="sides_error" class="error"></span>
                        <div class="leftSide">
                            <a href="javascript:void(0);" data-product_id="{{isset($val->id)?$val->id:''}}" data-product_qty="{{isset($val->quantity)?$val->quantity:''}}" class="btn btn-success order_multiple">
                                ADD TO CART
                            </a>
                        </div>
                      @if($val->add_customisation == 1)
                        <div class="leftSide">
                          <a href="javascript:void(0);" 
                              data-id="{{isset($val->id)?$val->id:0}}"
                              class="btn btn-success customise_item">
                              Customize
                          </a>
                        </div>
                      @endif
                    @endif

                  </div>    
                
                </div>
                
            </div>
        </div>
      </div>
    </div>
    @endif
    
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
.special-type {
    background-color: #fff;
    padding-top: 27px;
    margin-bottom: 28px;
    border: 1px solid #9e9b9b;
}
.listGrid .img-footer .price {
    color: #fff;
    font-weight: 500;
    font-size: 14px;
    border-radius: 5px;
    padding: 5px;
    background-color: #1517198c;
}
</style>
