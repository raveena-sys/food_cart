@extends('store::layouts.app')
@section('content')
<?php
$current =!empty($data)?'Edit Delivery Zone':'Add Delivery Zone';
?>

<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Manage Delivery Zone</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{!empty($data)?'Edit':'Add'}} Delivery Zone</li>
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
               <h4 class="mb-0">{{!empty($data)?'Edit':'Add'}} Delivery Zone</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <!-- add list -->
                    <form enctype="multipart/form-data"  method="POST" class="f-field" action="{{ url('store/manage-delivery/add') }}" id="editSocial">
                        {{csrf_field()}}
                        <?php
                        if(isset($data)){
                            $zip_code = explode(',', $data['postal_code']); 
                        }
                        ?>                      
                        <input type="hidden" name="id" value="{{isset($data->id)?$data->id:''}}">
                        <div class="form-group">
                            <label>Postal Code</label>
                            <div class="menu__subcategory__inner post_span">
                                @if(!empty($zip_code))
                                @foreach($zip_code as $k => $v)   
                                @if(!empty($v))
                                <p class="subcategory__name">{{$v}}<input type="text" name="zip_code[]" value="{{$v}}" style ="display:none;"> <span class="close"> <i class="fa fa-close"></i></span></p>
                                @endif
                                @endforeach
                                @endif
                            </div>
                            <input type="text" name="zip_code[]" class="postal_code form-control" placeholder="" value="">
                            <p><strong>Note:</strong>Add postal code and press space to enter multiple</p>
                        </div>
                        <div class="form-group">
                            <label>Delivery Charge</label>
                            <input type="text" name="price" class="form-control" placeholder="0" value="{{isset($data->price)?$data->price:''}}">
                        </div>
                        <div class="btn_row text-center">
                            <a href="{{url('store/manage-delivery/add')}}" class="btn btn-outline-light ripple-effect text-uppercase">Cancel</a>
                            <button type="submit" class="btn btn-danger ripple-effect text-uppercase " id="btnCms" >Update
                                <span id="cmsFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\UpdatePostalCodeRequest','#editSocial') !!}

                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</div>
@if(Session::has('errmessage'))
<script>
    toastr.clear();
    Command: toastr['success']("{{ Session::get('errmessage') }}");



</script>
@endif

<script type="text/javascript">
    
$(".postal_code").keypress(function(event){
  if(event.which==32){
    var tag = $(this).val();
    //Clear the text input and add tag
    if(tag.trim() !==''){
        $(this).val('');
        $('.post_span').append('<p class="subcategory__name">'+tag+'<input type="hidden" name="zip_code[]" value="'+tag+'"> <span class="close"> <i class="fa fa-close"></i></span></p>');
    }
  }
});

$(document).on('click', '.close', function(){
    $(this).parent().remove();
});


</script>

@stop
