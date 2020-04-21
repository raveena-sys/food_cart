<!-- add ReadMore -->

<!--  -->
<!--Purchase History-start-->
    <div class="modal modal-effect delete-popup" data-backdrop="static" data-keyboard="false"  tabindex="-1" role="dialog" id="deleteCustomer" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xs">
            <div class="modal-content">
                <!-- xxxxx -->
                <div class="modal-body">
                   <h4 class="h-20 font-reg mb-4 text-center">Are you sure want to delete ? </h4>
                    <input type="hidden" id="selectRemoveID">
                    <div class="btn_row text-center">
                        <button class="btn btn-outline-light ripple-effect text-uppercase" onclick="confirmDelete()">No</button>
                        <button class="btn btn-danger ripple-effect text-uppercase" onclick="deleteRow()">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="overlay-screen"></div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.11/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" type="text/css">-->
<script src="{{ asset('js/popper.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.min.js')}}"></script>
<script src="{{ asset('js/bootstrap-select.min.js')}}"></script>
<script src="{{ asset('js/moment.js')}}"></script>
<script src="{{ asset('js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{ asset('js/jquery.fancybox.js')}}"></script>
<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('backend/js/tinymce/tinymce.min.js') }}"></script>

<script>
    // Tool-tip
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
      $(document).on('hover', '[data-toggle=tooltip]', function () { $(this).tooltip('show'); });
      $(document).on('focus', '[data-toggle=tooltip]', function () { $(this).tooltip('hide'); });
    })
	//ripple-effect for button
	$('.ripple-effect, .ripple-effect-dark').on('click', function(e) {
	    var rippleDiv = $('<span class="ripple-overlay">'),
	        rippleOffset = $(this).offset(),
	        rippleY = e.pageY - rippleOffset.top,
	        rippleX = e.pageX - rippleOffset.left;

	    rippleDiv.css({
	        top: rippleY - (rippleDiv.height() / 2),
	        left: rippleX - (rippleDiv.width() / 2),
	        // background: $(this).data("ripple-color");
	    }).appendTo($(this));

	    window.setTimeout(function() {
	        rippleDiv.remove();
	    }, 800);
	});

	// switch Effect
    setTimeout(function(){
        $(".switch, .custom-radio, .custom-checkbox, .checkbx_shadow").each(function () {
            var intElem = $(this);
            intElem.on('click', function () {
				//alert('hello');
                intElem.addClass('interactive-effect');
                setTimeout(function () {
                    intElem.removeClass('interactive-effect');
                }, 400);


            });
        });
    }, 3000);




	if($(window).width() > 1200){
	$('#menu').click(function(){
		$('body').toggleClass('open-menu')
	});
	}

    if($(window).width() <= 1199){
    //side menu
	$('#menu').click(function(){
		$('body').toggleClass('open-menu')
	});
		$('.overlay-screen').click(function(){
		$('body').removeClass('open-menu')
	});
    }


	// filter open / close
    function filterOpen(){
        $('#filterform').addClass('slide');
    }

    function filterClose(){
        $('#filterform').removeClass('slide');
    }

    //for filter
    $("#filterbtn").click(function() {
		$("#filterSection").toggleClass("open");
	});

	$("#closeFilter").click(function() {
		$("#filterSection").removeClass("open");
	});


    //fancy box
    $(".fancybox").fancybox({
        openEffect  : 'none',
        closeEffect : 'none',
        arrows:true
    });


    // Delete Modal
 	function askDeleteModal(id) {
        $("#selectRemoveID").val(id);
        $("#deleteCustomer").modal('show');
    }
     function confirmDelete(){
        $('#deleteCustomer').modal('hide');
    }

   $('.selectpicker').selectpicker();

        //Date Picker
    $('#SelectDate, #SelectDate01, #SelectDate02, #SelectDate03, #SelectDate04, #SelectDate05').datetimepicker({
        format: 'L',
        // debug:true
    });

       function deleteRow() {
       let id =$("#selectRemoveID").val();
       var mainUrl = $('#remove'+id).data('url');
       var tableid = $('#remove'+id).data('tableid');
       console.log(tableid);
       var name = $('#remove'+id).data('name');
       $.ajax({type: "GET",
           url: mainUrl + '/' +id,
           success: function (response)
           {
               toastr.success(response.message);
               confirmDelete()
                $("#"+tableid).DataTable().ajax.reload();
           }
       });
       $('#delete').modal('hide');
   }
$('#search-form').keydown(function(e){
    if (e.keyCode === 13) { // If Enter key pressed
        $(this).trigger('submit');
    }
});

$('.selectpicker').selectpicker();


   function getLoader(){
        var url = "{{url('public/backend/images/loader.svg')}}";
        let str = "<center><img class='icon spinner' src='"+url+"' alt='loader'></center>"
        return str;
   }

        $('.selectpicker').on('change', function(e){
            var name =$(this).attr("name");
            $('#'+name+'-error').text(" ");
        });

        $('.selectpicker').on('change', function(e){
            var id =$(this).attr("id");
            $('#'+id+'-error').text(" ");
        });
</script>
