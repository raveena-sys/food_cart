<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title font-libre-bold w-100 text-center">Edit Category</h4>
           
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
            <form id="edit_category_form" action="{{url('store/manage-pizza-crust/update')}}" method="POST" class="needs-validation" novalidate autocomplete="false">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$detail->id}}">
                <input type="hidden" name="store_id" value="{{$detail->store_id?$detail->store_id:''}}">
                <!-- {{$detail}} -->
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Name" value="{{$detail->name}}" maxlength="250" {{ $inputreadonly}}>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input class="form-control" name="price" type="text" placeholder="Price" maxlength="250" value="{{isset($detail->custom_price)?round($detail->custom_price,2):round($detail->price,2)}}" >
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="255" {{ $inputreadonly}}>{{$detail->description}}</textarea>
                </div>

                <div class="form-group text-center mb-0">
                    <button id="editCategoryBtn" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="updateCategory()" type="button">Update<span id="editCategoryFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>
                </div>
            </form>
            {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditPizzaCrustRequest','#edit_category_form') !!}
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
                url: "{{url('store/manage-pizza-crust/update')}}",
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
