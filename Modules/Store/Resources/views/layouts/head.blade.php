<head>
    <!-- Required meta tags -->
    <title>FoodCart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- favicon -->
    <link rel="icon" 
      type="image/png" 
      href="{{asset('img/logo_black.png')}}">
    <link rel="shortcut icon" href="{{ asset('backend/images/favicon/favicon.ico') }}" type="image/x-icon">

    <link rel="icon" href="{{ asset('backend/images/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('backend/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('backend/images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('backend/images/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#5bbad5">
    <meta name="theme-color" content="#5bbad5">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/tempusdominus-bootstrap-4.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/css/icomoon.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/css/admin.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.min.css')}}" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/css/dashboard.css')}}" type="text/css">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="{{ asset('js/jquery.min.js')}}"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/bootbox.min.js')}}"></script>
    <script src="{{ asset('js/jsvalidation.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('common/plugin/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/plugin/datatable/dataTables.bootstrap4.min.js') }}"></script>   
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="{{ asset('js/custom_autoload.js')}}"></script>
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
    </script>
</head>

<script>
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": true,
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "3000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

</script>


@if(Session::has('log_out'))
<script>
    $(document).ready(function () {
        toastr.clear();
        Command: toastr['success']("{{ Session::get('log_out') }}");
    });

</script>
@endif
@if(Session::has('link_expired'))

<script>
    $(document).ready(function () {
        toastr.clear();
        Command: toastr['error']("{{ Session::get('link_expired') }}");
    });

</script>
@endif

@php
if(Session::has('log_out')){
\Session::forget('log_out');
}
if(Session::has('link_expired')){
\Session::forget('link_expired');
}
@endphp
