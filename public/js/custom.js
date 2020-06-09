
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
/*$(document).ready(function (e) {
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
*/
$(document).ready(function (e) {
  $(window).scroll(function () {
    if ($(this).scrollTop() > 30) {
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

  //$('.store_list_inner').addClass('disabled');
  var product_price = $(this).attr('data-price');  
  var product_id = $(this).attr('data-product_id');  
  var scroll_id = $(this).parents('.cartbox__inner').attr('id');  
  UpdateCartQty(scroll_id, product_id, 0, product_price);
});


$(document).on('click', '.sub_item', function(){
  var product_price = $(this).attr('data-price');
  var product_id = $(this).attr('data-product_id');
  var scroll_id = $(this).parents('.cartbox__inner').attr('id');  
  
  //if($('#item_count_'+product_id).val()>1){
  if($('#item_count_'+product_id).val()>=1){
    //$('.store_list_inner').addClass('disabled');
    UpdateCartQty(scroll_id,product_id, 1, product_price);
  }
  /*else{
    $('.remove_item').attr('data-product_id', product_id);
    $('.remove_item').attr('data-price', product_price);
    $('#confirmdeleteModal').modal('show');
  }*/
});

$(document).on('click', '.remove_item', function(){
  //$('.store_list_inner').addClass('disabled');
  var product_price = $(this).attr('data-price');  
  var product_id = $(this).attr('data-product_id');  
  var scroll_id = $(this).parents('.cartbox__inner').attr('id');  

  UpdateCartQty(scroll_id, product_id, 1, product_price);
});



$(document).on('click', '.add_to_cart', function(){

  //$('.store_list_inner').addClass('disabled');
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
        $('.cart_count').empty().html(data.cart_count);
        $(".cartWrapper").scrollTop($('.cartWrapper')[0].scrollHeight);
        $('.sideMenu').removeClass('open');
        $('#confirmdeleteModal').modal('hide');
        /*if(data.add == 1){
          Command: toastr['error'](data.msg);
        }else{
          Command: toastr['success'](data.msg);
        }
        setTimeout(function(){
          $('.store_list_inner').removeClass('disabled');

        },1500);*/
      }
      else {
          Command: toastr['error'](data.msg);
          /*setTimeout(function(){
            $('.store_list_inner').removeClass('disabled');

          },1500);*/

      }
    },
    error:function(e){
      Command: toastr['error'](data.msg);   
      /*setTimeout(function(){
        $('.store_list_inner').removeClass('disabled');

      },1500);*/
    }
  })
}


