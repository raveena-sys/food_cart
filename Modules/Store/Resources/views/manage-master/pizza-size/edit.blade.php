<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title font-libre-bold w-100 text-center">Edit Pizza Sauce</h4>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="icon-clear"></i>
            </button>
        </div>
        <div class="modal-body field-padd" id="edit-form">
            @if(($detail->store_id == Auth::user()->store_id))
            @php 
            $inputdisable = '';
            $inputreadonly = '';
            @endphp  
            @else
            @php 
            $inputdisable = 'disabled';
            $inputreadonly = 'readonly';
            @endphp                     
            <h5 class="mb-0">Only Price can be edit.</h5>
            @endif
            <form id="edit_category_form" action="{{url('store/manage-pizza-size/update')}}" method="POST" class="needs-validation" novalidate autocomplete="false">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$detail->id}}">
                <input type="hidden" name="store_id" value="{{$detail->store_id?$detail->store_id:''}}">
                <div class="form-group">
                    <label>Size Type</label>
                    @php
                    $sizequery = \App\Models\SizeMaster::query();
                    $getsize = $sizequery->where('status', '!=', 'deleted')->get();
                    @endphp
                    <select class="form-control selectpicker1" name="size_master_id" id="comanyList" title="Select Size" data-size="4" {{$inputdisable}}>
                        @foreach($getsize as $compay)
                        <option value="{{$compay->id}}" @if($compay->id == $detail->size_master_id) selected="selected" @endif >{{$compay->name}}</option>
                        @endforeach
                    </select>
                    @if($inputdisable)
                        <input type="hidden" name="size_master_id"value="{{$detail->size_master_id}}">
                    @endif
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Name" value="{{$detail->name}}" maxlength="250" {{$inputreadonly}}>
                </div>
                <div class="form-group has-error">
                    <label>Price</label>
                    <input class="form-control" name="price" type="text" placeholder="Price" maxlength="250"  value="{{$detail->custom_price}}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="255" {{$inputreadonly}}>{{$detail->description}}</textarea>
                </div>

                <div class="form-group text-center mb-0">
                    <button id="editCategoryBtn" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="updateCategory()" type="button">Update<span id="editCategoryFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>
                </div>
            </form>
            {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditPizzaSizeRequest','#edit_category_form') !!}
        </div>
    </div>
</div>


<script>
    function updateCategory() {
        var formData = $("#edit_category_form").serializeArray();
        if ($('#edit_category_form').valid()) {
            $('#editCategoryBtn').prop('disabled', true);
            $('#editCategoryFormLoader').show();

            $.ajax({
                type: "POST",
                url: "{{url('store/manage-pizza-size/update')}}",
                data: formData,
                success: function(response) {
                    $('#editCategoryBtn').prop('disabled', false);
                    toastr.clear();
                    Command: toastr['success'](response.message);
                    $("#editCategoryModalPopup").modal('hide');
                    $('#editCategoryFormLoader').hide();
                    $('#category-listing').DataTable().ajax.reload();
                }
            });
        }
    };
</script>
