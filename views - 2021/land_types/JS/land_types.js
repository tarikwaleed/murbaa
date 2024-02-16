vm_land_type = new Vue({
	el: '#types_settings',
	data: {
		types: js_types,
		l_status: js_status,
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
				$.post(URL+"land_types/del_type",{csrf:crt,id:id},function(data,status,xhr){
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
			new_modal_element($id);
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
	obj.TY_FOR 		= $("#new_for").val();
	obj.BUILD 		= $('input[name="new_build"]:checked').val();
	obj.LANDS		= 0;
	vm_land_type.types.push(obj);
}

function upd_modal_element()
{
	//upd_index
	var index = vm_land_type.upd_type.INDEX;
	Vue.set(vm_land_type.types[index], 'NAME', $("#upd_name").val());
	Vue.set(vm_land_type.types[index], 'TY_FOR', $("#upd_for").val());
	Vue.set(vm_land_type.types[index], 'BUILD', $('input[name="upd_build"]:checked').val());
	
}
