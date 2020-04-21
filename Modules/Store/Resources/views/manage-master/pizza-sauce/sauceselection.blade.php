<div class="row">
    <div class="form-group col-md-8">        
        <select name="sauce_id[]"  class="form-control sauce_id">
            <option value="">Select Pizza Sauce</option>
            @foreach($sauces as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control sauce_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($sauces) && count($sauces)>0 && ($selected_ids== $sauces[0]->id))?(isset($sauces[0]->custom_price)?$sauces[0]->custom_price:$sauces[0]->price):''}}">
    </div>
</div>
