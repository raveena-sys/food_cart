@php
$cartArray = Session::get('cartItem');
$subtotal = 0;
$sidesKey =false;
$trHtml ='';
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

        @if($val->sub_category_id == $value->id )
        @if($val->special_cat == 0 )
          {!!view('front.ajax.simple_product', compact('val'))->render()!!}      

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
                                    <th colspan="4" style="background: #e23434; padding: 12px!important; color: #fff;">
                                        <h2 class="panel-title text-center"> {{isset($val->name)?$val->name:''}}</h2>
                                    </th>
                                </tr>
                                <tr>
                                  <!-- <th>#</th> -->
                                  <th class="text-center">Size</th>
                                  <th class="text-center">$</th>
                                  <th class="text-center">Select</th>
                                </tr>
                                @if(!empty($val->size_master_price))
                                @foreach(json_decode($val->size_master_price) as $k => $v)
                                <tr>
                                    <td>{{$v->size}}</td>
                                    <td>${{$v->price}}</td>
                                    <td>
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
                                            <label>Customize your {{$i}} Pizza</label>
                                            <select name="special_prod_id[]" class="form-control customize_select_option" id="customize_select{{$val->id}}_{{$i}}">
                                              <option value="">Please Select</option>
                                              @foreach($products as $val1)
                                                <option value="{{$val1->id}}">{{$val1->name}}</option>
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
        {!!view('front.ajax.simple_product', compact('val'))->render()!!}
        
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
                                <th colspan="4" style="background: #e23434; padding: 12px!important; color: #fff;">
                                    <h2 class="panel-title text-center"> Menu 
                                    Card</h2>
                                </th>
                            </tr>
                            <tr>
                              <!-- <th>#</th> -->
                              <th class="text-center">Name</th>
                              <th class="text-center">Info</th>
                              <th class="text-center">Price($)</th>
                              <th class="text-center">Select</th>
                            </tr>
                            @foreach($products as $val)
                              @if($val->special_cat==2)
                              <tr>
                                <td>{{$val->name}}</td>
                                <td>{{$val->description}}</td>
                                <td>{{($val->custom_price)?number_format($val->custom_price,2):$val->price}}</td>
                                <td>
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
