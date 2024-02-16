vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		bills: js_bills,
		services: js_services,
		admin: js_admin,
		co_id: js_co_id,
		upd_ser: [],
		upd_service: [],
	},
	mounted(){
		//this.onSubmitSearch();
		
	},
	methods: {
		onSubmitSearch() {
			var x = this;
			var cu = $('#Staff_search').attr('action');
			$.post(cu,$('#Staff_search').serializeArray(),function(data,status,xhr){
				try {
					x.reports = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},
		update_service(i,index)
		{
			this.upd_ser = this.services[index].data[i];
		},
		update_admin_service(index)
		{
			this.upd_ser = this.services[index];
		}
	}
});


function modal_element($type,$id)
{
	location.reload();
}
