window.onerror = function(error) {
  // do something clever here
  alert(error); // do NOT do this for real!
};

//JS For Products Actions
$(document).ready(function()
{
	if( typeof get_ready == 'function')
	{
		get_ready();
	}
	
	//For Error Class style
	$('.err_notification').addClass(E_HIDE);
	
	get_model_default_functions();
	
	//set active menu
	var curr_url = window.location.href.split('#')[0];
	var url_arr = curr_url.split('/');
	if( $.isNumeric(url_arr[url_arr.length -1]))
	{
		var len = curr_url.length - url_arr[url_arr.length -1].length - 1;
		curr_url = curr_url.substr(0,len);
	}else if(curr_url == URL+"/" || curr_url == URL || curr_url.endsWith("dashboard")|| curr_url.endsWith("dashboard/") )
	{
		$(".dashboard_adv_search").removeClass('d-none');
	}
	
	//active link and permissions
	$("#nav-menu-links-list li").each(function(){
		if(!$(this).hasClass('dashboard_adv_search'))
		{
			var li = $(this).find('a').attr('href');
			if(li == curr_url || li+"/" == curr_url)
			{
				$(this).addClass('active');
			}else{
				$(this).removeClass('active');
			}
			
			//check permission
			var link_a = li.replace(URL,'');
			link_a = link_a.split('/');
			var asd = 1;
			if(link_a.length == 1)
			{
				asd = vm_page_permission.h_access(link_a[0]);
			}else{
				asd = vm_page_permission.h_access(link_a[0],link_a[1]);
			}
			if(!asd)
			{
				$(this).addClass(E_HIDE);
			}
		}
	});
	
	//default year
	$year = new Date().getFullYear();
	$(".default_year_place").each(function(){
		if($(this).is("input"))
		{
			$(this).attr('max',$year);
		}else
		{
			$(this).html($year);
		}
	})
	
})

//contact Form
$(document).on('submit','#contact_form', function (e) 
{
	e.preventDefault();
	$(".err_notification").addClass("d-none");
	$(this).ajaxSubmit({
		target:   '#targetLayer', 
		beforeSubmit: function() {
			form_progress(0);
		},
		uploadProgress: function (event, position, total, percentComplete){	
			form_progress(percentComplete);
		},
		success:function (){
			form_progress(100);
			var x = $('#targetLayer').html();
			try {
				var obj = JSON.parse(x);
				if ("Error" in obj)
				{
					error_handler(x);
				}else
				{
					alert("تم إرسال رسالتك, سيتم الرد عليك قريبا");
				}
			}
			catch(err) {
				alert(err.message+"\n\n\n ASD: "+x);
			}
		},
		resetForm: false
	});
	return false;
})

//Mailing list
$(document).on('submit','#mail_list', function (e) 
{
	e.preventDefault();
	$(".err_notification").addClass("d-none");
	$(this).ajaxSubmit({
		target:   '#targetLayer', 
		beforeSubmit: function() {
			form_progress(0);
		},
		uploadProgress: function (event, position, total, percentComplete){	
			form_progress(percentComplete);
		},
		success:function (){
			form_progress(100);
			var x = $('#targetLayer').html();
			try {
				var obj = JSON.parse(x);
				if ("Error" in obj)
				{
					error_handler(x);
				}else
				{
					alert("لقد تم اشتراكك في رسائل البريد الإلكترونى ");
				}
			}
			catch(err) {
				alert(err.message+"\n\n\n"+x);
			}
		},
		resetForm: false
	});
	return false;
})

//For Form Error 
function error_handler(data)
{
	try {
		var obj = JSON.parse(data);
		if ("Error" in obj)
		{
			var res = obj.Error;
			if(res == "No Certificate")
			{
				alert("Error Certificate");
			}else
			{
				var m;
				res = res.split("\n");
				res.forEach(function(item, index)
				{
					if(item.search("Data not saved") != -1)
					{
						$('#save_err').modal();
					}else if(item != "" && item.trim() != "")
					{
						item = item+"";
						m = item.split(":");
						if(m.length != 2)
						{
							alert(item);
						}else if(m[0]=="modal")
						{
							$('#'+m[0]).modal('show');
						}else
						{
							m[0] = m[0].replace("In Field ", "");
							m[0] = m[0].replace(" ", "");
							if(m[1].search("Duplicate") != -1)
							{
								if($("#duplicate_"+m[0]).length)
								{
									$("#duplicate_"+m[0]).removeClass(E_HIDE);
								}else
								{
									alert("Duplicate in :"+m[0]);
								}
								
							}else{
								if($("#valid_"+m[0]).length)
								{
									$("#valid_"+m[0]).removeClass(E_HIDE);
									$("#valid_"+m[0]).html(m[1]);
								}else
								{
									alert("Error in :"+m[0]);
								}
							}
						}
					}
				})
			}
		}
	}
	catch(err) {
		alert(err.message+"\n\n\n"+data);
	}
} 

