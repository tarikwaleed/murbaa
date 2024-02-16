vm_cobon = new Vue({
	el: '#types_settings',
	data: {
		types: js_types,
		pays: js_pay,
		upd_cobon:{},
		cobon_list:[],
	},
	mounted(){
		this.onSubmitSearch();
	},
	methods: {
		onSubmitSearch() {
			var x = this;
			var cu = $('#cobon_search').attr('action');
			$.post(cu,$('#cobon_search').serializeArray(),function(data,status,xhr){
				try {
					x.cobon_list = data;
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},update_type(index){
			this.upd_cobon = this.cobon_list[index];
			this.upd_cobon.INDEX = index;
		},
		active(index){
			var crt= $("#csrf").val();
			var id = this.cobon_list[index]['ID'];
			
			var active = this.cobon_list[index]['ACTIVE'] == 1;
			var lib = this.cobon_list;
			$.post(URL+"cobon/active",{csrf:crt,id:id,current:active},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم تنشيط / تجميد الكبون ");
						lib[index]['ACTIVE'] = (lib[index]['ACTIVE'] == 1)?0:1;
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
	vm_cobon.onSubmitSearch();
}
