vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		owner: [],
		msg_user: [],
	},
	mounted(){
		this.onSubmitSearch();
		
	},
	methods: {
		onSubmitSearch() {
			var x = this;
			var cu = $('#Staff_search').attr('action');
			$.post(cu,$('#Staff_search').serializeArray(),function(data,status,xhr){
				try {
					x.owner = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},
		active(index){
			
			var crt= $("#csrf").val();
			var id = this.owner[index]['EMAIL'];
			
			var active = this.owner[index]['ACTIVE'] == 1;
			var lib = this.owner;
			$.post(URL+"mail_list/active",{csrf:crt,id:id,current:active},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم حذف العميل");
						lib.splice(index,1);
					}
				}
				catch(err) {
					alert(err.message+"\n\n\n"+data);
				}
			})
		},
		change_msg(){
			var x = $("#msgs").prop('checked');
			$('.msgs').prop('checked',x);
		},
		message(){
			var no = $('.msgs:checked').length;
			if(no == 0)
			{
				alert('الرجاء اختيار  شخص او اشخاص اولاً');
				return;
			}
			this.msg_user.splice(0)
			x = this;
			$i = 0;
			
			$( ".msgs:checked" ).each(function( index ) {
				Vue.set(x.msg_user, $i, $(this).data('id'));
				$i += 1;
			});
			
			$('#msg_staff').modal('show');
		},
		send_msg(){
			var msg_form = $("#msg_staff_form");
			msg_form.find(".err_notification").addClass(E_HIDE);
			$MSG 		= msg_form.find('.form_msg').html();
			
			msg_form.ajaxSubmit({ 
				target:   '#targetLayer', 
				beforeSubmit: function() {
					
					//form_progress(0);
				},
				uploadProgress: function (event, position, total, percentComplete){	
					//form_progress(percentComplete);
				},
				success:function (){
					//form_progress(100);
					var data = $('#targetLayer').html();
					data = data.split(/\r?\n/);
					data = data[data.length - 1];
					try {
						var obj = JSON.parse(data);
						if ("Error" in obj)
						{
							error_handler(data);
						}else if("total" in obj && obj.total != 0)
						{
							alert("تم ارسال بريد إلكتروني ل "+obj.email+" موظف \n");
							close_form_dialog($('#msg_staff_form'));
							x.msg_user.splice(0);
							$('#msg_staff').modal('hide');
						}else
						{
							alert(data);
						}
					}
					catch(err) {
						alert(err.message+"\n"+$('#targetLayer').html());
					}
				},
				error:function(response,status,xhr){
					alert("error "+JSON.stringify(response));
				},
				resetForm: false
			});
			
		}
		
	}
});


function modal_element($type,$id)
{
	switch($type)
	{
		case "new":
			new_modal_element();
		break;
		case "upd":
			upd_modal_element();
		break;
		case "del":
			del_modal_element();
		break;
		
	}
}

function new_modal_element($id = 0)
{
	
	var obj = {};
	obj.ID 			= $id;
	obj.NAME 		= $("#new_name").val();
	obj.PHONE 		= $("#new_phone").val();
	obj.NAT_NO 		= $("#new_nat_no").val();
	obj.ADDRESS 	= "";
	obj.EMAIL 		= $("#new_email").val();
	obj.LINK		= URL+"owner/details/".$id;
	obj.ACTIVE		= 1;
	obj.LANDS		= 0;
	obj.COMM		= "";
	
	vm_staff.owner.push(obj);
}

function upd_modal_element()
{
	//upd_index
	var index = $("#upd_index").val();
	
	vm_staff.owner[index].NAME 		= $("#upd_name").val();
	vm_staff.owner[index].PHONE 	= $("#upd_phone").val();
	vm_staff.owner[index].EMAIL 	= $("#upd_email").val();
	vm_staff.owner[index].NAT_NO 	= $("#upd_nat_no").val();
	
}



//////////////////////////////////////////////////////MSG owner
$(document).on('change','#msgs', function (e) 
{
	var x = $(this).prop('checked');
	$('.msgs').prop('checked',x);
	
})

//////////////////////////////////////////////////////End MSG owner





