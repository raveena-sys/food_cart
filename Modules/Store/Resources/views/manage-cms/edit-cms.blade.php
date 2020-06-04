@extends('store::layouts.app')
@section('content')
<?php
$current ="Edit". ' ' . $data->page_name;
?>

<main class="main-content add-page">
    <div class="container-fluid">
        <!-- page title section start -->
        <div class="page-title-row">
            <div class="left-side">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('store')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Manage CMS</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit {{$data->page_name}}</li>
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
               <h4 class="mb-0">Edit {{ucWords($data->page_name)}}</h4>
            </div>
            <div class="card-body">
                <div class="inner_cnt">
                    <!-- add list -->
                    <form enctype="multipart/form-data"  method="POST" class="f-field" action="{{ url('store/manage-cms/update-cms-page') }}">
                        {{csrf_field()}}
                        <input type="hidden" name="cms_id" value="{{$data->id}}">
                        @if($data->page_slug=='home_page')
                        <div class="form-group">
                            <label>Banner Video </label>
                        </div>  
                        <div class="upload_photo col-12">
                            <div class="img-box">
                                @if($data->home_video)
                                <video width="100%" height="100%" controls>
                                    <source src="{{getUserImage($data->home_video,'cms')}}" type="video/mp4" class="img-fluid box-circle-vid">
                                    <source src="{{getUserImage($data->home_video,'cms')}}" type="video/ogg" class="img-fluid box-circle-vid">
                                    Your browser does not support the video tag.
                                  </video>
                                @endif
                            </div>
                           <!--  <label class="mb-0 ripple-effect" for="uploadHomeVideo"> -->
                                <input type="file" name="home_video" id="uploadHomeVideo" accept="video/*" value="">
                               <!--  <i class="icon-photo_camera"></i>
                            </label> -->
                        </div>
                        @endif
                        @if($data->page_slug=='home_page' || $data->page_slug=='order_type'|| $data->page_slug=='menu_list')
                        <div class="form-group">
                            <label>Background Image (Image dimension must be (1348*799))</label>
                        </div>  
                        <div class="upload_photo col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($data->background_image,'cms')}}" alt="user-img" class="img-fluid rounded-circle-back">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadBackImage">
                                <input type="file" name="background_image" id="uploadBackImage" accept="image/*" value="">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        @endif

                        @if($data->page_slug=='store_list' || $data->page_slug=='menu_products' || $data->page_slug=='about_us'  || $data->page_slug=='privacy_policy')
                        <div class="form-group">
                            <label>Header Image (Image dimension must be (1348*258))</label>
                        </div>  
                        <div class="upload_photo col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($data->header_image,'cms')}}" alt="user-img" class="img-fluid rounded-circle-head">
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadHeadImage">
                                <input type="file" name="header_image" id="uploadHeadImage" accept="image/*" value="">
                                <i class="icon-photo_camera"></i>
                            </label>
                        </div>
                        @endif
                        @if($data->page_slug=='store_list')
                       <!--  <div class="form-group">
                            <label>Left Side Image</label>
                        </div>  
                        <div class="upload_photo mx-auto text-center col-12">
                            <div class="img-box">
                                <img src="{{getUserImage($data->side_image,'cms')}}" alt="user-img" class="img-fluid rounded-circle-left" >
                            </div>
                            <label class="mb-0 ripple-effect" for="uploadLeftImage">
                                <input type="file" name="side_image" id="uploadLeftImage" accept="image/*">
                                <i class="icon-photo_camera" value=""></i>
                            </label>
                        </div> -->
                        @endif
                        
                        @if($data->page_slug=='store_list' || $data->page_slug=='order_type' || $data->page_slug=='menu_list' || $data->page_slug=='about_us'  || $data->page_slug=='privacy_policy')
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="page_title" class="form-control" placeholder="Title" value="{{$data->page_title}}">
                        </div>
                        @endif
                        @if($data->page_slug=='home_page')
                        <div class="form-group">
                            <label>Left Title (Store content text)</label>
                            <input type="text" name="left_content" class="form-control" placeholder="Left cotnent" value="{{$data->left_content}}">
                        </div>
                        <div class="form-group">
                            <label>Right Title (Contact us content text)</label>
                            <input type="text" name="right_content" class="form-control" placeholder="Right cotnent" value="{{$data->right_content}}">
                        </div>
                        @endif
                        <div class="form-group">
                            <label>Content</label>
                            <textarea id="description" name="page_content" class="form-control" rows="15" placeholder="content">{{$data->page_content}}
                            </textarea>
                            <span id="validation-error-msg"></span>
                        </div>
                        <div class="btn_row text-center">
                            <a href="{{url('store/manage-cms')}}" class="btn btn-outline-light ripple-effect text-uppercase">Cancel</a>
                            <button type="submit" class="btn btn-danger ripple-effect text-uppercase " id="btnCms" >Update
                                <span id="cmsFormLoader" class="spinner-border spinner-border-sm" style="display: none;"></span>
                            </button>
                        </div>
                    </form>
                    {!! JsValidator::formRequest('Modules\Admin\Http\Requests\EditCmsRequest','#editCms') !!}

                </div>
            </div>
            <!-- xx -->
        </div>
    </div>
