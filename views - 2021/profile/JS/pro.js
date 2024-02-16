vm_staff = new Vue({
	el: '#staff_info',
	data: {
		info: js_info,
    },
	mounted(){
		
	},
	methods: {
		onSubmitupd(){
			var vu = this;
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
			
		},
		del_img(index)
		{
			var conf = confirm("هل انت متأكد من مسح الملف :"+this.info.PROFILE.FILES[index].NAME);
			if(conf)
			{
				var csr = $("#csrf").val();
				var th = this;
				$.post(URL+"profile/del_img",{csrf:csr,file:this.info.PROFILE.FILES[index].NAME},function(data,status,xhr){
					try {
						alert(data);
						if(data == "تم حذف الملف")
						{
							var m_ind = $("#upd_index").val();
							th.info.PROFILE.FILES.splice(index,1)
						}
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				});
			}
		}
		
	}
});
