<div class="row">
    <div class="form-group col-md-8">        
        <select name="top_wing_id[]"  class="form-control top_wing_id">
            <option value="">Select Topping Wing</option>
            @foreach($top_wing as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control top_wing_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($top_wing) && count($top_wing)>0 && ($selected_ids== $top_wing[0]->id))?(isset($top_wing[0]->custom_price)?$top_wing[0]->custom_price:$top_wing[0]->price):''}}">
    </div>
</div>
