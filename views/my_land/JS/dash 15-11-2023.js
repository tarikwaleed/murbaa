vm_landsearch = new Vue({
	el: '#vue_area_div',
	data: {
		cities		: js_cities,
		neighborhood: [],
		company		: js_company,
		types		: js_types,
		statues		: js_statues,
		adv			: js_adv,
		currency	: js_currency,
		lands		: js_land,
		config		: js_config,
		page_number	: 10,
		current_page: 1,
		pages: [],
		upd_land	:[],
		interf		: js_interface, //interface
		vip_land	:[],
		VIP_price	: js_VIP_price,
		VIP_period	: js_VIP_period,
		bill_no		: 0,
		land_price	: js_price,
		relation	: js_relation,
		delegate	: false,
		local_delegate	: false,
		pay_form	: "",
		
    },
	created: function() {
		this.onSubmitSearch();
		this.upd_land['COMPANY'] = [];
    },
	mounted(){
		if(upg_pay != '')
		{
			if(typeof upg_pay.error != "undefined")
			{
				$txt = "هنالك خطأ: \n "+upg_pay.error;
				if(typeof upg_pay.error_data != "undefined")
                {
                    $txt += "\n "+upg_pay.error_data.error_data.message;
                }
                alert($txt);
			}else if(typeof upg_pay.Error != "undefined")
			{
				$txt = "هنالك خطأ: \n "+upg_pay.Error;
				if(typeof upg_pay.error_data != "undefined")
                {
                    $txt += "\n "+upg_pay.Error;
                }
                alert($txt);
			}else if(typeof upg_pay.id != "undefined" && upg_pay.id != 0)
			{
				alert("تمت عملية الدفع بنجاح, رقم الفاتورة "+upg_pay.id);
			}
		}
		
	},
	computed: {
		displayedPosts () {
			return this.paginate(this.lands);
		}
	},
	watch: {
		lands () {
			//this.setPages();
		}
	},
	methods: {
		onChangeCity(event) {
			var id = event.target.options[event.target.selectedIndex].dataset.no;
			this.neighborhood = this.cities[id].NEIGHBOR;
		},
		onChangeRelation(event) {
			this.delegate = event.target.options[event.target.selectedIndex].dataset.dele;
		},
		onChangeRelation_type(event) {
			this.local_delegate = event.target.value;
		},
		paginate (posts) {
			let page = this.current_page;
			let perPage = $("#paging_length").val();
			let from = (page * perPage) - perPage;
			let to = (page * perPage);
			return  posts.slice(from, to);
		},
		setPages () {
			let perPage = $("#paging_length").val();
			let numberOfPages = Math.ceil(this.lands.length / perPage);
			for (let index = 1; index <= numberOfPages; index++) {
				this.pages.push(index);
			}
		},
		onChangeType(event,$build,$farm) {
			var sel_id = event.target.id;
			var curr_id = $('option:selected', "#"+sel_id).last().data('id');
			
			if(this.types[curr_id].BUILD == 1)
			{
				$("."+$build).removeClass(E_HIDE);
				$("."+$farm).addClass(E_HIDE);
			}else if(this.types[curr_id].BUILD == 2)
			{
				$("."+$farm).removeClass(E_HIDE);
				$("."+$build).addClass(E_HIDE);
			}else
			{
				$("."+$farm).addClass(E_HIDE);
				$("."+$build).addClass(E_HIDE);
			}
		},
		onChangeFor(event,$sail) {
			var sel_id = event.target.id;
			var curr_id = $('option:selected', "#"+sel_id).data('id');
			
			if(curr_id == 'SALE')
			{
				$("."+$sail).removeClass(E_HIDE);
			}else
			{
				$("."+$sail).addClass(E_HIDE);
			}
			
		},
		onSubmitSearch(event){
			if(event != undefined)
			{
				event.preventDefault();
			}
			if($('#land_search').length)
			{
				var x = this;
				var cu = $('#land_search').attr('action');
				$.post(cu,$('#land_search').serializeArray(),function(data,status,xhr){
					try {
						x.lands = data;
						x.current_page = 1;
						x.pages = [];
						x.setPages();
						setTimeout(function(){ 
							set_vue_reload_func(); 
						}, 100);
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				},'JSON');
			}
			return false;	
		},
		new_land($type)
		{
			if($type == "ADV")
			{
				set_location_map('map_area','new_map_data',$type,'new_lng','new_lat');
			}
			else{
				set_location_map('request_map_area','request_map_data',$type,'new_lng','new_lat');
					
			}
			//initMap();
			
		},
		update_land(id){
			//this.upd_land = this.displayedPosts[id];
			this.upd_land = {};
			Vue.set(this.upd_land, "L_INDEX", id);
			
			$th = this;
			for (const [key, value] of Object.entries(this.displayedPosts[id])) 
			{
				Vue.set($th.upd_land, key, value);
			}
			
			this.delegate = this.relation[this.upd_land.RELATION].del;
			if(this.delegate)
			{
				if(this.upd_land.DELEGATION != '')
				{
					this.local_delegate = "LOCAL";
				}else
				{
					this.local_delegate = "REG";
				}
				
				setTimeout(function()
				{
					$("#upd_relation_type").val(vm_landsearch.local_delegate);
				},2000);
				
			}else
			{
				this.local_delegate = "";
			}
            if(this.types[this.upd_land.TYPE].BUILD == 1)
			{
				$(".upd_home_area").removeClass(E_HIDE);
				$(".upd_farm_area").addClass(E_HIDE);
			}else if(this.types[this.upd_land.TYPE].BUILD == 2)
			{
				$(".upd_farm_area").removeClass(E_HIDE);
				$(".upd_home_area").addClass(E_HIDE);
			}else
			{
				$(".upd_farm_area").addClass(E_HIDE);
				$(".upd_home_area").addClass(E_HIDE);
			}
			
			if(this.upd_land.ADV == "ADV")
			{
				set_location_map('upd_map_area','upd_location',this.upd_land.ADV,'upd_lat','upd_lng',this.upd_land.LOCATION);
			}else{
				set_location_map('upd_request_map_area','upd_request_map_data',this.upd_land.ADV,'upd_lat','upd_lng',this.upd_land.LOCATION);
			}
		},
		del_upd_img(id){
			var x = this;
			$fdata = {};
			$fdata.csrf = $("#csrf").val();
			$fdata.id = this.upd_land.ID;
			$fdata.img = this.upd_land.OTHER_IMG[id].NAME;
			
			$.post(URL+"my_land/del_img",$fdata,function(data,status,xhr){
				try {
					$oth_img = x.lands[x.upd_land.L_INDEX].OTHER_IMG;
					
					$oth_img.splice(id, 1); 
					Vue.set(x.lands[x.upd_land.L_INDEX], 'OTHER_IMG', $oth_img);
					Vue.set(x.upd_land, 'OTHER_IMG', $oth_img);
					
					alert("تم حذف الصورة");
					
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
			return false;
		},
		active(index){
			var x = this;
			var crt= $("#csrf").val();
			var id = this.displayedPosts[index]['ID'];
			
			var active = this.displayedPosts[index]['ACTIVE'] == 1;
			$.post(URL+"my_land/active",{csrf:crt,id:id,current:active},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم تنشيط / تجميد العقار ");
						$act = (x.displayedPosts[index]['ACTIVE'] == 1)?0:1;
						Vue.set(x.displayedPosts[index], 'ACTIVE', $act);
						Vue.set(x.lands[index], 'ACTIVE', $act);
					}
				}
				catch(err) {
					alert(err.message+"\n\n\n"+data);
				}
			});
		},
		vip(index){
			if(confirm("هل انت متأكد من إضافة هذا العقار ليصبح متميز لمدة "+this.VIP_period+" يوم?"))
			{
				var x = this;
				var crt= $("#csrf").val();
				var id = this.displayedPosts[index]['ID'];
				$.post(URL+"my_land/vip",{csrf:crt,id:id},function(data,status,xhr){
					try {
						var obj = JSON.parse(data);
						if ("Error" in obj)
						{
							alert("ll: "+obj.Error)
						}else
						{
							alert("اصبح العقار متميز");
							var date = new Date();
							var m = date.getMonth() + 1
							var d = date.getDate()+"-"+m+"-"+date.getFullYear();
							Vue.set(x.displayedPosts[index], 'PACKAGE_START', d);
							Vue.set(x.lands[index], 'PACKAGE_START', d);
							
							date.setDate(date.getDate() + x.VIP_period);
							m = date.getMonth() + 1
							d = date.getDate()+"-"+m+"-"+date.getFullYear();
							Vue.set(x.displayedPosts[index], 'PACKAGE_END', d);
							Vue.set(x.lands[index], 'PACKAGE_END', d);
						}
					}
					catch(err) {
						alert(err.message+"\n\n\n"+data);
					}
				});
			}
		},
		by_vip(id){
			if(this.displayedPosts[id].PACKAGE_START!== null)
			{
				alert("هذا العقار ضمن الباقة المميزة ");
				setTimeout(function(){ 
					$('#vip_land').modal('hide');
				}, 10);
			}else
			{
				this.vip_land = {};
				Vue.set(this.vip_land, "L_INDEX", id);
				$th = this;
				for (const [key, value] of Object.entries(this.displayedPosts[id])) 
				{
					Vue.set($th.vip_land, key, value);
				}
				this.pay_form = 'VIP_BILL';
                setTimeout(getdiscount, 1000);
			}
		},
		by_land(id){
			this.pay_form = 'LAND_BILL';
			
			this.vip_land = {};
			Vue.set(this.vip_land, "L_INDEX", id);
			$th = this;
			for (const [key, value] of Object.entries(this.displayedPosts[id])) 
			{
				Vue.set($th.vip_land, key, value);
			}
            setTimeout(getdiscount, 1000);
		},
		vip_price_change()
		{
			var period = $("#vip_range").val();
			var pkgs = period / this.VIP_period;
			var amount = pkgs * parseFloat(this.VIP_price);
			$("#vip_price").val(amount);
            getdiscount();
		},
		get_type_color($type)
		{
			var cls = "label ";
			if($type == "SALE")
			{
				cls += " c-red";
			}else if($type == "INVEST")
			{
				cls += " bg-info";
			}
			return cls;
		},
		ch_cobon()
		{
			$v = event.target;
			$x =  $v.value;
			$th= this;
			$.getJSON(URL+"my_co/cobon/"+$x+"/LAND", function(obj){
				if ("Error" in obj)
				{
					alert(obj.Error);
					$v.value = "";
				}else
				{
					$amount = obj.PRICE;
					if(obj.PRICE_TYPE == 'PER')
					{
						$amount = $amount / 100;
					}
					$('#vip_discount').val($amount);
					getdiscount();
					
				}
			});
		
		},
	}
});

function update_at_land()
{
	location.reload();
}
function getdiscount()
{
	$total = $('#vip_price').val();
	$amount= $('#vip_discount').val();
	if($total == 0 || $amount == 0)
	{
		return;
	}
	
	if($amount < 1)
	{
		$total = $total - ($amount * $total);
	}else
	{
		$total = $total - $amount;
	}
	$("#vip_price").val($total);
	
}

function modal_element($type,$id)
{
	switch($type)
	{
		case "new_vip":
			alert("رقم العملية المالية: "+$id);
			new_upd_land_element($id);
		break;
		case "new_land":
			if($('#new_adv').val() == "ADV")
			{
				Vue.set(vm_landsearch.config,'ALL_LANDS',parseInt(vm_landsearch.config.ALL_LANDS) + 1);
			}
		case "upd_land":
			new_upd_land_element($id);
		break;
		case "del":
			del_modal_element();
		break;
	}
}

function new_upd_land_element($id = 0)
{
	vm_landsearch.onSubmitSearch();
}
/*
function set_location_map(map_id,loc_id)
{
	//upd_land.LOC_LNG
	var lat = 15.6312428;
	var lng = 32.5642953;
	if(old_lat !== null && old_lng !== null && old_lat !== "" && old_lng !== "")
	{
		lat = parseFloat(old_lat);
		lng = parseFloat(old_lng);
	}else if(navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(function(position){
			lat = position.coords.latitude;
			lng = position.coords.longitude;
		});
	}
			
	$("#"+lat_id).val(lat);
	$("#"+lng_id).val(lng);
	
	//create map:
	const myLatlng = { lat: lat, lng: lng };
	
	const map = new google.maps.Map(document.getElementById(map_id), {
		zoom: 7,
		center: myLatlng,
	});
	
	// Create the initial InfoWindow.
	let infoWindow = new google.maps.InfoWindow({
		content: "اضغط على الخريطة لجلب الموقع",
		position: myLatlng,
	});
	infoWindow.open(map);
	
	// Configure the click listener.
	map.addListener("click", (mapsMouseEvent) => {
		// Close the current InfoWindow.
		infoWindow.close();
		// Create a new InfoWindow.
		infoWindow = new google.maps.InfoWindow({
			position: mapsMouseEvent.latLng,
		});
		$("#"+lat_id).val(mapsMouseEvent.latLng.lat);
		$("#"+lng_id).val(mapsMouseEvent.latLng.lng);
		var lat = $("#"+lat_id).val();
		var lng = $("#"+lng_id).val();
		
		var cont = "خط الطول: "+lat+" - خط العرض: "+lng;
		infoWindow.setContent(
			cont
		);
		infoWindow.open(map);
	});
}
*/
function set_location_map(map_id,data_id,type,lng,lat,old_data="")
{
	drawing_initial(map_id,data_id,type,lng,lat,old_data);
	
	//document.getElementById("delete-circle").addEventListener("click", deletecircle(drawing_data,data_id));
	

}

//////////////////////////////////////////////////////MSG Staff
$(document).on('change','#msgs', function (e) 
{
	var x = $(this).prop('checked');
	$('.msgs').prop('checked',x);
	
})

paylib.inlineForm({
	'key': JS_KEY,
	'form': document.getElementById('vip_land_pay'),
	'autoSubmit': true,
	'callback': function(response) 
	{
		document.getElementById('vip_land_pay_error').innerHTML = '';
		if (response.error) 
		{             
			paylib.handleError(document.getElementById('vip_land_pay_error'), response); 
		}
	}
});


