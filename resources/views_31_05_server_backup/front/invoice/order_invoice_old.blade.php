<p><strong>Store Name: {{isset($orderdata->store->name)?$orderdata->store->name:''}}</strong></div>


<div><strong>Customer name: {{isset($orderdata->name)?$orderdata->name:''}}</strong></div>


<div><strong>Address: {{isset($orderdata->address)?$orderdata->address:''}}, {{isset($orderdata->city)?$orderdata->city:''}}  ({{isset($orderdata->state)?$orderdata->state:''}}) {{isset($orderdata->zipcode)?$orderdata->zipcode:''}}</strong></div>


<div><strong>Phone number: {{isset($orderdata->mobile_no)?$orderdata->mobile_no:''}}</strong></div>

<div><strong>Email address: {{isset($orderdata->email)?$orderdata->email:''}}</strong></div>

<div><strong>Pick up or Delivery: {{isset($orderdata->order_type)?ucfirst(($orderdata->order_type)):''}}</strong></div>


@if(isset($orderdata->payment_method))
<div><strong>Method of payment: {{$orderdata->payment_method=='cod'?'Pay with cash at door':($orderdata->payment_method=='card_payment'?'Pay with Credit / Debit at the door':'')}}</strong></div>
@endif
<div><strong>Time Of Order: {{isset($orderdata->created_at)?date('h:i A', strtotime($orderdata->created_at)).' ON '.date('d M Y', strtotime($orderdata->created_at)):''}}</strong></div>
<p></p>
@if(isset($orderdata->additional_notes))
<div><strong>Additional Notes: {{$orderdata->additional_notes}}</strong></div>
@endif
<p></p>
<table style="
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;">
  <tr style="padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  color: black;
  padding: 8px;">
    <th style="min-width:40%;">Product Name</th>
    <th style="min-width:20%;">Quantity</th>
    <th style="min-width:20%;">Price</th>
    <th style="min-width:20%;">SubTotal</th>
  </tr>
  @if($orderdata->cart_item)
    @php
        $ordercart = json_decode($orderdata->cart_item)
    @endphp
    @foreach($ordercart as $k => $v)  
    <tr>
        <td><strong>{{isset($v->name)?ucfirst($v->name):''}}</strong> 
            {!!isset($v->size_master_name)?'<p>Pizza Size: '.$v->size_master_name.'</p>':''!!}

          {!!isset($v->crust_master_name)?'<p>Pizza Crust: '.$v->crust_master_name.'</p>':''!!}

          {!!isset($v->sauce_master_name)?'<p>Pizza Sauce: '.$v->sauce_master_name.'</p>':''!!}

          @if(!empty($v->dip_master_name))
          <p>
            Dips:
            @foreach($v->dip_master_name as $val)
            {{$val}},
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
            ($ {{isset($v->topping_master_price)?number_format($v->topping_master_price.2):0}})
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
        <td>${{isset($v->price)?number_format($v->price,2):''}}</td> 
    </tr>
    @endforeach
  @endif
  <tr style="padding: 8px;">
    <td>SubTotal:</td>
    <td>&nbsp;</td>
    <td>${{isset($orderdata->subtotal)?number_format($orderdata->subtotal,2):''}}</td>
  </tr>
  <tr style="padding: 8px;">
    <td>Delivery Charge:</td>
    <td>&nbsp;</td>
    <td>{{isset($orderdata->delivery_charge)?'$'.number_format($orderdata->delivery_charge,2):''}}</td>
  </tr>
  <tr style="padding: 8px;">
    <td></td>
    <td></td>
    <td></td>
    <td>${{isset($orderdata->total)?number_format($orderdata->total,2):''}}</td>
  </tr>
  
</table>
@if(isset($orderdata->delivery_ins))
<div><strong>Delivery Instructions for Driver</strong></div>
<p>{{ucfirst($orderdata->delivery_ins)}}</p>
@endif