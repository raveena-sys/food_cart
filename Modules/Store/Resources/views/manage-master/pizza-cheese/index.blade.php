@extends('store::layouts.app')
@section('content')
<?php $current = 'Pizza Extra Cheese'; ?>
<main class="main-content">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row filter-page-btn">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pizza Cheese</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
            <div class="right-side">
                <a aria-controls="filterSection" aria-expanded="false" class="btn btn-light ripple-effect d-lg-none" data-toggle="collapse" href="#filterSection" id="filterbtn" role="button"><span class="ripple rippleEffect"></span><i class="icon-filter1"></i></a>
                <a href="javascript:void(0);" onclick="showAddCheese()" class="btn btn-danger ripple-effect text-uppercase">Add Pizza Cheese</a>
                @if(Auth::user()->user_type == 'store')
                <!-- <a href="javascript:void(0);" onclick="showSelectCheese()" class="btn btn-danger ripple-effect text-uppercase">Select Pizza Cheese</a> -->
                

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
                            <th><span class="sorting">Pizza Size Name</span></th>
                            <th> Price ($)</th>
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
                <h4 class="modal-title font-libre-bold w-100 text-center">Add/Edit Pizza Cheese</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-clear"></i>
                </button>
            </div>
            <div class="modal-body field-padd">
                <form id="add_category_form" method="POST" class="needs-validation" novalidate autocomplete="false" action="{{URL::To('store/manage-pizza-cheese/add')}}">
                    {{csrf_field()}}
                     <input class="form-control" name="store_id" type="hidden"  value="{{Auth::check()?Auth::user()->store_id:''}}">
                    <!-- <div class="form-group">
                        <label>Store Name</label>
                        @php
                        $stores = \App\Models\StoreMaster::where('status', '!=', 'deleted')->get();
                        @endphp
                        <select class="form-control selectpicker" name="size_master_id" id="size_master_change" title="Select Cheese" data-size="4">
                            @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div> -->
                    <input class="form-control" name="id" type="hidden" value="" id="id">
                    <div class="form-group">
                        <label>Pizza Size Name</label>
                        @php
                        $sizequery = \App\Models\PizzaSizeMaster::select('pizza_size_master.*', 'store_pizza_size.custom_price')->where('pizza_size_master.status', 'active')->join('store_pizza_size',
                            function($join){
                              $join->on('store_pizza_size.size_id', '=', 'pizza_size_master.id');
                              $join->where('store_pizza_size.store_id', '=', Auth::user()->store_id);
                          })->get();
                        @endphp
                        <select class="form-control selectpicker" name="size_master_id" id="size_master_id" title="Select Pizza Size" >
                            @foreach($sizequery as $size)
                            <option value="{{$size->id}}">{{$size->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Price</label>
                        <input class="form-control" name="price" type="text" placeholder="Price" maxlength="250" value="" id="size_master_price">
                    </div>                    
                

                    <div class="form-group text-center mb-0">
                        <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="addCheese()" type="button">ADD/UPDATE<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                        <!--  -->
                    </div>
                </form>
                {!! JsValidator::formRequest('Modules\Admin\Http\Requests\AddPizzaCheeseRequest','#add_category_form')
                !!}
            </div>
        </div>
    </div>
</div>
<!-- Edit substore -->

<!-- Add crust in store -->
<div class="modal modal-effect" id="customPriceModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title font-libre-bold w-100 text-center product_price">Select Pizza Cheese to get in your Store</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-clear"></i>
                </button>
            </div>
            <div class="modal-body field-padd">
                <form id="add_category_form" method="POST" class="needs-validation" novalidate autocomplete="false" action="{{URL::To('store/manage-pizza-cheese/add')}}">
                    {{csrf_field()}}
                    <input class="form-control" name="store_id" type="hidden" value="{{Auth::check()?Auth::user()->store_id:0}}">
                    <div class="row cheese_list">
                        <div class="form-group col-md-8">
                            <label>Products</label>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Price</label> 
                        </div>
                    </div> 
                    <label class="label_cheese_error help-block error-help-block form-text text-danger"></label>                  
                    <div class="form-group text-center mb-0">
                        <button id="btnAdd" class="btn btn-danger ripple-effect text-uppercase min-w130" onClick="addCheeseSelect()" type="button">Select<span id="btnAddLoader" class="spinner-border spinner-border-sm" style="display: none;"></span></button>

                        <!--  -->
                    </div>
                </form>
                
            </div>
        </div>
    </div>
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
                url: "{{url('store/manage-pizza-cheese/list')}}",
                data: function(d) {
                    d.name = $('#name').val(),
                        d.status = $('#status').val(),
                        d.size_master_id = $('#size_master_id').val(),

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
                },
/*
                {
                    data: 'store',
                    name: 'store'
                },*/

                {
                    data: 'price',
                    name: 'price'
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





    function addCheese() {
        var formData = $("#add_category_form, :hidden").serializeArray();

        if ($('#add_category_form').valid()) {
            $('#btnAdd').prop('disabled', true);
            $('#btnAddLoader').css("display", '');
            $.ajax({
                type: "POST",
                url: "{{url('store/manage-pizza-cheese/add')}}",
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
                    url: "{{url('store/manage-pizza-cheese/change-status')}}",
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

    function editCheese(id) {
        var url = "{{url('store/manage-pizza-cheese/detail')}}";
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function(response) {
                $('#size_master_price').val(response.price);
                $('#size_master_id').val(response.pizza_size_master);
                $('#id').val(response.id);
                $('.selectpicker').selectpicker('refresh');
                $("#addCategoryModal").modal('show');
            }
        });
    }

    /*
     * Delete user by id
     */
    function deleteCheese(id) {
        bootbox.confirm('Are you sure you want to delete ?', function(result) {
            if (result) {
                var url = "{{url('store/manage-pizza-cheese/delete')}}";
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

    function showAddCheese() {
        $('.error-help-block').text('');
        $('#add_category_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addCategoryModal").modal('show');
    }

    function showSelectCheese(id=''){
        $.ajax({
            url:"{{url('store/manage-pizza-cheese/select')}}",
            type:'get',
            data:{id:id},
            success:function(response){
                $('.cheese_price').empty();
                $('.cheese_price').html('Select Pizza Cheese to get in your Store');
                $("#customPriceModal").modal('show');
                if($('.cheese_id:last').val()!= ''){
                    $('.cheese_list').append(response);
                }
            }
        })



        /*$('.error-help-block').text('');
        $('#add_category_form')[0].reset();
        $('.selectpicker').selectpicker('refresh');
        $("#addCategoryModal").modal('show'); */       
    }


    $(document).on('change', '.cheese_id', function(){
        if($(this).val()!=''){
            var selected = $('.cheese_id');
            selectedIDS = '';            
            selected.map(function() {
                if(this.value !=''){
                    selectedIDS += this.value+',';          
                }
            }).get();           
            var price = $('option:selected', this).attr('data-price');           
            $(this).parent().next().find('.cheese_price').val(price);
            showSelectCheese(selectedIDS);
        }else{
            $(this).parent().next().find('.cheese_price').val('0');
        }
    })


    function addCheeseSelect(){
        var selected = $('.cheese_id');
        selectedIDArray = [];        
        $.each(selected, function(obj, val){
            if($(this).val() != ''){ 
                selectedIDArray.push({'id':$("option:selected", this).val(),'price':$(this).parent().next().find('.cheese_price').val()});
            }            
        });
        if(selectedIDArray.length>0){
           $('.label_cheese_error').empty(); 
            $.ajax({
            url:"{{url('store/manage-pizza-cheese/addselection')}}",
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
                
                $('.cheese_list').find('.row').not(":last").remove();
                $('#category-listing').DataTable().ajax.reload();
            },
            error: function() {
                Command: toastr['success']('Something went wrong.');
                $("#customPriceModal").modal('hide'); 
                
                $('.cheese_list').find('.row').not(":last").remove();
            }
        })
        }else{
           $('.label_cheese_error').html('This field is required'); 

        }
    }  

</script>
@endsection
