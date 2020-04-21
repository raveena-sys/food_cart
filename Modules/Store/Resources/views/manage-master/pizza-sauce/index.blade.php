@extends('store::layouts.app')
@section('content')
<?php $current = 'Pizza Sauce'; ?>
<main class="main-content">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row filter-page-btn">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pizza Sauce</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
            <div class="right-side">
                <a aria-controls="filterSection" aria-expanded="false" class="btn btn-light ripple-effect d-lg-none" data-toggle="collapse" href="#filterSection" id="filterbtn" role="button"><span class="ripple rippleEffect"></span><i class="icon-filter1"></i></a>
                <a href="javascript:void(0);" onclick="showAddCategory()" class="btn btn-danger ripple-effect text-uppercase">Add Pizza Sauce</a>
                @if(Auth::user()->user_type == 'store')
                <a href="javascript:void(0);" onclick="showSelectSauces()" class="btn btn-danger ripple-effect text-uppercase">Select Pizza Sauce</a>
                

                @endif
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
                    <div class="btn_clumn mb-3">
                        <button type="submit" class="btn btn-secondary ripple-effect mr-1" data-toggle="tooltip" data-placement="top" title="Search"><i class="icon-search"></i></button>
                        <button type="reseat" id="clear-search" class="btn btn-outline-danger ripple-effect" data-toggle="tooltip" data-placement="top" title="Reset"><i class="icon-refresh"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- filter section end -->
        <!-- table listing start -->
        <div class="common-table">
            <div class="table-responsive">
                <table class="table" id="category-listing">
                    <thead>
                        <tr>
                            <th class="w_80"><span class="sorting">S.No.</span></th>
                            <th><span class="sorting">Name</span></th>
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
<!-- add substore -->
<div class="modal modal-effect" id="addCategoryModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-libre-bold w-100 text-center">Add Pizza Sauce</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-clear"></i>
                </button>
            </div>
            <div class="modal-body field-padd">
                <form id="add_category_form" method="POST" class="needs-validation" novalidate autocomplete="false" action="{{URL::To('store/manage-pizza-sauce/add')}}">
                    {{csrf_field()}}
                    <input class="form-control" name="store_id" type="hidden" value="{{Auth::check()?Auth::user()->store_id:0}}">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" name="name" type="text" placeholder="Name" maxlength="250">
                    </div>
                    <div class="form-group">
                    <label>Price</label>
                        <input class="form-control" type="text" name="price" placeholder="Price" value="" maxlength="250">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" type="text" placeholder="Description" maxlength="255"></textarea>
                    </div>

                    <div class="form-group text-center mb-0">
                        <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="addCategory()" type="button">ADD<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                        <!--  -->
                    </div>
                </form>
                {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddPizzaSauceRequest','#add_category_form')
                !!}
            </div>
        </div>
    </div>
</div>
<div class="modal modal-effect" id="customPriceModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-libre-bold w-100 text-center sauce_price">Select Pizza sauce to get in your Store</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-clear"></i>
                </button>
            </div>
            <div class="modal-body field-padd">
                <form id="add_category_form" method="POST" class="needs-validation" novalidate autocomplete="false" action="{{URL::To('store/manage-category/add')}}">
                    {{csrf_field()}}
                    
                    <div class="row sauce_list">
                        <div class="form-group col-md-8">
                            <label>Pizza sauce</label>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Price</label> 
                        </div>
                    </div> 
                    <label class="label_sauce_error help-block error-help-block form-text text-danger"></label>                  
                    <div class="form-group text-center mb-0">
                        <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="addSauceSelect()" type="button">Select<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                        <!--  -->
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
<!-- Edit substore -->
<div class="modal modal-effect" id="editCategoryModalPopup" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">

</div>


@endsection

