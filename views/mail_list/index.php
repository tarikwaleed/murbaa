<div><br><br><br><br><br></div>
<main id="staff_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> العملاء</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="Staff_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>mail_list/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="email" class="form-control" placeholder="البريد الإلكتروني" />
						</div>	
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3">
							<button type="button" class="btn btn-block btn-primary" v-on:click.prevent="message()"><i class="fa fa-send"></i> ارسال رسالة</button>
						</div>
						<div class="col-sm mb-3">
							<!--button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_staff">إضافة مالك جديد <i class="fa fa-plus"></i></button-->
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->

	<!--owner List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th><input type="checkbox" id='msgs' v-on:change="change_msg()" /></th>
									<th>البريد الإلكتروني</th>
									<th>تاريخ الانضمام</th>
									<th>الأجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in owner">
									<td><input type="checkbox" class="msgs" v-bind:data-id="x.EMAIL" /></td>
									<td>{{x.EMAIL}}</td>
									<td>{{x.CREATE}}</td>
									<td>
										<button v-on:click.prevent="active(index)" class='btn btn-warning rounded btn-sm '>حذف</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For MSG owner -->
	<div id="msg_staff" class="modal fade" tabindex="-1" aria-labelledby="msg_staff_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 text-right" v-on:submit.prevent="send_msg" id="msg_staff_form" method="post" action="<?php echo URL?>mail_list/msg_mail" data-model="msg_staff" data-type="msg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="msg_staff_title"><i class="fa fa-send"></i> ارسال رسالة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" v-for="x in msg_user" name="msg_user[]" :value="x" />
						<div class="col-auto">
							<label for="msg_comm">الرسالة</label>
							<textarea type="text" name="msg_comm" id="msg_comm" class="form-control"></textarea>
							<div class="err_notification " id="valid_msg_comm" >هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم ارسال الرسائل</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-send"></i> ارسال</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div >	
	
</main>
<script>
</script>