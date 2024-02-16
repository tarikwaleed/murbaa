vm_city = new Vue({
	el: '#city_area',
	data: {
		cities: js_cities,
		letters		: js_letters, //letters 
		
    },
	mounted(){
		
	},
	methods: {
		upd_city(index) {
			$('#upd_index').val(index);
			$('#upd_id').val(this.cities[index]['ID']);
			$('#upd_name').val(this.cities[index]['NAME']);
		},
		del_city(index){
			$('#del_index').val(index);
			$('#del_id').val(this.cities[index]['ID']);
			$('#del_name').val(this.cities[index]['NAME']);
		},
		upd_nei(index,city_index) {
			
			$('#upd_nei_index').val(index);
			$('#upd_nei_old_city').val(city_index);
			$('#upd_nei_id').val(this.cities[city_index].NEIGHBOR[index].ID);
			$('#upd_nei_name').val(this.cities[city_index].NEIGHBOR[index].NAME);
			$('#upd_letter').val(this.cities[city_index].NEIGHBOR[index].LETTER);
			$('#upd_nei_city').val(this.cities[city_index].ID);
		},
		del_nei(index,city_index){
			
			$('#del_nei_index').val(index);
			$('#del_nei_city').val(city_index);
			$('#del_nei_id').val(this.cities[city_index].NEIGHBOR[index].ID);
			$('#del_nei_name').val(this.cities[city_index].NEIGHBOR[index].NAME);
		}
	}
});


function modal_element($type,$id)
{
	switch($type)
	{
		case "new_city":
			new_city_modal_element($id);
		break;
		case "upd_city":
			upd_city_modal_element();
		break;
		case "del_city":
			del_city_modal_element();
		break;
		case "new_nei":
			new_nei_modal_element($id);
		break;
		case "upd_nei":
			upd_nei_modal_element();
		break;
		case "del_nei":
			del_nei_modal_element();
		break;
	}
}


function new_city_modal_element($id = 0)
{
	var obj = {};
	obj.ID = $id;
	obj.NAME = $("#new_name").val();
	obj.NEIGHBOR = [];
	Vue.set(vm_city.cities, vm_city.cities.length, obj);
	
}

function upd_city_modal_element()
{
	//upd_index
	var index = $("#upd_index").val();
	Vue.set(vm_city.cities[index], 'NAME', $("#upd_name").val());
	
}

function del_city_modal_element()
{
	var index = $("#del_index").val();
	vm_city.cities.splice(index,1);
}

function new_nei_modal_element($id = 0)
{
	var obj = {};
	obj.ID = $id;
	obj.NAME = $("#new_nei_name").val();
	obj.LETTER = $("#new_letter").val();
	obj.LANDS = 0;
	var city_index = $('option:selected', "#new_nei_city").data('id');
	
	Vue.set(vm_city.cities[city_index].NEIGHBOR, vm_city.cities[city_index].NEIGHBOR.length, obj);
	
}

function upd_nei_modal_element()
{
	var index 			= $("#upd_nei_index").val();
	var old_city_index 	= $("#upd_nei_old_city").val();
	Vue.set(vm_city.cities[old_city_index].NEIGHBOR[index], 'NAME', $("#upd_nei_name").val());
	Vue.set(vm_city.cities[old_city_index].NEIGHBOR[index], 'LETTER', $("#upd_letter").val());
	var city_index = $('option:selected', "#upd_nei_city").data('id');
	
	if(old_city_index != city_index)
	{
		Vue.set(
			vm_city.cities[city_index].NEIGHBOR, 
			vm_city.cities[city_index].NEIGHBOR.length, 
			vm_city.cities[old_city_index].NEIGHBOR[index]
			);
		vm_city.cities[old_city_index].NEIGHBOR.splice(index,1);
	}
}

function del_nei_modal_element()
{
	var index 		= $("#del_nei_index").val();
	var city_index 	= $("#del_nei_city").val();
	
	vm_city.cities[city_index].NEIGHBOR.splice(index,1);
	
}

