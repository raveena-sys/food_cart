@extends('store::layouts.app')
@section('content')
<?php $current = Request::segment(4)==1?'Double/Triple Product':(Request::segment(4)==2?'Sides Product':'Drink Product'); ?>
<main class="main-content">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row filter-page-btn">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item">Manage Special Menu</li>
                        <li class="breadcrumb-item active" aria-current="page">
                           
                        {{(Request::segment(4)==1)?'Double/Triple Product':(Request::segment(4)==2?'Sides Product':'Drink Product')}}</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
            <div class="right-side">
                <a aria-controls="filterSection" aria-expanded="false" class="btn btn-light ripple-effect d-lg-none" data-toggle="collapse" href="#filterSection" id="filterbtn" role="button"><span class="ripple rippleEffect"></span><i class="icon-filter1"></i></a>
                <a href="{{ URL::To('store/manage-special-menu/add/'.Request::segment(4)) }}" class="btn btn-danger ripple-effect text-uppercase">Add {{Request::segment(4)==1?'Double/Triple Product':(Request::segment(4)==2?'Sides Product':'Drink Product')}} </a>
                @if(Auth::user()->user_type == 'store')
                <!-- <a href="javascript:void(0);" onclick="showSelectProducts()" class="btn btn-danger ripple-effect text-uppercase">Select Special Menu</a> -->
                

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
                        <select class="form-control selectpicker" id="status" name="status" title="Status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
            <input type="hidden" name="segment" value="{{Request::segment(4)}}" class="segment">
            <div class="table-responsive">
                <table class="table" id="category-listing">
                    <thead>
                        <tr>
                            <th class="w_80"><span class="sorting">S.No.</span></th>
                            <th><span class="sorting">Name</span></th>
                            <th> Category </th>
                            <th> Sub Category</th>
                            <th> Store </th>
                            <!-- <th> Price ($) </th> -->
                            <th> Quantity </th>
                            <th> Topping Form </th>
                            <!-- <th> Description </th> -->
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

<!-- Edit subadmin -->
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
                url: "{{url('store/manage-special-menu/list-data')}}",
                data: function(d) {
                    d.name = $('#name').val(),
                        d.status = $('#status').val(),
                        d.seg = $('.segment').val()
                    //,d.type = 'appraiser'

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
                },

               /* {
                    data: 'size_name',
                    name: 'size_name'
                },*/
                {
                    data: 'category_name',
                    name: 'category_name'
                },
                {
                    data: 'sub_name',
                    name: 'sub_name'
                },

                {
                    data: 'store_name',
                    name: 'store_name'
                },
               /* {
                    data: 'price',
                    name: 'price'
                },*/
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'topping_from',
                    name: 'topping_from'
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
                url: "{{url('store/product/add')}}",
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
                    url: "{{url('store/product/change-status')}}",
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
        var url = "{{url('store/product/detail')}}";
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
                var url = "{{url('store/product/delete')}}";
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

    
    function getProductSelection(id) {
        var url = "{{url('store/product/detail')}}";
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function(response) {
                $("#editCategoryModalPopup").modal('show');
                $('#editCategoryModalPopup').html(response);

            }
        });
    }


    function showSelectProducts(id=''){
        $.ajax({
            url:"{{url('store/product/select')}}",
            type:'get',
            data:{id:id},
            success:function(response){
                $('.product_price').empty();
                $('.product_price').html('Select Special Menu to get in your Store');
                $("#customPriceModal").modal('show');
                if($('.product_id:last').val()!= ''){
                    $('.product_list').append(response);
                }
            }
        })



        /*$('.error-help-block').text('');
        $('#add_category_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addCategoryModal").modal('show'); */       
    }

    $(document).on('change', '.product_id', function(){
        if($(this).val()!=''){
            var selected = $('.product_id');
            selectedIDS = '';            
            selected.map(function() {
                if(this.value !=''){
                    selectedIDS += this.value+',';          
                }
            }).get();           
            var price = $('option:selected', this).attr('data-price');           
            $(this).parent().next().find('.product_price').val(price);
            showSelectProducts(selectedIDS);
        }else{
            $(this).parent().next().find('.product_price').val('0');
        }
    })


    function addProdcutSelect(){
        var selected = $('.product_id');
        selectedIDArray = [];        
        $.each(selected, function(obj, val){
            if($(this).val() != ''){ 
                selectedIDArray.push({'id':$("option:selected", this).val(),'price':$(this).parent().next().find('.product_price').val()});
            }            
        });
        if(selectedIDArray.length>0){
           $('.label_product_error').empty(); 
            $.ajax({
            url:"{{url('store/product/addselection')}}",
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
                
                $('.product_list').find('.row').not(":last").remove();
                $('#category-listing').DataTable().ajax.reload();
            },
            error: function() {
                Command: toastr['success']('Something went wrong.');
                $("#customPriceModal").modal('hide'); 
                
                $('.product_list').find('.row').not(":last").remove();
            }
        })
        }else{
           $('.label_product_error').html('This field is required'); 

        }
    }      

    function showEditProductPrice(id){
        $.ajax({
            url:"{{url('store/product/select')}}",
            type:'get',
            data:{product_id:id},
            success:function(response){

                $('.product_price').empty();                
                $('.product_price').html('Edit Price');
                $('.product_list').empty();
                $("#customPriceModal").modal('show');
                //if($('.product_id:last').val()!= ''){
                $('.product_list').append(response);
                //}
            }
        })
    }     
</script>
@endsection
