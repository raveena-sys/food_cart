<div class="row">
    <div class="form-group col-md-8">        
        <select name="crust_id[]"  class="form-control crust_id">
            <option value="">Select Pizza Crust</option>
            @foreach($crusts as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control crust_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($crusts) && count($crusts)>0 && ($selected_ids== $crusts[0]->id))?(isset($crusts[0]->custom_price)?$crusts[0]->custom_price:$crusts[0]->price):''}}">
    </div>
</div>
