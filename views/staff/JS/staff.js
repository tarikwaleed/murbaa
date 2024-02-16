vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		staff		: [],
		upd_staff	: {},
		msg_user	: [],
		per_list	: js_per_list,
		conf		: js_conf_list,
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
					vm_staff.staff = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
			
		},
		update_staff(index){
			this.upd_staff = this.staff[index];
			this.upd_staff.INDEX = index;
		},
		active(index){
			var crt= $("#csrf").val();
			var id = this.staff[index]['ID'];
				
			var active = this.staff[index]['ACTIVE'] == 1;
			var lib = this.staff;
			$.post(URL+"staff/active",{csrf:crt,id:id,current:active,oth_user:0},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم تنشيط/تجميد المستخدم ");
						lib[index]['ACTIVE'] = (lib[index]['ACTIVE'] == 1)?0:1;
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
			
			$( ".msgs" ).each(function( index ) {
				Vue.set(x.msg_user, $i, $(this).data('id'));
				$i += 1;
			});
			
			$('#msg_staff').modal('show');
		},
		send_msg(){
			var postData = $('#msg_staff_form').serializeArray();
			x = this;
			$.post(URL+"staff/msg_staff",postData,function(data,status,xhr)
			{
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						error_handler(data);
					}else if("total" in obj && obj.total != 0)
					{
						alert("تم ارسال الرسالة ل "+obj.sms+" موظف \n تم ارسال بريد إلكتروني ل "+obj.email+" موظف \n");
						close_form_dialog($('#msg_staff_form'));
						x.msg_user.splice(0);
						$('#msg_staff').modal('hide');
					}else
					{
						alert(data);
					}
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			})
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
		case "freez":
			vm_staff.onSubmitSearch();
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
	obj.PER 		= $("#new_permission").val();
	obj.ADDRESS 	= "";
	obj.EMAIL 		= $("#new_email").val();
	obj.ACTIVE 		= 1;
	obj.LINK		= URL+"staff/details/".$id;
	obj.IMG			= URL+"public/IMG/user/logo1.png";
	obj.ACTIVE		= 1;
	obj.COMM		= "";
	
	vm_staff.staff.push(obj);
}

function upd_modal_element()
{
	//upd_index
	var index = vm_staff.upd_staff.INDEX;
	
	vm_staff.staff[index].NAME 		= $("#upd_name").val();
	vm_staff.staff[index].PHONE 	= $("#upd_phone").val();
	vm_staff.staff[index].NAT_NO 	= $("#upd_nat_no").val();
	vm_staff.staff[index].PER 		= $("#upd_permission").val();
	vm_staff.staff[index].EMAIL 	= $("#upd_email").val();
	
}

//////////////////////////////////////////////////////MSG Staff
$(document).on('change','#msgs', function (e) 
{
	var x = $(this).prop('checked');
	$('.msgs').prop('checked',x);
	
})

//////////////////////////////////////////////////////End MSG Staff

