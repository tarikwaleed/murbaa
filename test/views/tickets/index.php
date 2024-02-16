<main id="staff_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div" style="margin-top:100px;">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> تذاكر العملاء</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section ">
		<div class="container">
			<form class="filter-form" id="ticket_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>tickets/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="no" class="form-control" placeholder="رقم التذكرة" value="<?php echo $this->curr_no?>" />
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3" v-if="curr_user != 'ADMIN'">
							<button type="button" data-toggle="modal" data-target="#new_ticket" class="btn btn-block btn-success"><i class="fa fa-plus"></i> تذكرة جديدة</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->

	<!--Tickets List-->
	<div id="accordion">
		<div class="card mb-1" v-for="(tk,tk_index) in tickets">
			<div class="card-header" :id="'ticketRoom_'+tk_index">
				<h5 class="mb-0">
					<button class="btn btn-link collapsed" data-toggle="collapse" :data-target="'#ticketRoomData_'+tk_index" aria-expanded="false" :aria-controls="'ticketRoomData_'+tk_index">
						تذكرة رقم {{tk.ID}}
						<span v-if="curr_user == 'ADMIN'">
							-- العميل: {{tk.NAME}}
							<span v-if="tk.CO != ''"> -- {{tk.CO}}</span>
						</span>
					</button>
				</h5>
			</div>
			<div :id="'ticketRoomData_'+tk_index" :class="(tk_index==0)?'collapse ':'collapse'" :aria-labelledby="'ticketRoom_'+tk_index" data-parent="#accordion">
				<div class="card-body">
					<h6 class="mb-3 border p-3">{{tk.DESC}}</h6>
					<div v-for="(CHAT,index) in tk.CHAT_DATA" class="chat book_chat" :data-ticket="tk.ID"  :data-msg="CHAT.ID" >
						<div class="chat-user mt-3">
							<a class="avatar m-0">
								<img v-bind:src="CHAT.FR_IMG" alt="..." class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" />
								{{CHAT.FR_NAME}}
							</a>
							<span class="chat-time mt-1">{{CHAT.DATE}}</span>
						</div>
						<div class="chat-detail border-bottom">
							<div class="chat-message">
								<p v-html="CHAT.TEXT"></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="chat-footer p-3 bg-white">
						<form class="d-flex align-items-center chat_action" method="post" action="<?php echo URL?>tickets/addchat">
							<input type="hidden" class="hid_info" name="csrf" id="csrf" value="<?php echo lib::get_CSRF(); ?>" />
							<input type="hidden" class="hid_info" name="ticket_id" v-bind:value="tk.ID" />
							<input type="hidden" class="hid_info ticket_index" v-bind:value="tk_index" />
							<div class="input-group">
								<input type="text" name="chat_msg" class="form-control mr-3" placeholder="الرسالة">
								<button type="submit" class="btn btn-primary d-flex align-items-center"><i class="fa fa-paper-plane-o" aria-hidden="true"></i><span class="d-none d-lg-block ml-1">إرسال</span></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For MSG -->
	<div id="new_ticket" class="modal fade" tabindex="-1" aria-labelledby="new_ticket_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="new_ticket_form"  method="post" action="<?php echo URL?>tickets/add_ticket" data-model="new_ticket" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_ticket_title"><i class="fa fa-send"></i> تذكرة جديدة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="msg_comm">الرسالة</label>
							<textarea type="text" name="msg_comm" id="msg_comm" class="form-control"></textarea>
							<div class="err_notification " id="valid_msg_comm" >هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم فتح التذكرة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-send"></i> إرسال</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</main>
<script>
	var js_user = <?php echo "'".$this->curr_user."'"; ?>;
</script>