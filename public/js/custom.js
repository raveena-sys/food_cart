
$(document).ready(function (e) {
  $('.owl-carousel').owlCarousel({
    stagePadding: 0,
    autoplay: true,
    autoplaySpeed: 800,
    speed: 1000,
    fade: true,
    margin: 10,
    nav: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 1
      },
      1000: {
        items: 1
      }
    }
  })
});
$(document).ready(function (e) {
  $('.selectitemSlider').slick({
    slidesToShow: 2,
    slidesToScroll: 2
  });
  $(".variable").slick({
   
    dots: true,
    infinite: true,
    variableWidth: true
  });
});
$(document).ready(function (e) {
  $(window).scroll(function () {
    if ($(this).scrollTop() > 5) {
      $(".navbar-me").addClass("fixed-me");
    } else {
      $(".navbar-me").removeClass("fixed-me");
    }
  });
});
//jQuery(document).ready(function () {
  // This button will increment the value
  $(document).on('click', '.qtyplus', function (e) {
    // Stop acting like a button
    e.preventDefault();
    // Get the field name
    fieldName = $(this).attr('field');
    // Get its current value
    var currentVal = parseInt($('input[id=' + fieldName + ']').val());
    // If is not undefined
    if (!isNaN(currentVal)) {
      // Increment
      $('input[id=' + fieldName + ']').val(currentVal + 1);
    } else {
      // Otherwise put a 0 there
      $('input[id=' + fieldName + ']').val(0);
    }
    changeCustomization();
  });
  // This button will decrement the value till 0
  $(document).on('click', '.qtyminus', function (e) {
    // Stop acting like a button
    e.preventDefault();
    // Get the field name
    fieldName = $(this).attr('field');
    // Get its current value
    var currentVal = parseInt($('input[id=' + fieldName + ']').val());
    // If it isn't undefined or its greater than 0
    if (!isNaN(currentVal) && currentVal > 0) {
      // Decrement one
      $('input[id=' + fieldName + ']').val(currentVal - 1);
    } else {
      // Otherwise put a 0 there
      $('input[id=' + fieldName + ']').val(0);
    }
    changeCustomization();   

  });
//});




$(document).on('click', '.add_item', function(){

  $('.store_list_inner').addClass('disabled');
  var product_price = $(this).attr('data-price');  
  var product_id = $(this).attr('data-product_id');  
  UpdateCartQty(product_id, 0, product_price);
});


$(document).on('click', '.sub_item', function(){
  var product_price = $(this).attr('data-price');
  var product_id = $(this).attr('data-product_id');
  
  if($('#item_count_'+product_id).val()>1){
    $('.store_list_inner').addClass('disabled');
    UpdateCartQty(product_id, 1, product_price);
  }
  else{
    $('.remove_item').attr('data-product_id', product_id);
    $('.remove_item').attr('data-price', product_price);
    $('#confirmdeleteModal').modal('show');
  }
});

$(document).on('click', '.remove_item', function(){
  $('.store_list_inner').addClass('disabled');
  var product_price = $(this).attr('data-price');  
  var product_id = $(this).attr('data-product_id');  
  UpdateCartQty(product_id, 1, product_price);
});



$(document).on('click', '.add_to_cart', function(){

  $('.store_list_inner').addClass('disabled');
  var product_price = $(this).attr('data-price');
  var product_id = $(this).attr('data-product_id');
  UpdateCart(product_id, 0, product_price);
});


function UpdateCart(product_id, sub=0, product_price, custom_product=0){
  var segment = $('#url_param').val();
  $.ajax({
    url: site_url+'/product/'+product_id+'?sub='+sub+'&seg='+segment +'&product_custom_price=' +product_price+'&c_prod='+custom_product,
    type: 'get',
    async:false,    
    success:function(res){
      var data = JSON.parse(res);
      if(data.status){
        $('.cart_item').empty().html(data.html);
        $('.product_item').empty().html(data.product_html);
        $('.sideMenu').removeClass('open');
        $('#confirmdeleteModal').modal('hide');
        if(data.add == 1){
          Command: toastr['error'](data.msg);
        }else{
          Command: toastr['success'](data.msg);
        }
        setTimeout(function(){
          $('.store_list_inner').removeClass('disabled');

        },1500);
      }
      else {
          Command: toastr['error'](data.msg);
          setTimeout(function(){
            $('.store_list_inner').removeClass('disabled');

          },1500);

      }
    },
    error:function(e){
      Command: toastr['error'](data.msg);   
      setTimeout(function(){
        $('.store_list_inner').removeClass('disabled');

      },1500);
    }
  })
}


$(document).ready(function(){

$('#checkout_form').validate({
  rules:{
    'name':{
      required:true,
    },
    'address':{
      required:true,
    },
    'mobile_no':{
      required:true,
      minlength:8,
      maxlength:12,
      number:true
    },
    'email':{
      required:true,
      email:true,
    },
    'city':{
      required:true,
    },
    'state':{
      required:true,
    },
    'zipcode':{
      required:true,
      number:true,
      remote: {
        url: site_url+"/validation/checkzipcode",
        type: "post",
        data: {
           "_token": $('meta[name="csrf-token"]').attr("content"),
            zipcode: function () {
                return $("input[name='zipcode']").val();
            }
        },
        dataFilter: function (data) {
            if (data == "true") {
                return 'true';
            } else {
                return 'false';
            }
        }
      }
    }
  },
  messages:{
    'zipcode':{
      remote: 'Store does not provide delivery at this area',
    }
  }, 
  submitHandler:function (form){
    form.submit();
  }
})




});
$(document).ready(function(){
  $('#save_order').validate({
  rules:{
    'pay_method':{
      required:true,
    }
  },
  submitHandler:function(form){
    form.submit();
  }
})  
})





