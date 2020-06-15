setInterval(function(){ 				
	$.ajax({
		url:base_url+'/store/orders/latest',
		type:'get',
		success:function(response){

			console.log("response", response.success);
			if(response.success){

  				var x = document.getElementById("myAudio");
  			
				x.autoplay = true;
  				x.load();
			}
			$('.orderTable').DataTable().ajax.reload();
		},
		error:function(e){

			console.log("e", e);
		}
	})


}, 10000);

