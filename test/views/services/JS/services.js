vm_service = new Vue({
	el: '.vue_area_div',
	data: {
		config		: js_config,
		cont_type	: js_cont_type,
		reg_type	: js_reg_type,
		ser_type	: js_ser_type,
		service_list:[],
		user_list	:[],
		upd_service	:{},
		ser_sel		:{},
		cont_status	: js_cont_status,
		//types: js_types,
		//pays: js_pay,
		//
		//
	},
	mounted(){
		this.onSubmitSearch();
	},
	methods: {
		onSubmitSearch() {
			if($('#service_search').length)
			{
				var cu = $('#service_search').attr('action');
				$.post(cu,$('#service_search').serializeArray(),function(data,status,xhr){
					try {
						vm_service.service_list = data;
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				},'JSON');
			}else if($('#user_search').length)
			{
				var cu = $('#user_search').attr('action');
				$.post(cu,$('#user_search').serializeArray(),function(data,status,xhr){
					try {
						vm_service.user_list = data;
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				},'JSON');
			}
		},update_service(index){
			this.upd_service = this.service_list[index];
		},sel_user(index){
			this.ser_sel = this.user_list[index];
		},
		active(index){
			var crt= $("#csrf").val();
			var id = this.service_list[index]['ID'];
			
			var active = this.service_list[index]['STATUS'];
			var lib = this.service_list;
			$.post(URL+"services/active",{csrf:crt,id:id,current:active},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم تنشيط / تجميد الطلب");
						lib[index]['STATUS'] = (lib[index]['STATUS'] == "NEW")?"FREEZ":"NEW";
					}
				}
				catch(err) {
					alert(err.message+"\n\n\n"+data);
				}
			})
		},
	}
});


function modal_element($type,$id)
{
	switch($type)
	{
		case 'new_service':
			window.location.href = URL+"services/details/"+$id;
		break;
		default:
			vm_service.onSubmitSearch();
	}
	
}
