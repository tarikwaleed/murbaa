vm_service = new Vue({
	el: '.vue_area_div',
	data: {
		config		: js_config,
		cont_type	: js_cont_type,
		service_list:[],
		upd_service	:{},
        tes:0,
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
			var x = this;
			var cu = $('#service_search').attr('action');
			$.post(cu,$('#service_search').serializeArray(),function(data,status,xhr){
				try {
					x.service_list = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
                }
			},'JSON');
		},update_service(index){
			this.upd_service = this.service_list[index];
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
	vm_service.onSubmitSearch();
}
