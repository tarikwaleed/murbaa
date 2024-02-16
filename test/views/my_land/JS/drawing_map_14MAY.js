let Map_obj = {};
let input_id = "";
let drawing_data = {};
let map = null;
let lng_id;
let lat_id;

function drawing_initial(map_id,id,type,lng_id_a,lat_id_a,old_data_a)
{
	input_id = id;
	lng_id = lng_id_a;
	lat_id = lat_id_a;
	//upd_land.LOC_LNG
	var lat = 24.6800207;
	var lng = 46.6987903;
    
	if(navigator.geolocation && old_data_a == '')
	{
		navigator.geolocation.getCurrentPosition(function(position){
			lat = position.coords.latitude;
			lng = position.coords.longitude;
            if(map == null)
			{
				map = new google.maps.Map(document.getElementById(map_id), {
					zoom: 7,
					center: { lat: lat, lng: lng },
				});
			}else
			{
				map.setCenter({lat: lat, lng: lng});
			}
		});
	}
	//create map:
	map = new google.maps.Map(document.getElementById(map_id), {
			zoom: 7,
			center: { lat: lat, lng: lng },
		});
	
	
	var mode = [];
	if(type == "REQ")
	{
		mode.push(google.maps.drawing.OverlayType.CIRCLE);
		mode.push(google.maps.drawing.OverlayType.RECTANGLE);
		//google.maps.drawing.OverlayType.POLYGON,
	}else
	{
		mode.push(google.maps.drawing.OverlayType.MARKER);
	}
	const drawingManager = new google.maps.drawing.DrawingManager({
		drawingMode: google.maps.drawing.OverlayType.MARKER,
		drawingControl: true,
		drawingControlOptions: {
			position: google.maps.ControlPosition.TOP_CENTER,
			drawingModes: mode,
		},
		markerOptions: {
			icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
		},
		circleOptions: {
			fillColor: "#ffff00",
			fillOpacity: 1,
			strokeWeight: 5,
			clickable: true,
			editable: true,
			zIndex: 1,
		},
	});
	drawingManager.setMap(map);
	
	//add old data
	var old_data = old_data_a;
	
	var old_obj = {CIRCLE:[],RECTANGLE:[]};
	if(old_data !== null && old_data !== "")
	{
		old_obj = JSON.parse(old_data);
	}
	
	if(old_obj.LATLNG != null)
	{
		var latnlg = old_obj.LATLNG;
		for(var i=0; i < latnlg.length; i++)
		{
			add_obj_marker(latnlg[i].LAT,latnlg[i].LNG);
		}
        map.setCenter({lat: latnlg[0].LAT, lng: latnlg[0].LNG});
        
        $("#"+lng_id).val(latnlg[0].LNG);
        $("#"+lat_id).val(latnlg[0].LAT);
    }
	if(old_obj.CIRCLE != null)
	{
		var circle = old_obj.CIRCLE;
		for(var i = 0; i < circle.length; i++)//
		{
			add_obj_circle(circle[i].CNTR_LAT,circle[i].CNTR_LNG,circle[i].RADIUS);
		}
        map.setCenter({lat: circle[0].CNTR_LAT, lng: circle[0].CNTR_LNG});
	}
	if(old_obj.RECTANGLE != null)
	{
		var rectangle = old_obj.RECTANGLE;
		for(var i = 0; i < rectangle.length; i++)//
		{
			add_obj_rectangle(rectangle[i].N,rectangle[i].S,rectangle[i].E,rectangle[i].W);
		}
        rec_lat = (rectangle[0].N + rectangle[0].S) / 2;
		rec_lng = (rectangle[0].E + rectangle[0].W) / 2;
		map.setCenter({lat: rec_lat, lng: rec_lng});
	}
	
	google.maps.event.addDomListener(drawingManager, 'circlecomplete', 
		function(circle) {
			if(!drawing_data.CIRCLE)
			{
				drawing_data.CIRCLE = [];
			}
			drawing_data.CIRCLE.push(circle);
			set_all_drawing_data();
		});
	google.maps.event.addDomListener(drawingManager, 'rectanglecomplete', 
		function(rectangle) {
			if(!drawing_data.RECTANGLE)
			{
				drawing_data.RECTANGLE = [];
			}
			drawing_data.RECTANGLE.push(rectangle)
			set_all_drawing_data();
		});
	google.maps.event.addDomListener(drawingManager, 'markercomplete', 
		function(marker) {
			if(!drawing_data.LATLNG)
			{
				drawing_data.LATLNG = [];
			}else
			{
				for(var i=0; i< drawing_data.LATLNG.length; i++)
				{
					drawing_data.LATLNG[i].setMap(null);
				}
			}
			drawing_data.LATLNG = [marker];
			set_all_drawing_data();
		});
}

