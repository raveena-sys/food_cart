@extends('store::layouts.app')
@section('content')
<?php $current = 'Discount Coupon'; ?>
<main class="main-content">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row filter-page-btn">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Discount Coupon</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
            <div class="right-side">
                <a aria-controls="filterSection" aria-expanded="false" class="btn btn-light ripple-effect d-lg-none" data-toggle="collapse" href="#filterSection" id="filterbtn" role="button"><span class="ripple rippleEffect"></span><i class="icon-filter1"></i></a>
                <a href="{{url('store/manage-coupon/add')}}" class="btn btn-danger ripple-effect text-uppercase">Add Discount Coupon</a>
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
                            <th><span class="sorting">Coupon Code</span></th>
                            <th> Discount </th>
                            <th><span class="sorting">Expired at</span></th>
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

@endsection

@section('js')
<script>
    (function deliveryList() {
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
                url: "{{url('store/manage-coupon/list')}}",
                data: function(d) {
                    d.name = $('#name').val(),
                        d.status = $('#status').val(),
                        d.type = 'appraiser'

                },

            },
            "complete": function(json, ) { //type return "success" or "parsererror"

console.log("json", json);
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
                    data: 'coupon_code',
                    name: 'coupon_code'
                }, {
                    data: 'discount',
                    name: 'discount'
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
                    url: "{{url('store/manage-delivery/change-status')}}",
                    data: {
                        id: id,
                        status: status,
                        _token: "{{csrf_token()}}"
                    },
                    success: function(response) {
                        toastr.clear();
                        Command: toastr['success'](response.message);
                        $('#category-listing').DataTable().ajax.reload();
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

    

    /*
     * Delete user by id
     */
    function deleteCategory(id) {
        bootbox.confirm('Are you sure you want to delete ?', function(result) {
            if (result) {
                var url = "{{url('store/manage-delivery/delete')}}";
                 $.ajax({
                    type: "POST",
                    url: "{{url('store/manage-delivery/change-status')}}",
                    data: {
                        id: id,
                        status: 'deleted',
                        _token: "{{csrf_token()}}"
                    },
                    success: function(response) {
                        toastr.clear();
                        Command: toastr['success'](response.message);
                        $('#category-listing').DataTable().ajax.reload();
                    }
                });
            }
        });
    }

   
</script>
@endsection
