<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{asset('img/invoice_logo_black.png')}}" style="width:300px; max-width:300px;">
                            </td>
                            <td>
                                Invoice #: {{str_pad($orderdata->id,6,"0", STR_PAD_LEFT)}}<br>
                                Time Of Order: {{isset($orderdata->created_at)?date('h:i A', strtotime($orderdata->created_at)).' ON '.date('d M Y', strtotime($orderdata->created_at)):''}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{isset($orderdata->store->name)?$orderdata->store->name:''}}<br>
                                {{isset($orderdata->address)?$orderdata->address:''}}<br>
                                {{isset($orderdata->city)?$orderdata->city:''}}, {{isset($orderdata->state)?$orderdata->state:''}} {{isset($orderdata->zipcode)?$orderdata->zipcode:''}}
                            </td>
                            <td>
                                {{isset($orderdata->name)?$orderdata->name:''}}<br>
                                {{isset($orderdata->mobile_no)?$orderdata->mobile_no:''}}<br>
                                {{isset($orderdata->email)?$orderdata->email:''}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    Pick up or Delivery
                </td>
                <td>
                  Payment Method
                   
                </td>
            </tr>
       
            @if(isset($orderdata->payment_method))
            <tr class="details">
                <td>
                     {{isset($orderdata->order_type)?ucfirst(($orderdata->order_type)):''}}
                </td>
                <td>
                    {{$orderdata->payment_method=='cod'?'Pay with cash at door':($orderdata->payment_method=='card_payment'?'Pay with Credit / Debit at the door':'')}}
                </td>
            </tr>
            @endif
            
            <tr class="heading">
                <td>
                    Item * Quantity
                </td>
               <!--  <td>
                    
                </td> -->
                <td>
                    Price
                </td>
            </tr>
            
            <!-- <tr class="item">
                <td>
                    Website design
                </td>
                
                <td>
                    @if(isset($orderdata->additional_notes))
<div><strong>Additional Notes: {{$orderdata->additional_notes}}</strong></div>
@endif
                </td>
            </tr> -->
            @if($orderdata->cart_item)
    @php
        $ordercart = json_decode($orderdata->cart_item)
    @endphp
    @foreach($ordercart as $k => $v)  
    <tr class="item">
        <td>{{isset($v->name)?ucfirst($v->name):''}} 
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
            ($ {{isset($v->topping_master_price)?$v->topping_master_price:0}})
          </p>
          @endif
          @if(!empty($v->extra_cheese_name))
          <p>
            Cheese:
            Extra cheese (${{isset($v->extra_cheese_name)?$v->extra_cheese_name:0}})
          </p>
          @endif 
        <!-- </td>
        <td> --> * {{isset($v->quantity)?$v->quantity:''}}</td>
        <td>${{isset($v->price)?$v->price:''}}</td> 
    </tr>
    @endforeach
  @endif
            <tr class="heading">
                <td>
                   SubTotal
                </td>
                <!-- <td>
                   
                </td> -->
                <td>
                    ${{isset($orderdata->subtotal)?$orderdata->subtotal:''}}
                </td>
            </tr>
            
            <tr class="details">
                <td>
                   Delivery Charge:
                </td>
             <!--    <td>
                   
                </td> -->
                <td>
                    {{isset($orderdata->delivery_charge)?'$'.$orderdata->delivery_charge:''}}
                </td>
            </tr>
            
            <tr class="heading">
                <td>Total</td>
               <!--  <td>
                   
                </td> -->
                <td>
                   ${{isset($orderdata->total)?$orderdata->total:''}}
                </td>
            </tr>

            @if(isset($orderdata->additional_notes))
            <tr class="details">
                <td>
                    Additional Notes
                </td>  
                <!-- <td>
                   
                </td>  -->             
                <td>
                    {{$orderdata->additional_notes}}
                </td>
            </tr>
            @endif
            @if(isset($orderdata->delivery_ins))
            <tr class="details">
                <td>
                    Delivery Instructions for Driver
                </td>
                <!-- <td>
                   
                </td> -->                
                <td>
                    {{$orderdata->delivery_ins}}
                </td>
            </tr>
            @endif
       
        </table>
    </div>
</body>
</html>