$(document).on('click', '.customise_item', function () {
  
    var prod_id = $(this).attr('data-id');
    $.ajax({
      url: site_url+'/get_customise/'+prod_id,
      type: 'get',    
      success:function(res){
        var data = JSON.parse(res);
        if(data.status ==1){
          $('.sideMenu').empty().html(data.html)
          $('.sideMenu').addClass('open');
          $('.selectitemSlider').slick({
            slidesToShow: 2,
            slidesToScroll: 2
          });
        }
        else {
            Command: toastr['error'](data.msg);
        }
      },
      error:function(e){
        Command: toastr['error']('Something went wrong');      }
    })
});

$(document).on('click', '.closeSideMenu', function (){
  $('.sideMenu').removeClass('open');
});


$(document).on('click', '.custom_add_to_cart', function (){
  var formData = $('#custom_popup,:hidden').serializeArray();
  var segment = $('#url_param').val();
  formData.push({name: "segment", value: segment});
  formData.push({name: "custom", value: "custom"});
  formData.push({name: "sub", value: "0"});
  $.ajax({
    url: site_url+'/product',
    type: 'post', 
    data: formData,
    success:function(res){
      var data = JSON.parse(res);
      if(data.status){

        $('.cart_item').empty().html(data.html);
        $('.product_item').empty().html(data.product_html);
        $('.sideMenu').removeClass('open');
        $('#confirmdeleteModal').modal('hide');
        
        Command: toastr['success'](data.msg);
      }
      else {
          Command: toastr['error'](data.msg);
      }
    },
    error:function(e){
      Command: toastr['error'](data.msg);      
    }
  })
}); 

$(document).on('change', '.sauce_master', function (){
  changeCustomization();
});

$(document).on('change', '.size_master', function (){
  changeCustomization();
});

$(document).on('change', '.crust_master', function (){
  changeCustomization();
});

$(document).on('change', '.topping_master', function (){
  changeCustomization();
});
$(document).on('click', '.extra_cheese', function (){
  changeCustomization();
});

function changeCustomization(){

  var sm_price = $('.sauce_master:checked').attr('data-price');
  var sizem_price = $('.size_master:checked').attr('data-price');
  var cm_price = $('.crust_master:checked').attr('data-price');
  var cm_price = $('.crust_master:checked').attr('data-price');  
  var ex_price = $('.extra_cheese:checked').attr('data-price');  
  var dips_price = 0;
  var topping_price = 0;

  $('.dip_price').each(function(){

    dips_price += parseFloat($(this).attr('data-price')) * parseFloat($(this).val());

  });
  $('.topping_master:checked').each(function(){

    topping_price += parseFloat($(this).val());

  });

  if( sm_price == null ){

    sm_price = 0;
  }
  if( ex_price == null ){

    ex_price = 0;
  }
  if( sizem_price == null ){

    sizem_price = 0;
  }
  if( cm_price == null ){

    cm_price = 0;
  }

  var product_price = $('#product_price').val();
  var price = parseFloat( ex_price) +parseFloat( sm_price) +  parseFloat(sizem_price) + parseFloat(cm_price) + parseFloat(product_price) + parseFloat(dips_price) + parseFloat(topping_price); 

  $('.span_price').text(price.toFixed(2));
  $('.product_custom_price').val(price.toFixed(2));
}


$(document).on('click', '.sizem_id', function(){
  var id = $(this).attr('data-id');
  $('.show_cheese').show();
  $('.crow').hide();
  $('#cheese_row_'+id).show();
});

$(document).on('click', '.add_cart_product', function(){
  var id = $(this).attr('data-id');
  $('.show_cheese').show();
  $('.crow').hide();
  $('#cheese_row_'+id).show();
});






function UpdateCartQty(product_id, sub=0, product_price){
  /*var id = $(this).attr('data-product_id');
  var price = $(this).attr('data-price');
  var sub = $(this).attr('data-sub');*/
  var seg = $('#url_param').val();
  $.ajax({
    url: site_url+'/increament_decrement',
    type: 'get', 
    async:false,
    data: {id:product_id, price:product_price, sub:sub, seg:seg},
    success:function(res){
      var data = JSON.parse(res);
      if(data.status){

        $('.cart_item').empty().html(data.html);
        $('.product_item').empty().html(data.product_html);
        $('#confirmdeleteModal').modal('hide');
        if(data.add == 1){
          Command: toastr['error'](data.msg);
        }else{
          Command: toastr['success'](data.msg);
        }
        setTimeout(function(){
          $('.store_list_inner').removeClass('disabled');

        },1500);
      }
      else {
          Command: toastr['error'](data.msg);
          setTimeout(function(){
            $('.store_list_inner').removeClass('disabled');

          },1500);
      }
    },
    error:function(e){
      Command: toastr['error'](e.msg);    

      setTimeout(function(){
        $('.store_list_inner').removeClass('disabled');

      },1500);  
    }
  })
}; 


$(document).on('click', ".subcategory", function() {
  var id = $(this).attr('href');

    $('html,body').animate({
        scrollTop: $(id).offset().top},
        'slow');
});

$(document).on('click', '.read_less', function(){
  id = $(this).attr('id');
  if($('.short_content_'+id).is(':visible')){

    $('.content_'+id).show();
    $('.short_content_'+id).hide();
  }else{
    $('.short_content_'+id).show();
    $('.content_'+id).hide();
  }
});


