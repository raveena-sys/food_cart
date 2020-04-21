<div class="col-md-12">
    <div class="form-group">        
        <select name="category_id[]"  class="form-control category_id">
            <option value="">Select Category</option>
            @foreach($category as $key => $val)
            <option value="{{isset($val->id)?$val->id:''}}" data-price="{{isset($val->price)?$val->price:''}}" {{isset($selected_ids)&& ($selected_ids== $val->id)?'selected':''}}>{{isset($val->name)?$val->name:''}}</option>
            @endforeach
        </select>
    </div>
</div>
