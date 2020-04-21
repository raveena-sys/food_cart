<div class="row">
    <div class="form-group col-md-8">        
        <select name="top_id[]"  class="form-control top_dip_id">
            <option value="">Select Topping Dips</option>
            @foreach($top as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control top_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($top) && count($top)>0 && ($selected_ids== $top[0]->id))?(isset($top[0]->custom_price)?$top[0]->custom_price:$top[0]->price):''}}">
    </div>
</div>
