<div class="row">
    <div class="form-group col-md-8">        
        <select name="size_id[]"  class="form-control size_id">
            <option value="">Select Pizza Size</option>
            @foreach($size as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">        
        <input class="form-control size_price" name="price[]" max="10000" type="number" placeholder="Price" value="{{(isset($selected_ids) && !empty($size) && count($size)>0 && ($selected_ids== $size[0]->id))?(isset($size[0]->custom_price)?$size[0]->custom_price:$size[0]->price):''}}">
    </div>
</div>
