vm_service = new Vue({
	el: '.vue_area_div',
	data: {
		config		: js_config,
		SER			: js_ser,
		cont_type	: js_cont_type,
		cont_status	: js_cont_status,
		service_list:[],
		upd_service:{},
		timer		:0,
		//types: js_types,
		//pays: js_pay,
		//
		//
	},
	mounted(){
		if($('#chatRoomData').length)
		{
			this.fetchChat();  
			this.timer = setInterval(this.fetchChat, 10000);  
		}
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
	methods: {
		ch_price()
		{
			$v = event.target;
			$amount = $v.value;
			$per = this.config.SERVICE_PERCENTAGE / 100;
			$amount = $amount - ($amount * $per);
			$id = $v.getAttribute('data-per_id');
			$("#"+$id).val($amount);
		},
		async fetchChat() {
			$off = this.SER.OFFER_ID;
			last = Object.keys(this.SER.CHAT)[Object.keys(this.SER.CHAT).length-1];
			const res = await fetch(URL+"services/get_chat/"+$off+"/"+last);  
			const data = await res.json();
			for (const [key, val] of Object.entries(data)) {
				if(!this.SER.CHAT[key])
				{
					Vue.set(this.SER.CHAT,key,val);
				}
			}
		},
		finish(){
			var crt= $("#csrf").val();
			var off = this.SER.OFFER_ID;
			var lib = this.SER;
			$.post(URL+"services/finish",{csrf:crt,id:off},function(data,status,xhr){
				try {
					var obj = JSON.parse(data);
					if ("Error" in obj)
					{
						alert("ll: "+obj.Error)
					}else
					{
						alert("تم التعديل");
						location.reload();
						lib[index]['STATUS'] = (lib[index]['STATUS'] == "NEW")?"FREEZ":"NEW";
					}
				}
				catch(err) {
					alert(err.message+"\n\n\n"+data);
				}
			})
		},
		
	}
});


function modal_element($type,$id)
{
	//vm_service.onSubmitSearch();
}

$(document).on('submit','.offer_form', function (e)
{
	$(this).find(".err_notification").addClass(E_HIDE);
	$MSG 		= $(this).find('.form_msg').html();
	$ID 		= $(this).attr('id');
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
				}else if("id" in obj && obj.id != 0)
				{
					alert($MSG);
					location.reload(); 
				}else
				{
					alert(x);
				}
			}
			catch(err) {
				alert(err.message+"\n\n\n"+x);
			}
		},
		error:function(response,status,xhr){
			alert("error "+JSON.stringify(response));
		},
		resetForm: false
	});
	return false;
})

$(document).on('submit','#chat_form', function (e)
{
	$(this).find(".err_notification").addClass(E_HIDE);
	$ID 		= $(this).attr('id');
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
				}else if("id" in obj && obj.id != 0)
				{
					vm_service.fetchChat();
				}else
				{
					alert(x);
				}
			}
			catch(err) {
				alert(err.message+"\n\n\n"+x);
			}
		},
		error:function(response,status,xhr){
			alert("error "+JSON.stringify(response));
		},
		resetForm: true
	});
	return false;
})

if($('#accept_offer_form').length)
{
	paylib.inlineForm({
		'key': JS_KEY,
		'form': document.getElementById('accept_offer_form'),
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
