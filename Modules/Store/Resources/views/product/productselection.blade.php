<div class="row">
    <div class="form-group col-md-8">        
        <select name="product_id[]"  class="form-control product_id">
            <option value="">Select Product</option>
            @foreach($products as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control product_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && ($selected_ids== $products[0]->id))?(isset($products[0]->custom_price)?$products[0]->custom_price:$products[0]->price):''}}">
    </div>
</div>
