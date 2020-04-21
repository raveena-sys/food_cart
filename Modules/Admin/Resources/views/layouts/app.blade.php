<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('admin::layouts.head')

<body>
    @include('admin::layouts.header')
    @include('admin::layouts.sidebar')
    {{-- <div id="app"> --}}
    
    @yield('content')
    {{-- </div> --}}
    @include('admin::layouts.footer')
    @yield('js')
    <!-- Laravel Javascript Validation -->


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

    @if(Session::has('success'))

    <script>
        toastr.clear();
        Command: toastr['success']("{{ Session::get('success') }}");
    </script>
    @endif
    @if(Session::has('info'))
    <script>
        toastr.clear();
        Command: toastr['info']("{{ Session::get('info') }}");
    </script>
    @endif
    @if(Session::has('warning'))
    <script>
        toastr.clear();
        Command: toastr['warning']("{{ Session::get('warning') }}");
    </script>
    @endif
    @if(Session::has('error'))
    <script>
        toastr.clear();
        Command: toastr['error']("{{ Session::get('error') }}");
    </script>
    @endif

</body>

</html>