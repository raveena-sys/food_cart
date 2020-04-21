@extends('admin::layouts.app')
@section('content')
    <?php $current = 'Manage CMS'; ?>
    <main class="main-content">
        <div class="container-fluid">
            <!-- page title section start -->
            <div class="page-title-row">
                
              
                <div class="left-side">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Manage CMS</li>
                        </ol>
                    </nav>
                    <h2 class="page-title text-capitalize">
                    <?php echo $current ?>
                    </h2>
                </div>
            </div>
            <!-- page title section end -->
            <!-- table listing start -->
            <div class="common-table min-h500">
                <div class="table-responsive" id="getCmsList">

            </div>
        </div>
        <!-- table listing end -->

    </div>
</main>
<script>
    // Run function when page refresh
    $(document).ready(function ()
    {
        $('#preloader').hide();// hide page loader after show list
        loadCmsList(); // cms list function call
    });
    function loadCmsList()
    {
     $.ajax({
            type: "GET", // send get type data
            url: "{{ url('admin/manage-cms/load-cms-list') }}", // send data on this url.
            success: function (response)
            {
                if (response.success) {
                    $("#getCmsList").html(response.html); // Show cms list content.
                }
            },
            error: function (err) {
                var obj = jQuery.parseJSON(err.responseText);
                for (var x in obj) {
                  //  toastrAlertMessage('error', obj[x]); //show error message.
                }
            }
        });
    }


    </script>
    <script>
      @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){           

            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;

            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
      @endif
    </script>
    @endsection
