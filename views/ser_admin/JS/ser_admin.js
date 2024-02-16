vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		cont_type	: js_cont_type,
		reg_type	: js_reg_type,
		ser_type	: js_ser_type,
		cont_status	: js_cont_status,
		services: [],
	},
	mounted(){
		this.onSubmitSearch();
		
	},
	methods: {
		onSubmitSearch() {
			var x = this;
			var cu = $('#ser_search').attr('action');
			$.post(cu,$('#ser_search').serializeArray(),function(data,status,xhr){
				try {
					x.services = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},
	}
});