</div>


<script type="text/javascript">
    // onload tinymce text editor
    $(document).ready(function () {
        tinymceInit();
    });
    /** Summary of the this function:  This function is used to initiat(show) cms editor

     Parameters   : None

     Description: This is used to initiat editor. **/

    function tinymceInit() {
        tinymce.init({
            theme: "modern",
            selector: "textarea",
            relative_urls: false,
            remove_script_host: true,
            convert_urls: false,
            plugins: 'preview code searchreplace autolink directionality table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern',
            toolbar: 'undo redo | formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            height: 200,
            init_instance_callback: function (editor) {
                editor.on('keyup', function (e) {
                    var description = tinymce.get('description').getContent();
                    if (description === undefined || description.length == 0)
                    {
                        $('#validation-error-msg').css({"color": "red", "display": "inline-block", "margin-top": "5px"}).html("Description field is required.");
                    } else
                    {
                        $('#validation-error-msg').html('');
                    }
                });
            }
        });
    }

 /*   function editCms()
    {
        var description = tinymce.get('description').getContent(); // get textarea content.
        if (description) {
            $('#validation-error-msg').html('');
            var formData = $("#editCms").serializeArray();// this is used serialize data.
            formData.forEach(function (item) {
                if (item.name === 'page_content') {
                    item.value = tinymce.get('description').getContent();
                }
            });
             
          
           if ($('#editCms').valid()) {
                $('#btnCms').prop('disabled', true);
                $('#cmsFormLoader').show();// this will show button loader.
                $.ajax({
                    type: "POST",
                    url: "{{ url('store/manage-cms/update-cms-page') }}",
                    data: formData,
                    success: function (response)
                    {
                        var titile = response.page_name.charAt(0).toUpperCase() + response.page_name.slice(1);
                        toastr.clear();
                        Command: toastr['success'](titile + " edited successfully.");
                        $('#btnCms').prop('disabled', false);
                        $('#cmsFormLoader').hide();                      
                        setTimeout(function () {
                            window.location.href = "{{ url('store/manage-cms') }}"
                        }, 1000);
                    },
                    error: function (err) {
                        var obj = jQuery.parseJSON(err.responseText);
                        for (var x in obj) {
                            toastr.clear();
                            Command: toastr['error'](" SomeThing wrong")
                        }
                        $('#cmsFormLoader').hide();
                        $('#btnCms').prop('disabled', false);
                    }
                });
            }
        } else {
            $('#validation-error-msg').css({"color": "red", "display": "inline-block", "margin-top": "5px"}).html("Description field is required.");
        }
    }
*/


    $("#uploadHeadImage").change(function() {
        readURL(this, 'rounded-circle-head');
    });
    $("#uploadBackImage").change(function() {
        readURL(this, 'rounded-circle-back');
    });
    $("#uploadLeftImage").change(function() {
        readURL(this, 'rounded-circle-left');
    });
   /* $("#uploadHomeVideo").change(function() {
        readURL(this, 'box-circle-vid');
    });*/


    function readURL(input, imgClass) {
        if (input.files && input.files[0]) {
            var type = input.files[0].type;
            var size = input.files[0].size;
            if (type == 'image/png' || type == 'image/jpg' || type == 'image/jpeg'||  type == 'mp4') {
                if (size <= 2097152) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.'+imgClass).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    toastr.error('The profile picture may not be greater than 2MB.');
                    ('error', 'The profile picture may not be greater than 2MB.');
                    $('#uploadImage').val("");
                }
            } else {
                toastr.error('The profile picture must be a file of type: jpeg, png, jpg.');
                $('#uploadImage').val("");
            }
        }
    }
</script>
@stop