$(document).ready(function(){

  $('#checkout_form').validate({
    rules:{
      'pay_method':{
        required:true,
      },
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
        //number:true,
        remote: {
          url: site_url+"/validation/checkzipcode",
          type: "post",
          data: {
             "_token": $('meta[name="csrf-token"]').attr("content"),
              zipcode: function () {
                  return $("input[name='zipcode']").val();
              }
          },
          dataFilter: function (res) {
            console.log(res);
            data =JSON.parse(res);
            console.log(data.status);
              if (data.status == "true") {
                  $('.order_cart_summary').empty().html(data.html);
                  return 'true';
              } else {
                  $('.order_cart_summary').empty().html(data.html);
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



//Single normal product's customise popup on button click
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

//close customise popup on normal product's customise  
$(document).on('click', '.closeSideMenu', function (){
  $('.sideMenu').removeClass('open');
});

//Show pizza selection popup on special product
$(document).on('click', '.order_now', function (){
    var id = $(this).attr('data-product_id');
    var selectedLength = $('.special_prod_add'+id+':checked').length;
    if(selectedLength>0){
      var selectedsize = $('.special_prod_add'+id+':checked').val();
      var price = $('.special_prod_add'+id+':checked').attr('data-price');
      $('#combo_error'+id).text('');
      $('.customise_special_prod').attr('data-size', selectedsize);
      $('.add_combo').attr('data-price', price);
      $('.customise_special_prod').attr('data-price', price);
      var qty = $(this).attr('data-product_qty');
      $('#add_special_product'+id).modal('show'); 
      $.ajax({
        url: site_url+'/reset_cartcombo',
        type: 'post',
        data:{
          "_token": $('meta[name="csrf-token"]').attr("content")
        },
        success:function(res){  
          console.log(res);
        },error:function(e){

        }
      });
    }else
    {
      $('#combo_error'+id).text('Please select size');
    }
});

//customise on combo or special deal
$(document).on('click', '.customise_special_prod', function (){
    var key = $(this).attr('data-key');
    var prod_id = $(this).attr('data-id');
    var size = $(this).attr('data-size');
    var price = $(this).attr('data-price');
    var pizzaid = $('#customize_select'+prod_id+'_'+key).val();
    var selected = $('#customize_select'+prod_id+'_'+key).val();

    if(selected!==''){
      $('#customize_select_error'+prod_id+'_'+key).text('');
      $.ajax({
        url: site_url+'/get_customise_combo',
        type: 'post',

        data:{
          "_token": $('meta[name="csrf-token"]').attr("content"),
          pizzaid:pizzaid, 
          prodid:prod_id, 
          size:size, 
          price:price,
          key:key
        },
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
      $('#add_special_product'+prod_id).modal('hide');   
    }else
    {
      $('#customize_select_error'+prod_id+'_'+key).text('Please select Pizza');
    }
});

//Save customise options On change of products that doesn't need customisation on combo (double/triple) product category
$(document).on('change', '.customize_select_option', function(){
  var required = $(this).find(':selected').data('required');
    var key = $(this).attr('data-key');
    $.ajax({
      url: site_url+'/comboProductSelectionDetails',
      type: 'post', 
      data: {product_id: $(this).find(':selected').val(), "_token": $('meta[name="csrf-token"]').attr("content"), 'custom':'custom', 'required_customise':required, 'key':key},
      success:function(res){
        var data = JSON.parse(res);
        if(data.status){
          //$('#add_special_product'+comboprodid).modal('show');
          //$('.sideMenu').removeClass('open');  

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

//Save customise option on special product
$(document).on('click', '.custom_save', function(){
  var pizzaid = $(this).attr('data-product_id');
  var key = $(this).attr('data-key');
  var comboprodid = $(this).attr('data-combo_product_id');
  var formData = $('#custom_popup,:hidden').serializeArray();
  var segment = $('#url_param').val();
  formData.push({name: "segment", value: segment});
  formData.push({name: "custom", value: "custom"});
  formData.push({name: "sub", value: "0"});
  formData.push({name: "key", value: key});
  $.ajax({
    url: site_url+'/comboproduct',
    type: 'post', 
    data: formData,
    success:function(res){
      var data = JSON.parse(res);
      if(data.status){
        $('#add_special_product'+comboprodid).modal('show');
        $('.sideMenu').removeClass('open');  

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

//add special item customise add to cart button
$(document).on('click', '.add_combo', function (){
  
  var product_id = $(this).attr('data-product_id');
  var selectlength = $('#select_combo_pizza'+product_id).find('.customize_select_option').length;
  var countProduct = 0;
  var out = true;
  $('#select_combo_pizza'+product_id).find('.customize_select_option').each(function(value, index){

    var pizza = $(this).data('pizza');
    var selectedProdId = $(this).find(':selected').val();
    if(selectedProdId==''|| selectedProdId=='undefined'){
      $('#customize_addcart_error'+product_id).text('Please select '+pizza+' pizza');  
      out =false;
      return false;      
    }
    var required = $(this).find(':selected').data('required')
    if(required=='on'){
      out =false;
      $.ajax({
        url: site_url+'/CheckProductCutomise',
        type: 'post', 
        async:false,
        data: {
          "_token": $('meta[name="csrf-token"]').attr("content"),
          selectedProdId:selectedProdId,
          pizza:pizza
        },
        success:function(data){
          
          //var data = JSON.parse(res);
          if(data.status){
            out =true;
            $('#customize_addcart_error'+product_id).text('');
          }
          else {
            $('#customize_addcart_error'+product_id).text(data.msg);
            out =false;
            return false;  
                
          }
          return out;
        },
        error:function(e){
          Command: toastr['error'](e); 
            return false;      

        }
      })
      return out;
     // $('#customize_addcart_error'+product_id).text('Please choose toppings for your '+pizza+' pizza');  
    }
  });
  var price = $(this).attr('data-price');
  if(out){

    $.ajax({
      url: site_url+'/comboaddtocart',
      type: 'post', 
      data: {
        "_token": $('meta[name="csrf-token"]').attr("content"),
        product_id:product_id,
        selectlength:selectlength,
        price:price,
        countProduct:countProduct
      },
      success:function(res){
        var data = JSON.parse(res);

        if(data.status){
          $('#customize_addcart_error'+product_id).text('');
          //$('#add_special_product'+product_id).modal('hide');
          $('.sideMenu').removeClass('open');
          $('.cart_item').empty().html(data.html);
          $('.product_item').empty().html(data.product_html);
          $('.cart_count').empty().html(data.cart_count);
          //Command: toastr['success'](data.msg);
          window.location.reload();
          $('#add_special_product'+product_id+' button.close').trigger('click');
         /* $('.modal.in').modal('hide');
          $('.modal-backdrop').remove();*/
        }
        else {
          $('#customize_addcart_error'+product_id).text(data.msg);
            //Command: toastr['error'](data.msg);
        }
      },
      error:function(e){
        Command: toastr['error'](data.msg);      
      }
    })
  }   
});





//add item to cart on normal product customise add to cart button
$(document).on('click', '.custom_add_to_cart', function (){
  var input = $('.topping_wing_master:checked').length;
  if(typeof input=='undefined' || input>0) {
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
          $('.cart_count').empty().html(data.cart_count);
          $(".cartWrapper").scrollTop($('.cartWrapper')[0].scrollHeight);
        
          $('.sideMenu').removeClass('open');
          $('#confirmdeleteModal').modal('hide');
          
          //Command: toastr['success'](data.msg);
        }
        else {
            Command: toastr['error'](data.msg);
        }
      },
      error:function(e){
        Command: toastr['error'](data.msg);      
      }
    })
  }else{
    var msg='';
    var topping_from = $('.topping_name').val();
    if(topping_from=='topping_tops'){
      msg = 'Please choose toppings';
    }else if(topping_from=='topping_wing_flavour'){
      msg = 'Please choose wing flavours';
    }else if(topping_from=='topping_donair_shawarma_mediterranean'){
      msg = 'Please choose toppings and sauces';
    }else if(topping_from=='topping_dips'){
      msg = 'Please choose dips';
    }else if(topping_from=='topping_pizza'){
      msg = 'Please choose pizza customisation';
    }
    
    $('.topping_error').text(msg);
    //confirm(msg);
    return false;
  }
}); 

//calculate price on change of custom topping and dips
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
$(document).on('click', '.topping_wing_master', function (){
  changeCustomization();
});

function changeCustomization(){

  var sm_price = $('.sauce_master:checked').attr('data-price');
  var sizem_price = $('.size_master:checked').attr('data-price');
  var cm_price = $('.crust_master:checked').attr('data-price');
  var cm_price = $('.crust_master:checked').attr('data-price');  
  var ex_price = $('.extra_cheese:checked').attr('data-price');  
  var top_price = $('.topping_wing_master:checked').attr('data-price');  
  var dips_price = 0;
  var topping_price = 0;

  $('.dip_price').each(function(){

    dips_price += parseFloat($(this).attr('data-price')) * parseFloat($(this).val());

  });
  $('.topping_master:checked').each(function(){

    topping_price += parseFloat($(this).val());

  });

  if( top_price == null ){

    top_price = 0;
  }
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
  var price = parseFloat( ex_price) +parseFloat( sm_price) +  parseFloat(sizem_price) + parseFloat(cm_price) + parseFloat(product_price) + parseFloat(dips_price) + parseFloat(topping_price)+ parseFloat(top_price); 

  $('.span_price').text('$'+price.toFixed(2));
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

/*//Hide customise popup on click on body
$(document).on('click','#mainContent', function(){
  $('.sideMenu').removeClass('open');
});*/




function UpdateCartQty(scroll_id, product_id, sub=0, product_price){

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
         $('.cart_count').empty().html(data.cart_count);
         console.log(scroll_id);
       /*  $('.cartWrapper').animate({
            scrollTop: $('#'+scroll_id).offset().top - $('div').offset().top + $('div').scrollTop();
        });​*/
         $('.cartWrapper').scrollTop(
            $('#'+scroll_id).offset().top - $('.cartWrapper').offset().top + $('.cartWrapper').scrollTop()-10
        );
        $('#confirmdeleteModal').modal('hide');

        /*if(data.add == 1){
          Command: toastr['error'](data.msg);
        }else{
          Command: toastr['success'](data.msg);
        }
        setTimeout(function(){
          $('.store_list_inner').removeClass('disabled');

        },1500);*/
      }
      else {
          Command: toastr['error'](data.msg);
          /*setTimeout(function(){
            $('.store_list_inner').removeClass('disabled');

          },1500);*/
      }
    },
    error:function(e){
      Command: toastr['error'](e.msg);    

      /*setTimeout(function(){
        $('.store_list_inner').removeClass('disabled');

      },1500); */ 
    }
  })
}; 

//Read more and read less in product list
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

//read more and read less in cart
$(document).on('click', '.read_less_check', function(){
  id = $(this).attr('id');
  if($('.short_content_check_'+id).is(':visible')){

    $('.content_check_'+id).show();
    $('.short_content_check_'+id).hide();
  }else{
    $('.short_content_check_'+id).show();
    $('.content_check_'+id).hide();
  }
});

//Apply coupon code
$(document).on('click', '#applyCoupon', function(){
  var coupon = $('#coupon_code').val();
  $.ajax({
    url: site_url+"/checkcoupon",
    type: "post",
    data: {
      "_token": $('meta[name="csrf-token"]').attr("content"),
      'coupon':coupon,
    },
    success: function (data) {

      if(data.status == '200'){
        $('.cart_item').empty().html(data.html);
        Command: toastr['success'](data.message);
      }else{
        Command: toastr['error'](data.message);
      }
    },error: function (error){
      Command: toastr['error'](data.msg);
    }
  });
})

//add to cart on sides product
$(document).on('click', '.order_multiple', function(){
  $inputlength = $('.sides_prod_add:checked').length;
  if($inputlength>0){
    $('#sides_error').text('');  
    var values = [];
    $(".sides_prod_add:checked").each(function() {
       values.push( {
         id: $( this ).val()
       });
    });      
    sidesAddToCart(values);
  }else{
    $('#sides_error').text('Please choose atleast one product');
  }
});


function sidesAddToCart(values){
  //var segment = $('#url_param').val();
  $.ajax({
    url: site_url+'/sidesAddToCart',
    type: 'post',
    data:{
      "_token": $('meta[name="csrf-token"]').attr("content"),
      values:values,
      sub:0       
    },
    async:false,    
    success:function(res){
      var data = JSON.parse(res);
      
      console.log("res", res);
      if(data.status){
        $('.cart_item').empty().html(data.html);
        $('.product_item').empty().html(data.product_html);
        $('.cart_count').empty().html(data.cart_count);
        $(".cartWrapper").scrollTop($('.cartWrapper')[0].scrollHeight);
        
        $('.sideMenu').removeClass('open');
        $('#confirmdeleteModal').modal('hide');
        /*if(data.add == 1){
          Command: toastr['error'](data.msg);
        }else{
          Command: toastr['success'](data.msg);
        }
        setTimeout(function(){
          $('.store_list_inner').removeClass('disabled');

        },1500);*/
      }
      else {
          Command: toastr['error'](data.msg);
          /*setTimeout(function(){
            $('.store_list_inner').removeClass('disabled');

          },1500);*/
      }
    },
    error:function(e){
      Command: toastr['error'](data.msg);   
      /*setTimeout(function(){
        $('.store_list_inner').removeClass('disabled');

      },1500);*/
    }
  })
}

// $(document).ready(function(){
//   $('.subcategory').on('click', function(event) {
//       $(this).parent().find('a').removeClass('active');
//       $(this).addClass('active');
//   });


  
//   $(window).on('scroll', function() {
//       $('.sub_cat_list').each(function() {
//           if($(window).scrollTop() >= $(this).offset().top-$('.is-sticky').height()) {
//               $('.subcategory').removeClass('active');
//               var id = $(this).attr('id');
//               $('.subcategory[href="#'+ id +'"]').addClass('active');
//           }
//           if ($(".menu-nav").addClass('is-sticky')) {
   
//             $('.navbar-me').css('margin-top','-85px');
//           }
//           else if($(".menu-nav").removeClass('is-sticky')){
//             $('.navbar-me').css('margin-top','0px');
//           }
//       });
//   });

// });

$(document).ready(function(){
  $('#add_contactus_form').validate({
    rules:{
      first_name: 
      {
        'required':true
      },
      last_name: 
      {
        'required':true
      },
      email: 
      {
        'required':true,
        'email':true
      },
      phone_number: 
      {
        'required':true,
        'number':true,
        'minlength':9,
        'maxlength':13,
      },
      company_name: 
      {
        'required':true
      },
    },messages:{
      'first_name':{
        'required':'First Name field is required.',
      },
      'last_name':{
        'required':'Last Name field is required.'
      },
      'email':{
        'email': 'Please enter a valid email address.',
        'required':'Email field is required.'
      },
      'company_name':{
        'required':'Company Name field is required.',
      },
      'phone_number':{
        'required' :'Contact Number field is required.',
        'number' :'Please enter a valid contact number.',
        'minlength' :'Please enter a valid contact number.',
        'maxlength' :'Please enter a valid contact number.',
      }
    },submitHandler:function(form){
      form.submit();
    }
  })
});



