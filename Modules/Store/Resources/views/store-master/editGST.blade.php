@extends('store::layouts.app')
@section('content')
<?php
$current ="Edit GST %";
?>

<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Manage GST</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit GST %</li>
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
               <h4 class="mb-0">Edit GST %</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <!-- add list -->
                    <form enctype="multipart/form-data"  method="POST" class="f-field" action="{{ url('store/manage-gst/update') }}" id="editGST">
                        {{csrf_field()}}
                        <input type="hidden" name="store_id" value="{{Auth::check()?Auth::user()->store_id:''}}">  
                   
                        <div class="form-group">
                            <label>GST %</label>
                            <input type="text" name="gst_per" class="form-control" placeholder="0" value="{{!empty($data->gst_per)?$data->gst_per:''}}">
                        </div>
                        <div class="btn_row text-center">
                            <a href="{{url('store/manage-gst/edit')}}" class="btn btn-outline-light ripple-effect text-uppercase">Cancel</a>
                            <button type="submit" class="btn btn-danger ripple-effect text-uppercase " id="btnCms" >Update
                                <span id="cmsFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\UpdateGSTRequest','#editGST') !!}

                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</div>
@if(Session::has('message'))
<script>
    toastr.clear();
    Command: toastr['success']("{{ Session::get('message') }}");
</script>
@endif

@stop
