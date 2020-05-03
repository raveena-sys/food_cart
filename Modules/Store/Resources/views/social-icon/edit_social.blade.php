@extends('store::layouts.app')
@section('content')
<?php
$current ="Edit Social Link";
?>

<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Manage Social Link</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Social</li>
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
               <h4 class="mb-0">Edit Social</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <!-- add list -->
                    <form enctype="multipart/form-data"  method="POST" class="f-field" action="{{ url('store/manage-social/update') }}" id="editSocial">
                        {{csrf_field()}}
                        <input type="hidden" name="social_id" value="{{!empty($data->id)?$data->id:''}}">  
                        <input type="hidden" name="store_id" value="{{Auth::check()?Auth::user()->store_id:''}}">  
                         <div class="form-group">
                            <label>Facebook URL</label>
                            <input type="text" name="fb_url" class="form-control" placeholder="http://facebook.com" value="{{!empty($data->fb_url)?$data->fb_url:''}}">
                        </div>
                        <!-- <div class="form-group">
                            <label>Whatsapp URL</label>
                            <input type="text" name="whatsapp_url" class="form-control" placeholder="https://whatsapp.com/" value="{{!empty($data->whatsapp_url)?$data->whatsapp_url:''}}">
                        </div> -->
                        <div class="form-group">
                            <label>Twitter URL</label>
                            <input type="text" name="twitter_url" class="form-control" placeholder="https://twitter.com" value="{{!empty($data->twitter_url)?$data->twitter_url:''}}">
                        </div>
                        <div class="form-group">
                            <label>Linked URL</label>
                            <input type="text" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/" value="{{!empty($data->linkedin_url)?$data->linkedin_url:''}}">
                        </div>
                        <div class="btn_row text-center">
                            <a href="{{url('store/manage-social/edit')}}" class="btn btn-outline-light ripple-effect text-uppercase">Cancel</a>
                            <button type="submit" class="btn btn-danger ripple-effect text-uppercase " id="btnCms" >Update
                                <span id="cmsFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\UpdateSocialLinkRequest','#editSocial') !!}

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
