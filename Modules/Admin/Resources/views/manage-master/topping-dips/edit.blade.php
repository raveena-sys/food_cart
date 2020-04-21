<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title font-libre-bold w-100 text-center">Edit Pizza Sauce</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="icon-clear"></i>
            </button>
        </div>
        <div class="modal-body field-padd" id="edit-form">
            <form id="edit_category_form" action="{{url('admin/manage-pizza-size/update')}}" method="POST" class="needs-validation" novalidate autocomplete="false">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$detail->id}}">
                <input class="form-control" name="storeid" type="hidden" value="{{$detail->store_id}}">
                <div class="form-group">
                        <label>Food Type</label>

                        <select class="form-control selectpicker1" name="food_type" id="comanyList" title="Select Food Type" data-size="4">

                            <option value="veg"  @if("veg" == $detail->food_type) selected="selected" @endif>Veg</option>

                            <option value="non_veg"  @if("non_veg" == $detail->food_type) selected="selected" @endif>Non veg</option>



                        </select>
                    </div>



                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Name" value="{{$detail->name}}" maxlength="250">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input class="form-control" type="text" name="price" placeholder="Price" value="{{$detail->price}}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="255">{{$detail->description}}</textarea>
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
                url: "{{url('admin/manage-topping-dips/update')}}",
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
