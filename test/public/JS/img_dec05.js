//For Image Upload
$(document).on('click','.input_file_icon', function (e) 
{
	var id = $(this).attr('data-id');
	$("#"+id).trigger("click");
});

//For display image befor upload it
$(document).on('change','.image_upload', function (e) 
{
	if (typeof (FileReader) != "undefined") 
	{
		var locat = $(this).attr('data-id');
		
		var image_holder = $("#"+locat);
		$("#"+locat).attr( "src",URL+"public/IMG/logo.png");
		
		var reader = new FileReader();
		reader.onload = function (e) {image_holder.attr( "src",e.target.result);}
		reader.readAsDataURL($(this)[0].files[0]);
		
	} else {
		alert("عفوا هذا المتصفح لا يدعم عرض الصور قبل تحميلها");
	}
});

//For display multiple image befor upload it
$(document).on('change','.multi_image_upload', function (e) 
{
	if (typeof (FileReader) != "undefined") 
	{
		var locat = $(this).attr('data-id');
		var filesAmount = $(this)[0].files;
		$("#"+locat).html("");
		
		for (i = 0; i < filesAmount.length; i++) 
		{
			var ty = filesAmount[i].type.split('/');
			if(ty[0] == "image")
			{
				var reader = new FileReader();
				reader.onload = function(event) 
				{
					var ele =$('<img />');
					ele.attr('src', event.target.result);
					ele.attr('width', "75px");
					ele.attr('height', "75px");
					ele.attr('class', "m-2");
					var div = $("<div></div>");
					div.attr('class', "col-sm");
					div.append(ele);
					$("#"+locat).append(div);
				}
				
				reader.readAsDataURL($(this)[0].files[i]);
			}else
			{
				var ele =$('<p></p>');
				ele.attr('class', "m-2");
				ele.html(filesAmount[i].name);
				var div = $("<div></div>");
				div.attr('class', "col-sm");
				div.append(ele);
				$("#"+locat).append(div);
			}
			
		}
		
	} else {
		alert("عفوا هذا المتصفح لا يدعم عرض الصور قبل تحميلها");
	}
});

$('.image_form').submit(function(e) {
	
	var reg_form = $(this);
	$(this).find(".err_notification").addClass("d-none");
	
	var postData = $(this).serializeArray();
	var msg		 = $(this).attr("data-msg")
	e.preventDefault();
	
	//$('#loader-icon').show();
	$(this).ajaxSubmit({ 
	
		target:   '#targetLayer', 
		beforeSubmit: function() {
			//move(0,'progress');
			$('#send_ok').modal('show');
		},
		uploadProgress: function (event, position, total, percentComplete){	
			//move(percentComplete,'progress');
		},
		success:function (){
			//move(100,'progress');
			var x = $('#targetLayer').html();
			$('#send_ok').modal('hide');
			try {
				var obj = JSON.parse(x);
				if ("Error" in obj)
				{
					error_handler(x);
				}else
				{
					var id = reg_form.attr('data-ok');
					if($("#"+id).length)
					{
						$("#"+id).val(2);
						$("#"+id).trigger('change');
					}else
					{
						
						$('#'+msg).modal('show');
					}
					
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

//For display file befor upload it
$(document).on('change','.file_upload', function (e) 
{
	if (typeof (FileReader) != "undefined") 
	{
		var locat = $(this).attr('data-id');
		var file_holder = $("#"+locat);
		
		var reader = new FileReader();
		reader.onload = function (e) 
		{
			file_holder.html(e.target.result);
			uploaded_file_action(locat);
		}
		reader.readAsText($(this)[0].files[0]);
		
	} else {
		alert("عفوا هذا المتصفح لا يدعم عرض الملفات قبل تحميلها");
	}
});
