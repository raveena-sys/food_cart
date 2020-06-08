setInterval(function(){ 				
	$.ajax({
		url:base_url+'/store/orders/latest',
		type:'get',
		success:function(response){

			console.log("response", response.success);
			$('.orderTable').DataTable().ajax.reload();
			if(response.success){

  				var x = document.getElementById("myAudio");
  			
				x.autoplay = true;
  				x.load();
			}
		},
		error:function(e){

			console.log("e", e);
		}
	})


}, 10000);

