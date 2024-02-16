vm_pkg_type = new Vue({
	el: '#types_settings',
	data: {
		types: js_types,
		upd_type:[],
	},
	mounted(){
		
	},
	methods: {
		update_type(index){
			this.upd_type = this.types[index];
			this.upd_type.INDEX = index;
		},
		del(index){
			var crt= $("#csrf").val();
			var id = this.types[index]['ID'];
			var vm = this;
			if(confirm("هل انت متأكد من مسح النوع "+this.types[index]['NAME']+" ?"))
			{
				$.post(URL+"pkg_types/del_type",{csrf:crt,id:id},function(data,status,xhr){
					try {
						if ("Error" in data)
						{
							alert(data.Error)
						}else
						{
							alert("تم حذف النوع ");
							vm.types.splice(index, 1)
						}
					}
					catch(err) {
						alert(err.message+"\n\n\n"+data);
					}
				},'JSON');
			}
		},
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
	}
}

function new_modal_element($id = 0)
{
	var obj = {};
	obj.ID 			= $id;
	obj.NAME 		= $("#new_name").val();
	obj.USERS 		= $("#new_user").val();
	obj.PRICE 		= $("#new_price").val();
	obj.STARS 		= $("#new_stars").val();
	obj.ADV 		= $("#new_adv").val();
	obj.VIP 		= $("#new_vip").val();
	obj.ADV_PAY 	= ($("#new_adv_pay").checked)? 1:0;
	obj.MSG 		= ($("#new_msg").checked)? 1:0;
	obj.CO_NO		= 0;
	vm_pkg_type.types.push(obj);
}

function upd_modal_element()
{
	//upd_index
	var index = vm_pkg_type.upd_type.INDEX;
	Vue.set(vm_pkg_type.types[index], 'NAME', $("#upd_name").val())
	Vue.set(vm_pkg_type.types[index], 'USERS', $("#upd_user").val())
	Vue.set(vm_pkg_type.types[index], 'PRICE', $("#upd_price").val())
	Vue.set(vm_pkg_type.types[index], 'STARS', $("#upd_stars").val())
	Vue.set(vm_pkg_type.types[index], 'ADV', $("#upd_adv").val())
	Vue.set(vm_pkg_type.types[index], 'VIP', $("#upd_vip").val())
	Vue.set(vm_pkg_type.types[index], 'VIP', $("#upd_vip").val())
	Vue.set(vm_pkg_type.types[index], 'ADV_PAY', ($("#new_adv_pay").checked)? 1:0)
	Vue.set(vm_pkg_type.types[index], 'MSG', ($("#upd_msg").checked)? 1:0)
	
}
