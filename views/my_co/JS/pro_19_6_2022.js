vm_staff = new Vue({
	el: '#staff_info',
	data: {
		info: js_info,
		id_types: js_id_types,
		types: js_types,
		pkg: js_package,
		total: 0,
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
			}else if(typeof upg_pay.Error != "undefined")
			{
				alert("هنالك خطأ: "+upg_pay.Error);
			}else if(typeof upg_pay.id != "undefined" && upg_pay.id != 0)
			{
				alert("تمت عملية الدفع بنجاح, رقم الفاتورة "+upg_pay.id);
			}
		}
	},
	methods: {
		onSubmitupd(){
			var vu = this;
			var theform = $("#staff_form");
			theform.find(".err_notification").addClass(E_HIDE);
				
			theform.ajaxSubmit({ 
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
						}else if("id" in obj && obj.id != 0)
						{
							alert("تم تحديث البيانات")	
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
					alert("error "+response);
				},
				resetForm: false
			});
			
		},
		del_img(index){
			var conf = confirm("هل انت متأكد من مسح الملف :"+this.info.PROFILE.FILES[index].NAME);
			if(conf)
			{
				var csr = $("#csrf").val();
				var th = this;
				$.post(URL+"profile/del_img",{csrf:csr,file:this.info.PROFILE.FILES[index].NAME},function(data,status,xhr){
					try {
						alert(data);
						if(data == "تم حذف الملف")
						{
							var m_ind = $("#upd_index").val();
							th.info.PROFILE.FILES.splice(index,1)
						}
					}
					catch(err) {
						alert(err.message+"\n"+data);
					}
				});
			}
		},
		ch_price(){
			if($(".new_pkg:checked").length)
			{
				$price = $(".new_pkg:checked").data('price');
				$period= $("#vip_range").val();
				this.total = $price * $period;
				$("#vip_price").val(this.total);
			}
		},
		ch_cobon()
		{
			$v = event.target;
			$x =  $v.value;
			$th= this;
			$.getJSON(URL+"my_co/cobon/"+$x+"/VIP", function(obj){
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
					$total = $th.total;
					if($amount < 1)
					{
						$total = $total - ($amount * $total);
					}else
					{
						$total = $total - $amount;
					}
					$("#vip_price").val($total);
					$th.total = $total;
				}
			});
		
		},
		
	}
});

function modal_element($type,$id)
{
	switch($type)
	{
		case "new_vip":
			var d ;
			
			if($(".new_pkg:checked").val() == vm_staff.info.PKG)
			{
				//update period
				if(vm_staff.info.BK_END.length)
				{
					d = new Date(vm_staff.info.BK_END);
				}else
				{
					d = new Date();
				}
			}else
			{
				d = new Date();
			}
			d.setFullYear(d.getFullYear() + parseInt($("#vip_range").val()));
			
			Vue.set(vm_staff.info, 'PKG', $(".new_pkg:checked").val());
			Vue.set(vm_staff.info, 'BK_END', d.toISOString().slice(0,10));
			
		break;
		case "new_reg":
		case "upd_reg":
			const myTimeout = setTimeout(function(){location.reload(); }, 5000);
		break;
	}
}

if($('#vip_land_form').length)
{
paylib.inlineForm({
	'key': JS_KEY,
	'form': document.getElementById('vip_land_form'),
	'autoSubmit': true,
	'callback': function(response) 
	{
		document.getElementById('paymentErrors').innerHTML = '';
		if (response.error) 
		{             
			paylib.handleError(document.getElementById('paymentErrors'), response); 
		}
	}
});
}