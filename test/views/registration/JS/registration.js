vm_staff = new Vue({
	el: '#staff_settings',
	data: {
		real_requests: js_real_requests,
		ser_requests: js_ser_requests,
		upd_real	:{},
		upd_ser		:{},
		curr_real_index:0,
		curr_ser_index:0,
	},
	mounted(){
		
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
		accept(index){
			this.upd_real = this.real_requests[index];
			this.curr_real_index = index;
		},
		ser_accept(index){
			this.upd_ser = this.ser_requests[index];
			this.curr_ser_index = index;
			
		}
	}
});

//set min Date to tommorow
var min_date = new Date();
min_date.setDate(min_date.getDate() + 1);
var dd = min_date.getDate();
var mm = min_date.getMonth(); //January is 0!
var yyyy = min_date.getFullYear();
if (dd < 10) {
	dd = '0' + dd;
}
if (mm < 10) {
	mm = '0' + mm;
}
var today = yyyy + '-' + mm + '-' + dd;
$(".new_date").val(today);
$(".new_date").attr('min',today);
//_________________________________________________

function modal_element($type,$id)
{
	switch($type)
	{
		case "new":
			vm_staff.real_requests.splice(vm_staff.curr_real_index,1);
		break;
		case "ser_new":
			vm_staff.ser_requests.splice(vm_staff.curr_real_index,1);
		break;
	}
}

