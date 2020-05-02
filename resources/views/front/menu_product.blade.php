@extends('layouts.app')
@section('content')
<section class="store_menu_banner"
    style='background-image: url("{{asset(isset($cms->header_image)?'/uploads/cms/'.$cms->header_image:'/img/home_bg.jpg')}}") !important;'>
    >
    <div class="container">
        <div class="clearfix"></div>
    </div>
</section>

<div class="container store_list_inner">

    <div class="row">
        <div class="col-xs-12">
            <div class="menu-nav">
                @if(!empty($subcategory))
                @foreach($subcategory as $val)
                <a href="#{{str_replace(' ', '_', $val->name)}}" class="subcategory">{{$val->name}}</a>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="product_item">
                {!!view('front.ajax.product_item', compact('products', 'subcategory'))->render()!!}
            </div>
        </div>
        <div class="col-lg-4">
            <div class="cartRight cart_item" style="margin-top:40px">
                {!!view('front.ajax.cart_item', compact('products'))->render()!!}
            </div>
        </div>
    </div>
</div>

<div class="sideMenu">
</div>
<div class="modal" id="confirmdeleteModal" tabindex="-1" role="dialog" backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this item?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary remove_item">Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="wconfirmdeleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                        class="sr-only">Close</span></button>
                <h3 class="modal-title" id="lineModalLabel">Confirm</h3>
            </div>
            <div class="modal-body">

                <div class="col-lg-9">
                    <div class="prdct_prc">
                        <label>Are you sure you want to remove this item?:</label>
                    </div>
                    <button class="btn_add_cart btn btn-primary">Confirm</button>
                    <button class="btn btn-danger" data>Cancel</button>

                </div>
                <div class="clearfix"></div>


            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

@endsection


