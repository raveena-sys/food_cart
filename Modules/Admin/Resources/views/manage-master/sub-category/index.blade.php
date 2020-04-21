@extends('admin::layouts.app')
@section('content')
<?php $current = 'SubCategory'; ?>
<main class="main-content">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row filter-page-btn">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('admin')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">SubCategory</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
            <div class="right-side">
                <a aria-controls="filterSection" aria-expanded="false" class="btn btn-light ripple-effefct d-lg-none" data-toggle="collapse" href="#filterSection" id="filterbtn" role="button"><span class="ripple rippleEffect"></span><i class="icon-filter1"></i></a>
                <a href="javascript:void(0);" onclick="showAddSubCategory()" class="btn btn-danger ripple-effect text-uppercase">Add Sub Category</a>
            </div>
        </div>
        <!-- Filter Start -->
        <div class="filter_section collapse  d-lg-block" id="filterSection">
            <h5 class="font-md label">Search By</h5>
            <form id="search-form">
                <div class="form_field">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <select class="form-control selectpicker" id="status" title="Status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        @php
                        $categories = \App\Models\Category::query();
                        $categories = $categories->where('status', '!=', 'deleted')->get();
                        @endphp
                        <select class="form-control selectpicker" name="category_id" id="category_id" title="Select Category" data-size="4">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}} ({{!empty($category->store->name)?$category->store->name:'Admin'}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="btn_clumn mb-3">
                        <button type="submit" class="btn btn-secondary ripple-effect mr-1" data-toggle="tooltip" data-placement="top" title="Search"><i class="icon-search"></i></button>
                        <button type="reseat" id="clear-search" class="btn btn-outline-danger ripple-effect" data-toggle="tooltip" data-placement="top" title="Reset">
                            <i class="icon-refresh"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- filter section end -->
        <!-- table listing start -->
        <div class="common-table">
            <div class="table-responsive">
                <table class="table" id="subcategory-listing">
                    <thead>
                        <tr>
                            <th class="w_80"><span class="sorting">S.No.</span></th>
                            <th><span class="sorting">Category Name</span></th>
                            <th><span class="sorting">Subcategory</span></th>
                            <th> Description </th>
                            <th><span class="sorting">Status</span></th>
                            <th class="w_130"><span>Action</span></th>
                        </tr>
                    </thead>
                    <tbody id="listing">


                    </tbody>
                </table>
            </div>
            <!-- pagination start -->

            <!-- pagination end -->
        </div>
        <!-- table listing end -->

    </div>
</main>
<!-- add subadmin -->
<div class="modal modal-effect" id="addSubCategoryModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-libre-bold w-100 text-center">Add SubCategory</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-clear"></i>
                </button>
            </div>
            <div class="modal-body field-padd">
                <form id="add_SubCategory_form" method="POST" class="needs-validation" novalidate autocomplete="false" action="{{URL::To('admin/manage-sub-category/add')}}">
                    {{csrf_field()}}


                    <div class="form-group">
                        <label>Category Name</label>
                        @php
                        $companys = \App\Models\Category::query();
                        $categories = $companys->where('status', 'active')->get();
                        @endphp
                        <select class="form-control selectpicker" name="category_id" id="comanyList" title="Select Category" data-size="4">
                            @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}} ({{isset($category->store->name)?$category->store->name:'Admin'}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" type="text" placeholder="Name" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="250"></textarea>
                    </div>

                    <div class="form-group text-center mb-0">
                        <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="addSubCategory()" type="button">ADD<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                        <!--  -->
                    </div>
                </form>
                {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddSubCategoryRequest','#add_SubCategory_form')
                !!}
            </div>
        </div>
    </div>
</div>
<!-- Edit subadmin -->
<div class="modal modal-effect" id="editSubCategoryModalPopup" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">

</div>


@endsection

@section('js')
<script>
    (function subCategoryList() {
        let oTable = $('#subcategory-listing').DataTable({
            ordering: true,
            bSort: false,
            aaSorting: [],
            serverSide: true,
            bLengthChange: false,
            bInfo: false,
            bFilter: false,
            processing: true,
            language: {
                zeroRecords: "<div class='alert alert-danger'>No data available in table </div>",
                emptyTable: "<div class='alert alert-danger'>No data available in table </div>",
                loadingRecords: "&nbsp;",
                processing: getLoader()
            },
            ajax: {
                url: "{{url('admin/manage-sub-category/list')}}",
                data: function(d) {
                    d.name = $('#name').val(),
                        d.status = $('#status').val(),
                        d.category_id = $('#category_id').val(),
                        d.type = 'appraiser'
                },

            },
            "complete": function(json, type) { //type return "success" or "parsererror"
                let strcount = (parseInt(json.responseJSON.data.length) > 0) ? json.responseJSON.data.length : 0;
                $('.strcount').text(strcount);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'category_name',
                    name: 'category_name'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });
        $('#search-form').keydown(function(e) {
            var key = e.which;
            if (key == 13) {
                $('#listing').empty();
                oTable.draw();
                e.preventDefault();
            }
        });
        $('#search-form').on('submit', function(e) {
            $('#listing').empty();
            oTable.draw();
            e.preventDefault();
        });

        $('#clear-search').on('click', function(e) {
            $('#listing').empty();
            $('#search-form')[0].reset();
            $('#status').selectpicker('refresh')
            $('#category_id').selectpicker('refresh')
            oTable.draw();
            e.preventDefault();
        });

    })();

    function addSubCategory() {
        var formData = $("#add_SubCategory_form").serializeArray();

        if ($('#add_SubCategory_form').valid()) {
            $('#btnAdd').prop('disabled', true);
            $('#btnAddLoader').css("display", '');
            $.ajax({
                type: "POST",
                url: "{{url('admin/manage-sub-category/add')}}",
                data: formData,
                success: function(response) {
                    $('#btnAdd').prop('disabled', false);
                    $('#btnAddLoader').css('display', 'none');
                    toastr.clear();
                    if (response.success) {
                        Command: toastr['success'](response.message);
                        $("#addSubCategoryModal").modal('hide');
                        $('#subcategory-listing').DataTable().ajax.reload();
                    }
                    else {
                        Command: toastr['error']('Something went wrong.');
                    }
                },
                error: function() {
                    $('#btnAdd').prop('disabled', false);

                    Command: toastr['error']('Something went wrong.');
                }
            });
        }
    };

    function changeStatus(id) {
        bootbox.confirm('Are you sure you want to change the status ?', function(result) {
            let status = $("#subcategory" + id).data('status');
            if (result) {
                if ($("#subcategory" + id).prop("checked") == false) {
                    status = 'inactive';
                } else {
                    status = 'active';
                }
                $.ajax({
                    type: "POST",
                    url: "{{url('admin/manage-sub-category/change-status')}}",
                    data: {
                        id: id,
                        status: status,
                        _token: "{{csrf_token()}}"
                    },
                    success: function(response) {
                        toastr.clear();
                        Command: toastr['success'](response.message);
                        //$('#category-listing').DataTable().ajax.reload();
                    }
                });
            } else {
                if (status == 'active') {
                    $('#subcategory' + id).prop('checked', true);
                } else {
                    $('#subcategory' + id).prop('checked', false);
                }
            }
        })

    }

    function ediSubCategory(id) {
        var url = "{{url('admin/manage-sub-category/detail')}}";
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function(response) {
                $("#editSubCategoryModalPopup").modal('show');
                $('#editSubCategoryModalPopup').html(response);

            }
        });
    }

    /*
     * Delete user by id
     */
    function deleteSubCategory(id) {
        bootbox.confirm('Are you sure you want to delete ?', function(result) {
            if (result) {
                var url = "{{url('admin/manage-sub-category/delete')}}";
                $.ajax({
                    type: "DELETE",
                    url: url + '/' + id,
                    data: {
                        '_token': "{{csrf_token()}}",
                        user_type: 'employee'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#subcategory-listing').DataTable().ajax.reload();
                            Command: toastr['success'](response.message);
                        } else {
                            Command: toastr['error'](response.message);
                        }
                    },
                    error: function() {
                        Command: toastr['success']('Something went wrong.');
                    }
                });
            }
        });
    }

    function showAddSubCategory() {
        $('.error-help-block').text('');
        $('#add_SubCategory_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addSubCategoryModal").modal('show');
    }
</script>
@endsection
