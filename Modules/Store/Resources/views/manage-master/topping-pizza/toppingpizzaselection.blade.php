<div class="row">
    <div class="form-group col-md-8">        
        <select name="top_pizza_id[]"  class="form-control top_pizza_id">
            <option value="">Select Topping Pizza</option>
            @foreach($top_pizza as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control top_pizza_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($top_pizza) && count($top_pizza)>0 && ($selected_ids== $top_pizza[0]->id))?(isset($top_pizza[0]->custom_price)?$top_pizza[0]->custom_price:$top_pizza[0]->price):''}}">
    </div>
</div>
