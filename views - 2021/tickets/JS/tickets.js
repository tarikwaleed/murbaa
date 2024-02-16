var interval = null;
vm_tickets = new Vue({
	el: '#staff_settings',
	data: {
		tickets: [],
		curr_user: js_user,
	},
	mounted(){
		this.onSubmitSearch();
		
	},
	methods: {
		onSubmitSearch() {
			var x = this;
			var cu = $('#ticket_search').attr('action');
			$.post(cu,$('#ticket_search').serializeArray(),function(data,status,xhr){
				try {
					x.tickets = data;
					if(interval != null)
					{
						clearInterval(interval);
					}
					interval = setInterval(x.get_all_chats, 10000); // 1Minits
					
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		},
		get_all_chats()
		{
			$th = this;
			this.tickets.forEach(function(item, index)
			{
				$th.get_chat(item,index);
			})
		},
		get_chat(item,index)
		{
			$th = this;
			if(item.CHAT_DATA.length == 0)
			{
				$last = 0;
			}else
			{
				$last = item.CHAT_DATA[item.CHAT_DATA.length - 1].ID;
			}
			$.post(URL+"tickets/get_chat/"+item.ID+"/"+$last,{},function(data,status,xhr)
			{
				try {
					if ("Error" in data)
					{
						alert("Error "+data.Error);
					}else if(data.length)
					{
						for($i = 0; $i<data.length; $i++)
						{
							if($last < data[$i].ID)
							{
								Vue.set($th.tickets[index].CHAT_DATA
										,$th.tickets[index].CHAT_DATA.length
										,data[$i]
										);
							}
						}
					}
				}
				catch(err) {
					alert(err.message+"\n"+data);
				}
			},'JSON');
		}
	}
});

//submit modal form
$(document).on('submit','.chat_action', function (e)
{
	$(this).find(".err_notification").addClass(E_HIDE);
	var room = $(this).find(".ticket_index").val();
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
			$da = JSON.parse(x);
			if ("Error" in $da)
			{
				alert("Error "+ $da.Error);
			}else if("ok" in $da)
			{
				alert($da.ok+" -- "+room);
				vm_tickets.get_chat(vm_tickets.tickets[room],room);
				close_form_dialog($fr);
			}else
			{
				alert(x);
			}
			
		},
		error:function(response,status,xhr){
			alert("error "+JSON.stringify(response));
		},
		resetForm: false
	});
	return false;
})


function modal_element($type,$id)
{
	switch($type)
	{
		case "new":
			new_modal_element($id);
		break;
	}
}

function new_modal_element($id = 0)
{
	alert("رقم التذكرة "+$id);
	var obj = {};
	obj.ID 			= $id;
	obj.NAME 		= "";
	obj.CO 			= "";
	obj.DESC 		= $("#msg_comm").val();
	obj.CHAT_DATA	= [];
	
	vm_tickets.tickets.push(obj);
}


