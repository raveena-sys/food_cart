<div class="row">
    <div class="form-group col-md-8">        
        <select name="cheese_id[]"  class="form-control cheese_id">
            <option value="">Select Pizza Cheese</option>
            @foreach($cheese as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control cheese_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($cheese) && count($cheese)>0 &&  ($selected_ids== $cheese[0]->id))?(isset($cheese[0]->custom_price)?$cheese[0]->custom_price:$cheese[0]->price):''}}">
    </div>
</div>
