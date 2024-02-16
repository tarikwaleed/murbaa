vm_landsearch = new Vue({
	el: '#vue_area_div',
	data: {
		cities		: js_cities,
		neighborhood: [],
		types		: js_types,
		statues		: js_statues,
		adv			: js_adv,
		lands		: js_land,
		page_number	: 10,
		current_page: 1,
		pages		: [],
		interf		: js_interface, //interface
		letters		: js_letters, //letters 
		suggest		: js_suggest, //letters 
    },
	created: function() {
				
    },

	mounted(){
		this.onSubmitSearch();
		this.getAllChats();
		set_land_location();
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
		onSubmitSearch(event){
			if(event != undefined)
			{
				event.preventDefault();
			}
			if($('#land_search').length)
			{
				var x = this;
				$.post(URL+"dashboard/search",$('#land_search').serializeArray(),function(data,status,xhr){
					try {
						x.lands = data;
						/*if(data.length == 0){
							$("#footer_area").css('position','fixed');
							$("#footer_area").css('bottom',0);
							$("#footer_area").css('left',0);
							$("#footer_area").css('right',0);
                        }*/
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
		go_page(event)
		{
			alert(event.target.className);
		},
		getAllChats()
		{
			//Get Chats
			if($(".chat_area").length)
			{
				var th = this;
				$(".chat_area").each(function(){
					th.getChats($(this).data('id'));
				});
			}
		},
		getChats(room_id)
		{
			try{
				
				var room = this.lands[0].CHAT[room_id].ID;
				var	last = 0;
				if(this.lands[0].CHAT[room_id].CHAT_DATA.length)
				{
					last = this.lands[0].CHAT[room_id].CHAT_DATA[this.lands[0].CHAT[room_id].CHAT_DATA.length - 1].ID;
				}
				
				$th = this;
				if(typeof(EventSource) !== "undefined") 
				{
					var source = new EventSource(URL+"dashboard/chat_data/"+room+"/"+last);
					source.onopen = function(e){
						//alert("open");
					};
					source.onerror = function(e){
						//alert("error: "+JSON.stringify(e));
						chat_Notifications_s = setInterval($th.chat_Notifications, 30000, room_id); // 1Minits
					};
					source.onmessage = function(event) {
						$th.get_chat_txt(event.data,room_id);
					};
				}else{
					chat_Notifications_s = setInterval($th.chat_Notifications, 30000, room_id); // 1Minits
				}
			}
			catch(err) {
				alert(err.message);
			}
		},
		chat_Notifications(room_id)
		{
			var room = this.lands[0].CHAT[room_id].ID;
			var	last = 0;
			if(this.lands[0].CHAT[room_id].CHAT_DATA.length)
			{
				last = this.lands[0].CHAT[room_id].CHAT_DATA[this.lands[0].CHAT[room_id].CHAT_DATA.length - 1].ID;
			}
			$th = this;
			
			$.post(URL+"dashboard/chat_data/"+room+"/"+last,{},function(data,status,xhr)
			{
				$th.get_chat_txt(data,room_id);
				
			});
		},
		get_chat_txt(data,room_id)
		{
			var room = this.lands[0].CHAT[room_id].ID;
			var	last = 0;
			if(this.lands[0].CHAT[room_id].CHAT_DATA.length)
			{
				last = this.lands[0].CHAT[room_id].CHAT_DATA[this.lands[0].CHAT[room_id].CHAT_DATA.length - 1].ID;
			}
			
			try {
				var obj = JSON.parse(data);
				if ("Error" in obj)
				{
					alert("Error ".obj.Error);
				}else if(obj.length)
				{
					for($i = 0; $i<obj.length; $i++)
					{
						if(last < obj[$i].ID)
						{
							Vue.set(this.lands[0].CHAT[room_id].CHAT_DATA
									,this.lands[0].CHAT[room_id].CHAT_DATA.length
									,obj[$i]
									);
						}
					}
					
				}
			}
			catch(err) {
				alert(err.message+"\n"+data);
			}
		},
		newChatRoom()
		{
			var csrf = $("#csrf").val();
			var th = this;
			$.post(URL+"dashboard/newChatRoom",{'csrf':csrf,'land':this.lands[0].ID},function(data,status,xhr)
			{
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("Error "+obj.Error);
					}else if(obj.length)
					{
						Vue.set(th.lands[0].CHAT,th.lands[0].CHAT.length,obj[0]);
						th.getAllChats();
					}
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
				
			});
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
		
	}
});

function set_land_location() {
	if($("#land_location").length)
	{
		const loc_ini_data = $("#land_location").html();
		if(loc_ini_data == '')
		{
			return;
		}
		const loc_data = JSON.parse(loc_ini_data);
		//const loc_data = {};
		var lat = 15.6312428;
		var lng = 32.5642953;
		if(loc_data.LATLNG)
		{
			lat = loc_data.LATLNG[0].LAT;
			lng = loc_data.LATLNG[0].LNG;
		}else if(navigator.geolocation)
		{
			navigator.geolocation.getCurrentPosition(function(position){
				lat = position.coords.latitude;
				lng = position.coords.longitude;
			});
		}
		//create map:
		map = new google.maps.Map(document.getElementById("land_location"), {
			zoom: 10,
			center: { lat: lat, lng: lng },
		});
		if(loc_data.LATLNG != null)
		{
			var latnlg = loc_data.LATLNG;
			for(var i=0; i < latnlg.length; i++)
			{
				var myLatLng = { lat: latnlg[i].LAT, lng: latnlg[i].LNG };
				new google.maps.Marker({
					position: myLatLng,
					map,
				});
			}
		}
		if(loc_data.CIRCLE != null)
		{
            var circle = loc_data.CIRCLE;
			map.setCenter({lat: parseFloat(circle[0].CNTR_LAT), lng: parseFloat(circle[0].CNTR_LNG)});
			for(var i = 0; i < circle.length; i++)//
			{
				var cityCircle = new google.maps.Circle({
					strokeColor: "#FF0000",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					map,
					center: { lat: parseFloat(circle[i].CNTR_LAT), lng: parseFloat(circle[i].CNTR_LNG) },
					radius: Math.sqrt(parseFloat(circle[i].RADIUS)) * 100,
				});
			}
		}
		if(loc_data.RECTANGLE != null)
		{
			var rectangle = loc_data.RECTANGLE;
			if(loc_data.CIRCLE == null){
				var lon =  parseFloat(rectangle[0].E) + ((parseFloat(rectangle[0].W) - parseFloat(rectangle[0].E)) /2); 
				var lat =  parseFloat(rectangle[0].S) + ((parseFloat(rectangle[0].N) - parseFloat(rectangle[0].S)) /2); 
				map.setCenter({lat: lat, lng: lon});
			}
            for(var i = 0; i < rectangle.length; i++)//
			{
				var rectangle = new google.maps.Rectangle({
					strokeColor: "#FFF000",
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					map,
					bounds: {
						north: parseFloat(rectangle[i].N),
						south: parseFloat(rectangle[i].S),
						east: parseFloat(rectangle[i].E),
						west: parseFloat(rectangle[i].W),
					},
				});
			}
		}
	}
}
//add to adv list
$(document).on('click','#add_to_adv', function (e) 
{
	var id = vm_landsearch.lands[0].OW_ID;
	$.get(URL+"dashboard/adv_list/"+id, function(data, status){
		alert( data );
	});
});

$(".like_button").on('click',function(){
	$.post(URL+"dashboard/land_like/"+$(this).data('id'),{},function(data,status,xhr){
		alert(data);
		Vue.set(vm_landsearch.lands[0],'IS_LIKE',1);
	});
})
//submit modal form
$(document).on('submit','.chat_action', function (e)
{
	$(this).find(".err_notification").addClass(E_HIDE);
	var room = $(this).find(".room_info").val();
	$fr = $(this);
	$(this).ajaxSubmit({ 
		target:   '#targetLayer', 
		beforeSubmit: function() {
			//form_progress(0);
		},
		uploadProgress: function (event, position, total, percentComplete){	
			//form_progress(percentComplete);
		},
		success:function (){
			//form_progress(100);
			var x = $('#targetLayer').html();
			vm_landsearch.chat_Notifications(room);
			close_form_dialog($fr);
			
		},
		error:function(response,status,xhr){
			alert("error "+JSON.stringify(response));
		},
		resetForm: false
	});
	return false;
})

var full_screen = false;

$('.slide_img_kk,#close_slide_full').click(function(){
	/*if ($.fullscreen.isFullScreen()) 
	{
		$.fullscreen.exit();
	}else {
		$('#carouselExampleIndicators').fullscreen();
	}*/
	if(full_screen)
	{
		$('#carouselExampleIndicators').removeAttr( 'style' );
		$('.slide_img_kk').addClass('h-ne');
		$('.slide_img_kk').removeClass('h-ne_img_full');
		
		$('.slide_div_kk').removeClass('h-ne_full');
		$('#close_slide_full').addClass('d-none');
		full_screen = false;
	}else
	{
		full_screen = true;
		$('.slide_img_kk').removeClass('h-ne');
		$('.slide_img_kk').addClass('h-ne_img_full');
		$('#close_slide_full').removeClass('d-none');
		$('.slide_div_kk').addClass('h-ne_full');
		$('#carouselExampleIndicators').css({
		backgroundColor: 'LightGray',
		backgroundSize: 'contain',
		width: '100%',
		height: '100%',
		position: 'fixed',
		zIndex: '10000',
		top: '0',
		left: '0',
		cursor: 'zoom-out'});
	}
});
$(document).keyup(function(e) {
     if (e.key === "Escape") { // escape key maps to keycode `27`
       if(full_screen)
	   {
		   $('#carouselExampleIndicators').removeAttr( 'style' );
			$('.slide_img_kk').addClass('h-ne');
			$('.slide_img_kk').removeClass('h-ne_img_full');
			
			$('.slide_div_kk').removeClass('h-ne_full');
			$('#close_slide_full').addClass('d-none');
			full_screen = false;
	   }
    }
});

function modal_element($type,$id)
{
	
}