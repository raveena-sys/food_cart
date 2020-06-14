<a class="closeSideMenu" href="javascript:;">X</a>

    <div class="innerContent">

      <form method="post" id="custom_popup" name="custom_popup">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="card listGrid">
          <div class="imgWrapper">
            <div class="img-header">
              @if(isset($product->food_type) && $product->food_type == 'non_veg')
              <div class="food_type nonveg">
              </div>
              @else
              <div class="food_type veg">
              </div>
              @endif
            </div>
            <img class="card-img-top img-responsive" src="{{asset('uploads/products')}}/{{isset($product->image)?$product->image:''}}" alt="">
            <div class="img-footer">
              <!-- <div class="price">
                $ {{isset($product->custom_price)?number_format($product->custom_price, 2):0.00}}
              </div> -->
            </div>
          </div>

          <div class="card-body">
            <h5 class="card-title">{{isset($product->name)?$product->name:''}}</h5>
            <p>{{isset($product->description)?$product->description:''}}</p>
          </div>

        </div>


        @if(!empty($crust_master) && count($crust_master)>0)
        <h4>Select Pizza Crust:</h4>              
        <div class="selectitemSlider">
          @foreach($crust_master as $k2 => $v2)
          <div class="productItem">
            <input type="radio"  name="crust_master" id="crust_{{$v2->id?$v2->id:''}}"  value="{{$v2->id?$v2->id:''}}" data-price='{{$v2->custom_price?number_format($v2->custom_price,2):number_format($v2->price,2)}}' class="crust_master" {{isset($product->pizza_crust) && !empty($product->pizza_crust) && $product->pizza_crust==$v2->id?'checked':''}}>
            <label for="crust_{{$v2->id?$v2->id:''}}">
              <div class="productImg">
                <!-- <img src="{{asset('img/menu_pic_1.png')}}" class="img-responsive" /> -->
              </div>
              <div>{{$v2->name?$v2->name:''}}</div>
              <p class="price">${{$v2->custom_price?number_format($v2->custom_price,2):0.00}}</p>
            </label>
            <div class="check"></div>
          </div>
          @endforeach
        </div>
        @endif
        <input type="hidden" name="product_id" value="{{isset($product->id)?$product->id:''}}" id="product_id">
        @if(!empty($sauce_master) && count($sauce_master)>0)
        <h4>Select Pizza Sauce:</h4>              
        <div class="selectitemSlider">
          @foreach($sauce_master as $k3 => $v3)
          <div class="productItem">
            <input type="radio" name="sauce_master"  id="sauce_{{$v3->id?$v3->id:''}}"  value="{{$v3->id?$v3->id:''}}" data-price='{{$v3->custom_price?number_format($v3->custom_price,2):number_format($v3->price,2)}}' class="sauce_master" {{isset($product->pizza_sauce) && !empty($product->pizza_sauce) && $product->pizza_sauce==$v3->id?'checked':''}}>
            <label for="sauce_{{$v3->id?$v3->id:''}}">
              <div class="productImg">
              </div>
              <div>{{$v3->name?$v3->name:''}}</div>
              <p class="price">${{$v3->custom_price?number_format($v3->custom_price,2):0.00}}</p>
            </label>
            <div class="check"></div>
          </div>
          @endforeach
        </div>
        @endif
        @if(!empty($extra_cheese)  && count($extra_cheese)>0)
        <h2 class="cstmiz_head show_cheese" >Extra Cheese:</h2>
        <table class="table table-bordered show_cheese" >
          <thead>
            <tr>
              <th class="text-center">Size</th>
              <th class="text-center">Cost</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>  
          @foreach($extra_cheese as $key => $val)          
            <tr class="crow" id="cheese_row_{{$val->pizza_size_master}}">
              <td class="text-center">{{$val->pizzaSize->name}}</td>
              <td class="text-center">${{$val->custom_price?number_format($val->custom_price,2):0.00}}</td>
              <td class="text-center"><input type="checkbox" name="extra_cheese" id="extra_cheese" class="extra_cheese" value="{{$val->id}}" data-price="{{$val->custom_price?number_format($val->custom_price,2):number_format($val->price,2)}}"></td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
        

        @if(!empty($dip_master)  && count($dip_master)>0)
        <h2 class="cstmiz_head">Dips Option:</h2>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center">Item</th>
              <th class="text-center">Cost</th>
              <th class="text-center">Quantity</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dip_master as $k4 => $v4)
            <tr>
              <td class="">{{$v4->name?$v4->name:''}}  </td>
              <td class="text-center">${{$v4->custom_price?number_format($v4->custom_price,2):0.00}}</td>
              <td class="text-center">
                  <input type='button' value='-' class='qtyminus' field='dip_master_{{$v4->id?$v4->id:''}}' />
                  <input type='text' name='dip_master[{{$v4->id?$v4->id:''}}]'  id='dip_master_{{$v4->id?$v4->id:''}}'  value='0' min="0" class='qty dip_price' data-price="{{$v4->custom_price?$v4->custom_price:$v4->price}}"/>
                  <input type='button' value='+' class='qtyplus' field='dip_master_{{$v4->id?$v4->id:''}}'  />
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif

        @if(!empty($topping_master) && count($topping_master)>0)
        <h4>Extra Toppings</h4>
        <table class="table table-hover">
          <tbody>
            @foreach($topping_master as $k5 => $v5)
            <tr>
              <td>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="{{$v5->custom_price?$v5->custom_price:$v5->price}}" name="topping_master[{{$v5->id?$v5->id:''}}]" class="topping_master">
                    <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                    {{$v5->name?$v5->name:''}} (${{$v5->custom_price?$v5->custom_price:'$0.00'}} )
                  </label>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </form>
    </div>
    <div class="sideMenu__footer">
      <div class="leftSide">
        <span>1 ITEM</span>
        <span>|</span>
        <span class='span_price'> $0.00<?php //isset($price)?$price:'0.00';?></span>
        <input type="hidden" name="product_custom_price"  class="product_custom_price" value="0.00">
        <input type="hidden" name="product_price"  id="product_price" value="0.00">

        <div class="error topping_error" style="color:red;"></div>
      </div>
      <div class="rightSide">
        <button type="button" data-key={{$sessionkey}} data-product_id="{{isset($product->id)?$product->id:''}}" data-combo_product_id="{{isset($prodid)?$prodid:'0'}}" class="btn btn-success custom_save">Save</button>
      </div>
    </div>
