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