function add_obj_marker(LAT,LNG)
{
	var myLatLng = { lat: LAT, lng: LNG };
	var marker = new google.maps.Marker({
					position: myLatLng,
					map,
					//title: "Hello World!",
				});
	$("#"+lat_id).val(LAT);
	$("#"+lng_id).val(LNG);
	if(!drawing_data.LATLNG)
	{
		drawing_data.LATLNG = [];
	}
	drawing_data.LATLNG.push(marker);
}

function add_obj_circle(LAT,LNG,RADIUS)
{
	var cityCircle = new google.maps.Circle({
			strokeColor: "#FF0000",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: "#FF0000",
			fillOpacity: 0.35,
			map,
			center: { lat: parseFloat(LAT), lng: parseFloat(LNG) },
			radius: Math.sqrt(parseFloat(RADIUS)) * 100,
		});
	if(!drawing_data.CIRCLE)
	{
		drawing_data.CIRCLE = [];
	}
	drawing_data.CIRCLE.push(cityCircle);
}

function add_obj_rectangle(N,S,E,W)
{
	if(!drawing_data.RECTANGLE)
	{
		drawing_data.RECTANGLE = [];
	}
	var rectangle = new google.maps.Rectangle({
			strokeColor: "#FFF000",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: "#FF0000",
			fillOpacity: 0.35,
			map,
			bounds: {
				north: parseFloat(N),
				south: parseFloat(S),
				east: parseFloat(E),
				west: parseFloat(W),
			},
		});
		
	drawing_data.RECTANGLE.push(rectangle);
}

function set_all_drawing_data()
{
	var d = {};
	if(drawing_data.CIRCLE)
	{
		d.CIRCLE = [];
		var circle = drawing_data.CIRCLE;
		for( i=0;i< circle.length;i++ )
		{
			var cir = {};
			var circleCenter = circle[i].getCenter();
			var circleRadius = circle[i].getRadius();
			cir.CNTR_LAT = circleCenter.lat().toFixed(3);
			cir.CNTR_LNG = circleCenter.lng().toFixed(3);
			cir.RADIUS	 = circleRadius.toFixed(3);
			d.CIRCLE.push(cir);
		}
	}
	if(drawing_data.RECTANGLE)
	{
		d.RECTANGLE = [];
		var rectangle = drawing_data.RECTANGLE;
		for( i=0;i< rectangle.length;i++ )
		{
			var rec = {};
			var bounds = rectangle[i].getBounds();
			rec.N = bounds.getNorthEast().lat();
			rec.S = bounds.getSouthWest().lat();
			rec.E = bounds.getNorthEast().lng();
			rec.W = bounds.getSouthWest().lng();
			d.RECTANGLE.push(rec);
		}
	}
	if(drawing_data.LATLNG)
	{
		d.LATLNG = [];
		var latlng = drawing_data.LATLNG;
		for( i=0;i< latlng.length;i++ )
		{
			var rec = {"LAT":latlng[i].getPosition().lat(),"LNG":latlng[i].getPosition().lng()};
			d.LATLNG.push(rec);
		}
	}
	$('#'+input_id).val(JSON.stringify(d));
}

$(document).on('click','.delete-circle', function (e) 
{
	if(typeof drawing_data.CIRCLE === 'undefined' || drawing_data.CIRCLE.length == 0 )
	{
		alert('لا يوجد دائرة لمسحها');
		return;
	}
	id = drawing_data.CIRCLE.length - 1;
	if(id >= 0)
	{
		drawing_data.CIRCLE[id].setMap(null);
		drawing_data.CIRCLE.pop();
		if(drawing_data.CIRCLE.length == 0)
		{
			delete drawing_data.CIRCLE;
		}
		set_all_drawing_data();
	}
});
$(document).on('click','.delete-rectangle', function (e) 
{
	if(typeof drawing_data.RECTANGLE === 'undefined' || drawing_data.RECTANGLE.length == 0 )
	{
		alert('لا يوجد مستطيل لمسحه');
		return;
	}
	id = drawing_data.RECTANGLE.length - 1;
	if(id >= 0)
	{
		drawing_data.RECTANGLE[id].setMap(null);
		drawing_data.RECTANGLE.pop();
		if(drawing_data.RECTANGLE.length == 0)
		{
			delete drawing_data.RECTANGLE;
		}
		set_all_drawing_data();
	}
});
	