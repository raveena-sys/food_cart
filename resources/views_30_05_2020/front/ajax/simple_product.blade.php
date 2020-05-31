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