vm_group = new Vue({
	el: '#staff_settings',
	data: {
		group	: js_group,
		pages	: js_pages,
		per		: "CUSTOMER",
	},
	mounted(){
		this.per = this.group.TYPE;
		//checked
		
		$(".select_all").each(function(index){
			$cls = $(this).data('cls');
			$n_checked = $('body').find(".per_select[data-cls='"+$cls+"']:not(:checked)").length;
			if($n_checked == 0)
			{
				$(this).prop( "checked",true );
			}
		})
	},
	methods: {
		view_page(index){
			if(index == 'ADMIN_CUS' || this.per == index)
			{
				return true;
			}
			return false;
		},
		change_per(){
			this.per = $('#permission').val();
		},
		add_upd() {
			var x = this;
			$(".err_notification").addClass("d-none");
			
			var fo = $("#form_action");
			fo.find(".err_notification").addClass(E_HIDE);
			$MSG 		= fo.find('.form_msg').html();
			
			fo.ajaxSubmit({ 
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
					try {
						var obj = JSON.parse(x);
						if ("Error" in obj)
						{
							error_handler(x);
						}else if("id" in obj && obj.id != 0)
						{
							alert("تم الحفظ");
							setTimeout(
								function(){
									location.reload(); 
								}
								,100);
							
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
		},
		del(index) {
			if(this.group[index].STAFF != 0)
			{
				alert("هنالك موظفين مسجلين في هذه المجموعة");
				return;
			}
			if(confirm("هل انت متأكد من مسح هذه المجموعة؟"))
			{
				var x = this;
				var csrf = $("#csrf").val();
				
				$.post(URL+"permission/del_group",{'csrf':csrf,'id':this.group[index].ID},function(data,status,xhr){
					try {
						if ("Error" in data)
						{
							error_handler(data);
						}else if("id" in data && data.id == x.group[index].ID)
						{
							alert("تم الحذف");
							x.group.splice(index,1);
						}
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				},'JSON');
			}
			return false;
		},
		
	}
});

//_______________________________________change pages permissions
$('.select_all').on("change",function() {
	$cls = $(this).attr('data-cls');
	
	$(".per_select[data-cls='"+$cls+"']").prop("checked",$(this).is(':checked') );
	
});

$('body').on("change","input.def_page",function() {
	$cls = $(this).data('cls');
	if($(this).is(':checked')) 
	{
		$(".per_select[data-cls='"+$cls+"']").prop( "checked",true );
		$(".select_all[data-cls='"+$cls+"']").prop( "checked",true );
	}
});

$('body').on("change","input.per_select",function() {
	$cls = $(this).attr('data-cls');
	$pg = $(this).attr('data-pg');
	if(!$(this).is(':checked') && $pg == "index") 
	{
		$('body').find(".per_select[data-cls='"+$cls+"']").prop("checked",false );
		$('body').find(".def_page[data-cls='"+$cls+"']").prop("checked",false );
	}
});
//////////////////////////////////////////////////////End
