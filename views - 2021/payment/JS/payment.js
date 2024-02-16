vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		bills: js_bills,
		admin: js_admin,
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
					x.reports = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},
		
	}
});

