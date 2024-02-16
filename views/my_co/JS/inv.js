vm_staff = new Vue({
	el: '#staff_info',
	data: {
		info	: js_info,
		company	: js_company,
    },
	mounted(){
		
	},
	methods: {
		onSubmit(){
			
			
		},
	}
});

$(document).on('change','#msgs', function (e) 
{
	var x = $(this).prop('checked');
	$('.msgs').prop('checked',x);
	
})
$(document).on('submit','#staff_form', function (e) 
{
	var theform = $("#staff_form");
	theform.find(".err_notification").addClass(E_HIDE);
				
	theform.ajaxSubmit({ 
		target:   '#targetLayer', 
		beforeSubmit: function() {
			form_progress(0);
		},
		uploadProgress: function (event, position, total, percentComplete){	
			form_progress(percentComplete);
		},
		success:function (){
			form_progress(100);
			var x = $('#targetLayer').html();
			try {
				var obj = JSON.parse(x);
				if ("Error" in obj)
				{
					error_handler(x);
				}else if("id" in obj && obj.id != 0)
				{
					alert("تم تحديث البيانات")	
				}else
				{
					alert(x);
				}
			}
			catch(err) {
				alert(err.message+"\n"+x);
			}
		},
		error:function(response,status,xhr){
			alert("error "+response);
		},
		resetForm: false
	});
	return false;	
})
