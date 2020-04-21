@extends('admin::layouts.app')
@section('content')
<?php

$current = 'Order Number #' . str_pad($order->id,6,"0", STR_PAD_LEFT)  ?>
<main class="main-content view-page bar-detail-page inner-page">
    <div class="container-fluid">

        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <!-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Back</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav> -->
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
        </div>
        <div class="bid-wraper">
            <div class="box-shadow common-padding-box bid-user-detail mb-40">
                <div class="common-list-head list-header mb-3 mb-md-4">
                    <div class="list-heading">
                        <h4 class="h-20 font-black">Order</h4>
                    </div>
                </div>
                <div class="common-user-list active">

                    <div class="form-group">
                        <label>Name</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->name)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->email)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mobile No</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->mobile_no)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->address)}}, {{ucfirst($order->city)}}
                                ({{ucfirst($order->state)}})
                                {{ucfirst($order->zipcode)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Order Type</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->order_type)}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Payment Method</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{ucfirst($order->payment_method)}}</strong>
                            </div>

                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                            <th>ITEM</th>
                            <th>QTY</th>
                            <th>PRICE</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if($order->cart_item)
                                @php
                                    $orderdata = json_decode($order->cart_item)
                                @endphp
                                @foreach($orderdata as $k => $v)  
                                <tr>
                                    <td><strong>{{isset($v->name)?ucfirst($v->name):''}}</strong>
                                        {!!isset($v->size_master_name)?'<p>Pizza Size: '.$v->size_master_name.'</p>':''!!}

                                      {!!isset($v->crust_master_name)?'<p>Pizza Crust: '.$v->crust_master_name.'</p>':''!!}

                                      {!!isset($v->sauce_master_name)?'<p>Pizza Sauce: '.$v->sauce_master_name.'</p>':''!!}

                                      @if(!empty($v->dip_master_name))
                                      <p>
                                        Dips:
                                        @foreach($v->dip_master_name as $val)
                                        {{$val}}
                                        @endforeach
                                        (${{isset($v->dip_master_price)?$v->dip_master_price:0}})
                                      </p>
                                      @endif

                                      @if(!empty($v->topping_master_name))
                                      <p>
                                        Toppings:
                                        @foreach($v->topping_master_name as $val1)
                                        {{$val1}}
                                        @endforeach
                                        ($ {{isset($v->topping_master_price)?$v->topping_master_price:0}})
                                      </p>
                                      @endif
                                      @if(!empty($v->extra_cheese_name))
                                      <p>
                                        Cheese:
                                        Extra cheese (${{isset($v->extra_cheese_name)?$v->extra_cheese_name:0}})
                                      </p>
                                      @endif 
                                    </td>
                                    <td>{{isset($v->quantity)?$v->quantity:''}}</td>
                                    <td>${{isset($v->price)?$v->price:''}}</td> 
                                </tr>
                                @endforeach
                            @endif
                            <tr>
                            <td><strong>SubTotal:</strong></td>
                            <td>&nbsp;</td>
                            <td>{{isset($order->subtotal)?$order->subtotal:''}}</td>
                            </tr>
                            <tr>
                            <td><strong>Delivery Charge:</strong></td>
                            <td>&nbsp;</td>
                            <td>{{isset($order->delivery_charge)?$order->delivery_charge:''}}</td>
                            </tr>
                            </tr>
                            <tr>
                            <td><strong>Total:</strong></td>
                            <td>&nbsp;</td>
                            <td>${{isset($order->total)?$order->total:''}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label>Additional Notes</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{$order->additional_notes}}</strong>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label>Delivery Instruction</label>
                        <div class="row">
                            <div class="pr-2 col ">
                                <strong>{{$order->delivery_ins}}</strong>
                            </div>

                        </div>
                    </div>
                </div>  
            </div>

        </div>
        <button class="btn btn-primary" onclick="getPrint({{$order->id}})">Print Order</button>
    </div>
</main>
@endsection
@section('js')

<script>
    function getPrint(id){
        var url = "{{url('admin/orders/print')}}";
        $.ajax({
            type: "get",
            url: url + '/' + id,                    
            success: function(response) {
                if (response.success) {
                    print(response.url);
                } else {
                    Command: toastr['error'](response.message);
                }
            },
            error: function() {
                Command: toastr['success']('Something went wrong.');
            }
        });
    }

    function print(doc) {
        var objFra = document.createElement('iframe');   // Create an IFrame.
        objFra.style.visibility = "hidden";    // Hide the frame.
        objFra.src = doc;                      // Set source.
        document.body.appendChild(objFra);  // Add the frame to the web page.
        objFra.contentWindow.focus();       // Set focus.
        objFra.contentWindow.print();      // Print it.
    }
</script>
