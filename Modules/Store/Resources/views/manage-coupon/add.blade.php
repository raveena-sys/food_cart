@extends('store::layouts.app')
@section('content')
<?php
$current =!empty($data)?'Edit Discount Coupon':'Add Discount Coupon';
?>

<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Manage Discount Coupon</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{!empty($data)?'Edit':'Add'}} Discount Coupon</li>
                    </ol>
                </nav>
                <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                </h2>
            </div>
        </div>
        <!-- page title section end -->
        <div class="card">
            <div class="card-header text-center border-0">
               <h4 class="mb-0">{{!empty($data)?'Edit':'Add'}} Discount Coupon</h4>
            </div>

           
            <div class="card-body">
                <div class="inner_cnt">
                    <!-- add list -->
                    <form enctype="multipart/form-data"  method="POST" class="f-field" id="addCoupon">
                        {{csrf_field()}}
                                           
                        <input type="hidden" name="id" value="{{isset($data->id)?$data->id:''}}">
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{isset($data->coupon_image)?getUserImage($data->coupon_image,'coupon'):getUserImage('','')}}" alt="user-img" class="img-fluid user-img">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadImage">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        <input type="file" name="image" id="uploadImage" accept="image/*" style="visibility: hidden;position:absolute;">
                        <input type="text" name="coupon_image" class="user-img_text" value="{{isset($data->coupon_image)?$data->coupon_image:''}}" style="visibility: hidden;position:absolute;">
                        <div class="form-group">
                            <label>Discount Coupon Type</label>
                            <div class="menu__subcategory__inner post_span">

                                <select name="discount_type" class="form-control" >
                                    <option value="fixed_discount" {{isset($data->coupon_type) && $data->coupon_type == 'fixed_discount'?'selected':''}}>Fixed Cart Discount</option>
                                    <option value="percent_discount" {{isset($data->coupon_type) && $data->coupon_type == 'percent_discount'?'selected':''}}>Percentage Discount</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Discount Coupon Code</label>
                            <input type="text" name="coupon_code" class="form-control" placeholder="" value="{{isset($data->coupon_code)?$data->coupon_code:''}}">
                        </div>
                        <div class="form-group">
                            <label>Discount Coupon Amount</label>
                            <input type="text" name="coupon_amount" class="form-control" placeholder="0" value="{{isset($data->coupon_amount)?$data->coupon_amount:''}}">
                        </div>
                        <div class="form-group">
                            <label>Discount Coupon Expiry Date</label>
                            <input type="text" name="expired_at" class="form-control" placeholder="YYY-MM-DD" value="{{isset($data->expired_at)?$data->expired_at:''}}">
                        </div>
                        <div class="btn_row text-center">
                            <a href="{{url('store/manage-coupon/add')}}" class="btn btn-outline-light ripple-effect text-uppercase">Cancel</a>
                            <button type="submit" class="btn btn-danger ripple-effect text-uppercase " id="btnCoupon" onClick="addCoupon()">Update
                                <span id="cmsFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\CouponRequest','#addCoupon') !!}

                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</div>


<script type="text/javascript">
 function addCoupon() {
        var formData = $("#addCoupon").serializeArray();
        jQuery.each(jQuery('#uploadImage')[0].files, function(i, file) {
            formData.append('image', file);
        });
        if ($('#addCoupon').valid()) {
            $('#btnCoupon').prop('disabled', true);
            $('#btnAddLoader').css("display", '');
            $.ajax({
                type: "POST",
                url: "{{url('store/manage-coupon/create')}}",
                data: formData,
                success: function(response) {
                    $('#btnAdd').prop('disabled', false);
                    $('#btnAddLoader').css('display', 'none');
                    toastr.clear();
                    if (response.status) {
                        Command: toastr['success'](response.message);
                        $("#addCoupon")[0].reset();
                        $('#btnCoupon').prop('disabled', false);
                        $('.user-img').prop('src', response.img);
                      
                    }
                    else {
                        Command: toastr['error']('Something went wrong.');
                        $('#btnCoupon').prop('disabled', false);

                    }
                },
                error: function() {
                    $('#btnCoupon').prop('disabled', false);

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
                    url: "{{url('store/manage-category/change-status')}}",
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
        var url = "{{url('store/manage-category/detail')}}";
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
                var url = "{{url('store/manage-coupon/delete')}}";
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
     $("#uploadImage").change(function(e) {   

        readURL(this);
     })
    function readURL(input) {
       
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.user-img').attr('src', e.target.result);
            $('.user-img_text').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);                
    }

</script>

@stop
