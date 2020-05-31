@extends('layouts.app')
@section('content')


  <div class="container">
    <div class="checkoutContent">
       <input type="hidden" value="{{Request::segment(1)}}" id="url_param"> 
      <div class="row cart_item">
        {!!view('front.ajax.checkout_item')->render()!!}
      </div>
    </div>
  </div>

  <!--Modal Customize-->
  <!-- <div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h3 class="modal-title" id="lineModalLabel">MEATZZA PIZZA</h3>
        </div>
        <div class="modal-body">
          <div class="col-lg-3">
            <h4>Pizza Size:</h4>
            <ul>
              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text"> 10" Small</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">12" Medium</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">14" Large</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">16" Extra Large</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">18" Extra Large</span>
                  </label>
                </div>
              </li><br>

              <h4>Pizza Crust:</h4>
              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">Classic Hand Tossed</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">Thick Crust</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">Thin Crust</span>
                  </label>
                </div>
              </li><br>

              <h4>Pizza Sauce:</h4>
              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">Marinara</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">BBQ</span>
                  </label>
                </div>
              </li>

              <li>
                <div class="form-check">
                  <label>
                    <input type="radio" name="radio" checked> <span class="label-text">Tahini</span>
                  </label>
                </div>
              </li>


            </ul>
          </div>
          <div class="col-lg-9">
            <h2 class="cstmiz_head">Extra Cheese Option:</h2>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Size</th>
                  <th>Cost</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>14"</td>
                  <td>2.5</td>
                  <td>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" value="">
                        <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>

                      </label>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <h2 class="cstmiz_head">Dips Option:</h2>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Cost</th>
                  <th>Quantity</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Cheddar Chipotle</td>
                  <td>0.75</td>
                  <td>
                    <form id='myform' method='POST' action='#'>
                      <input type='button' value='-' class='qtyminus' field='quantity' />
                      <input type='text' name='quantity' value='0' class='qty' />
                      <input type='button' value='+' class='qtyplus' field='quantity' />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>Creamy Garlic</td>
                  <td>0.75</td>
                  <td>
                    <form id='myform1' method='POST' action='#'>
                      <input type='button' value='-' class='qtyminus' field='quantity' />
                      <input type='text' name='quantity' value='0' class='qty' />
                      <input type='button' value='+' class='qtyplus' field='quantity' />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>Ranch</td>
                  <td>0.75</td>
                  <td>
                    <form id='myform2' method='POST' action='#'>
                      <input type='button' value='-' class='qtyminus' field='quantity' />
                      <input type='text' name='quantity' value='0' class='qty' />
                      <input type='button' value='+' class='qtyplus' field='quantity' />
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>Marinara</td>
                  <td>0.75</td>
                  <td>
                    <form id='myform3' method='POST' action='#'>
                      <input type='button' value='-' class='qtyminus' field='quantity' />
                      <input type='text' name='quantity' value='0' class='qty' />
                      <input type='button' value='+' class='qtyplus' field='quantity' />
                    </form>

                  </td>
                </tr>
              </tbody>
            </table>

            <button class="btn_xtr_topping" data-toggle="modal" data-target="#extra_topping">Extra Topping Options</button>
            <div class="prdct_prc">
              <label>Price:</label>
              <label>$18.99</label>
            </div>
            <button class="btn_add_cart">Add to Cart</button>

          </div>
          <div class="clearfix"></div>


        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
  </div> -->
  <!--Modal Customize-->

  <!--Modal Extra Topping-->
  <!-- <div class="modal fade" id="extra_topping" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h3 class="modal-title" id="lineModalLabel">Extra Toppings Options</h3>
        </div>
        <div class="modal-body">

          <table class="table table-hover">
            <tbody>
              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Pepperoni
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Salami
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Ham
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Bacon
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Ground Beef
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Italian Sausage
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Donair Meat
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Tandoori Chicken
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      BBQ Chicken
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Spicy Chicken
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Regular Chicken
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Shrimp
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Mushrooms
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Onions
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Green Peppers
                    </label>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      Cooked Tomatoes
                    </label>
                  </div>
                </td>
              </tr>

            </tbody>
          </table>
          <button class="btn_save">Save</button>
          <div class="clearfix"></div>


        </div>

      </div>
    </div>
  </div> -->
  <!--Modal Extra Topping-->

  <div class="modal" id="confirmdeleteModal" tabindex="-1" role="dialog" backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to remove this item?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary remove_item">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection