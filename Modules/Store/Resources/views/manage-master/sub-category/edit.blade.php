<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title font-libre-bold w-100 text-center">Edit Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="icon-clear"></i>
            </button>
        </div>
        <div class="modal-body field-padd" id="edit-form">
            <form id="edit_category_form" action="{{url('store/manage-category/update')}}" method="POST" class="needs-validation" novalidate autocomplete="false">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$detail->id}}">
                <!-- {{$detail}} -->
                <div class="form-group">
                    <label>Category Name</label>
                    @php
                    $companys = \App\Models\Category::query();
                    $companys1 = $companys->where('status', '!=', 'deleted')->get();
                    @endphp
                    <select class="form-control selectpicker1" name="category_id" id="comanyList" title="Select Category" data-size="4">
                        @foreach($companys1 as $compay)
                        <option value="{{$compay->id}}" @if($compay->id == $detail->category_id) selected="selected" @endif >{{$compay->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" type="text" name="name" placeholder="Name" value="{{$detail->name}}" maxlength="250">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="255">{{$detail->description}}</textarea>
                </div>

                <div class="form-group text-center mb-0">
                    <button id="editCategoryBtn" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="updateCategory()" type="button">Update<span id="editCategoryFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>
                </div>
            </form>
            {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditSubCategoryRequest','#edit_category_form') !!}
        </div>
    </div>
</div>


<script>
    function updateCategory() {
        var formData = $("#edit_category_form").serializeArray();
        console.log(formData);
        if ($('#edit_category_form').valid()) {
            $('#editCategoryBtn').prop('disabled', true);
            $('#editCategoryFormLoader').show();

            $.ajax({
                type: "POST",
                url: "{{url('store/manage-sub-category/update')}}",
                data: formData,
                success: function(response) {
                    $('#editCategoryBtn').prop('disabled', false);
                    toastr.clear();
                    Command: toastr['success'](response.message);
                    $("#editSubCategoryModalPopup").modal('hide');
                    $('#editCategoryFormLoader').hide();
                    $('#subcategory-listing').DataTable().ajax.reload();
                }
            });
        }
    };
</script>
