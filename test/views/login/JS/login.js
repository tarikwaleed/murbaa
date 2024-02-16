var curr_url;
//JS For dep Actions
$(document).ready(function(){
	if($("#error_msg").length)
	{
		var m = $('#error_msg').html();
		if(m != "")
		{
			alert(m);
			//$('#err_'+m).modal('show');
		}
	}
})

//forget submit
$(document).on('click','#forget_send', function (e) 
{
	var postData = $('#forget_form').serializeArray();
	
	$.post(URL+"login/forget_request",postData)
    .done(function(data,status,xhr)
	{
        $('#forget_req').modal('show');
        /*try {
			var obj = JSON.parse(data);
			
			if ("Error" in obj)
			{
				error_handler(data);
			}else
			{
				$('#forget_req').modal('show');
			}
		}
		catch(err) {
			alert(err.message+"\n"+data);
        }*/
			
	});
	
})

//reset pass submit
$(document).on('click','#reset_send', function (e) 
{
	var postData = $('#reset_form').serializeArray();
	$.post(URL+"login/update_res_password",postData,function(data,status,xhr)
	{
		try {
			var obj = JSON.parse(data);
			
			if ("Error" in obj)
			{
				error_handler(data);
			}else
			{
				$('#reset_req_modal').modal('show');
			}
		}
		catch(err) {
			alert(err.message+"\n"+data);
		}
	})
})

$('#reset_req_modal').on('hide.bs.modal', function () {
	location.replace(URL+'login');
})