//for Form Progress
function form_progress(percentage)
{
	$('#progress_area').val(percentage);
	if(percentage === 0)
	{
		$('#targetProgress').show();
	}else if(percentage == 100)
	{
		setTimeout(function() { $("#targetProgress").hide(); }, 1500);
	}
}

//clear form
function close_form_dialog(di)
{
	var ty = di.data('type');
	if(ty.search('add') != -1 || ty.search('new') != -1 )
	{
		di.find('input:not(.hid_info):not(:checkbox):not(:radio)').val('');
		di.find('select').val('');
		di.find('textarea').html('');
		di.find('textarea').val('');
		di.find('input:checkbox').prop('checked', false);
		di.find('input:radio').prop('checked', false);
		di.find('.err_notification').addClass(E_HIDE);
		di.find('.clear_form_area').html('');
		di.find('.range_input').html('');
		di.find('.form_images').attr('src','');
	}
}

//_____________________________________Model Form
function get_model_default_functions()
{
	//open model
	$(".open_model").click(function(){
		$(this).find('.err_notification').addClass(E_HIDE);
		$id = $(this).attr("data-bs-target");
		$($id).modal('show');
	});
	
	$('.modal_with_form').on('show.bs.modal', function (event) {
		$(this).find('.err_notification').addClass(E_HIDE);
	});

	//close model
	$('.modal_with_form').on('hidden.bs.modal', function () {
		$form = $(this).find('form');
		close_form_dialog($form);
	});

}

//submit modal form
$(document).on('submit','.model_form', function (e)
{
	$(this).find(".err_notification").addClass(E_HIDE);
	$MSG 		= $(this).find('.form_msg').html();
	$model_id 	= $(this).attr('data-model');
	$ID 		= $(this).attr('id');
	$type 		= $(this).attr('data-type');
	$wait_area	= $(this).find('.wait_area');

	$(this).ajaxSubmit({ 
		target:   '#targetLayer', 
		beforeSubmit: function() {
			if($wait_area.length)
			{
				$wait_area.removeClass('d-none');
			}
			form_progress(0);
		},
		uploadProgress: function (event, position, total, percentComplete){	
			form_progress(percentComplete);
		},
		success:function (){
			form_progress(100);
            if($wait_area.length)
			{
				$wait_area.addClass('d-none');
			}
			var x = $('#targetLayer').html();
			try {
				var obj = JSON.parse(x);
				if ("Error" in obj)
				{
					error_handler(x);
				}else if("id" in obj && obj.id != 0)
				{
					alert($MSG);
					
					modal_element($type,obj.id);
					
					close_form_dialog($("#"+$ID));
					$('#'+$model_id).modal('hide');
					
				}else
				{
					alert(x);
				}
			}
			catch(err) {
				alert(err.message+"\n"+x);
			}
		},
		error:function(response,status,xhr){
			alert("error "+JSON.stringify(response));
		},
		resetForm: false
	});
	return false;
})

//_____________________________________End Model Form

//Range
$(document).on('mousemove','.range_input', function (e)
{
	$id = $(this).data('view');
	$("#"+$id).html($(this).val());
});

//Range
$(document).on('input', '.range_input', function() {
    $id = $(this).data('view');
	$("#"+$id).html($(this).val());
});

//loader
var myVar;

function loader() {
	myVar = setTimeout(showPage, 300);
}

function showPage() {
	document.getElementById("loader").style.display = "none";
	x = document.getElementsByClassName("vue_area_div");
	for (i = 0; i < x.length; i++)
	{
		x[i].style.display = 'block';
	}
	
}

/*
//Date Picker
$('.datepicker').datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
*/
function ago($time)
{
	$periods = array("ثانية", "دقيقة", "ساعة", "يوم", "أسبوع", "شهر", "سنة", "عشر سنوات");
	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$difference = $now - $time;
	$tense = "منذ";
	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) 
	{
		$difference /= $lengths[$j];
	}
	$difference = round($difference);
	if($difference != 1) 
	{
		$periods[$j];
	}
	return "$difference منذ $periods[$j]";
}
