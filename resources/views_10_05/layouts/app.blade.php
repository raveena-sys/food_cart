<!DOCTYPE html>
<html>
@include('layouts.head')

<body>
   
    @include('layouts.header')
    <div id="mainContent">
    @yield('content')
    </div>

    <!-- <div class="clearfix"></div> -->

    @include('layouts.footer')

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
    <script>
    $('.menu-nav a').click(function() {
        $('.menu-nav a').removeClass('active')
        $(this).addClass('active')
    })

    
var headerHeight = $('#header').height();
    $(window).scroll(function(){
    var e = $('.menu-wrapper'), i = e.offset();
    e.length > 0 && $(window).scrollTop() >= i.top - headerHeight ? $(".menu-nav").addClass('is-sticky') : $(".menu-nav").removeClass('is-sticky');
    console.log('test')

    $(".menu-nav").css("width", $('.store_list_inner').width())
    $(".menu-nav").css("top", headerHeight)
  })
    </script>
</body>

</html>