@section('js')
<script>
    (function categoryList() {
        let oTable = $('#category-listing').DataTable({
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
                url: "{{url('store/manage-pizza-sauce/list')}}",
                data: function(d) {
                    d.name = $('#name').val(),
                        d.status = $('#status').val(),
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
            oTable.draw();
            e.preventDefault();
        });

    })();

    function addCategory() {
        var formData = $("#add_category_form").serializeArray();

        if ($('#add_category_form').valid()) {
            $('#btnAdd').prop('disabled', true);
            $('#btnAddLoader').css("display", '');
            $.ajax({
                type: "POST",
                url: "{{url('store/manage-pizza-sauce/add')}}",
                data: formData,
                success: function(response) {
                    $('#btnAdd').prop('disabled', false);
                    $('#btnAddLoader').css('display', 'none');
                    toastr.clear();
                    if (response.success) {
                        Command: toastr['success'](response.message);
                        $("#addCategoryModal").modal('hide');
                        $('#category-listing').DataTable().ajax.reload();
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
            let status = $("#category" + id).data('status');
            if (result) {
                if ($("#category" + id).prop("checked") == false) {
                    status = 'inactive';
                } else {
                    status = 'active';
                }
                $.ajax({
                    type: "POST",
                    url: "{{url('store/manage-pizza-sauce/change-status')}}",
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
                    $('#category' + id).prop('checked', true);
                } else {
                    $('#category' + id).prop('checked', false);
                }
            }
        })

    }

    function ediCategory(id) {
        var url = "{{url('store/manage-pizza-sauce/detail')}}";
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function(response) {
                $("#editCategoryModalPopup").modal('show');
                $('#editCategoryModalPopup').html(response);

            }
        });
    }

    /*
     * Delete user by id
     */
    function deleteCategory(id) {
        bootbox.confirm('Are you sure you want to delete ?', function(result) {
            if (result) {
                var url = "{{url('store/manage-pizza-sauce/delete')}}";
                $.ajax({
                    type: "DELETE",
                    url: url + '/' + id,
                    data: {
                        '_token': "{{csrf_token()}}",
                        user_type: 'employee'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#category-listing').DataTable().ajax.reload();
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

    function showAddCategory() {
        $('.error-help-block').text('');
        $('#add_category_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addCategoryModal").modal('show');
    }




    /*function getSauceSelection(id) {
        var url = "{{url('store/manage-pizza-sauce/detail')}}";
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function(response) {
                $("#editCategoryModalPopup").modal('show');
                $('#editCategoryModalPopup').html(response);

            }
        });
    }*/


    function showSelectSauces(id=''){
        $.ajax({
            url:"{{url('store/manage-pizza-sauce/select')}}",
            type:'get',
            data:{id:id},
            success:function(response){
                $('.sauce_price').empty();
                $('.sauce_price').html('Select Sauce to get in your Store');
                $("#customPriceModal").modal('show');
                if($('.sauce_id:last').val()!= ''){
                    $('.sauce_list').append(response);
                }
            }
        })



        /*$('.error-help-block').text('');
        $('#add_category_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addCategoryModal").modal('show'); */       
    }

    $(document).on('change', '.sauce_id', function(){
        if($(this).val()!=''){
            var selected = $('.sauce_id');
            selectedIDS = '';            
            selected.map(function() {
                if(this.value !=''){
                    selectedIDS += this.value+',';          
                }
            }).get();           
            var price = $('option:selected', this).attr('data-price');           
            $(this).parent().next().find('.sauce_price').val(price);
            showSelectSauces(selectedIDS);
        }else{
            $(this).parent().next().find('.sauce_price').val('0');
        }
    })


    function addSauceSelect(){
        var selected = $('.sauce_id');
        selectedIDArray = [];        
        $.each(selected, function(obj, val){
            if($(this).val() != ''){ 
                selectedIDArray.push({'id':$("option:selected", this).val(),'price':$(this).parent().next().find('.sauce_price').val()});
            }            
        });
        if(selectedIDArray.length>0){
           $('.label_sauce_error').empty(); 
            $.ajax({
            url:"{{url('store/manage-pizza-sauce/addselection')}}",
            type:'post',
            data:{
                '_token': "{{csrf_token()}}",
                id:selectedIDArray
            },
            success:function(response){
                if(response.success){
                    Command: toastr['success'](response.message);
                } else {
                    Command: toastr['error'](response.message);
                }
                $("#customPriceModal").modal('hide'); 
                
                $('.sauce_list').find('.row').not(":last").remove();
                $('#category-listing').DataTable().ajax.reload();
            },
            error: function() {
                Command: toastr['success']('Something went wrong.');
                $("#customPriceModal").modal('hide'); 
                
                $('.sauce_list').find('.row').not(":last").remove();
            }
        })
        }else{
           $('.label_sauce_error').html('This field is required'); 

        }
    } 
</script>
@endsection
