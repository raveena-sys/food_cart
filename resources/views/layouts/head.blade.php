<head>
    <meta charset="utf-8" />
    <title>{{!empty(Request::segment(1))?ucwords(str_replace('_', ' ', Request::segment(1))):"Home Page"}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" 
      type="image/png" 
      href="{{asset('img/logo_black.png')}}">
    <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet" /> -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/slick.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css')}}" />

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}" type="text/css"> -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css"> -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-select.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/jquery.mCustomScrollbar.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/tempusdominus-bootstrap-4.min.css')}}" type="text/css">
    <!-- <link rel="stylesheet" href="{{ asset('backend/css/icomoon.css')}}" type="text/css"> -->
    <!-- <link rel="stylesheet" href="{{ asset('backend/css/admin.min.css')}}" type="text/css"> -->
    <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css')}}" type="text/css">


    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('js/slick.js')}}"></script>
    <script src="{{ asset('js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/custom.js')}}"></script>
    <script src="{{ asset('js/bootbox.min.js')}}"></script>
    <script src="{{ asset('js/jsvalidation.min.js')}}"></script>

    <script type="text/javascript" src="{{ asset('common/plugin/datatable/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/plugin/datatable/dataTables.bootstrap4.min.js') }}"></script>

    <script type="text/javascript">
    var site_url = "{{url('/')}}";
    </script>


</head>