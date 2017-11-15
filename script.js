jQuery(document).ready(function($){
	$("body").on("click",".delete-notification",function(){
			var r=confirm("Are you sure?");
			var dis=$(this);
			if(r){
				var n_id=dis.data('id');
			    var data = {
					'action': 'delete_edd_notification_nid',
					'id': n_id      // We pass php values differently!
				};
				// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				$.post(ajax_object.ajax_url, data, function(response) {
					if(response==true){
						dis.closest("tr").remove();
						$(".notifier-widget .success-result").fadeIn('slow').fadeOut('slow');
					}else{
						alert("Something Wrong! Try again.")
						return false;
					}
				});
			}else{
				return false;
			}


	});
    
});