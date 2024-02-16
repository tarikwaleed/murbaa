<div><br><br><br><br><br></div>
<main id="staff_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> العملاء</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="Staff_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>customer/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="الاسم" />
						</div>	
						<div class="col-sm mb-3">
							<input name="phone" class="form-control" placeholder="الهاتف" /> 
						</div>	
						<div class="col-sm mb-3">
							<select name="package" id="package" class="form-control" >
								<option value="" > الباقة</option>
								<option v-for="x in pack" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3">
							<button type="button" class="btn btn-block btn-primary" v-on:click.prevent="message()"><i class="fa fa-send"></i> إرسال رسالة</button>
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
									<th>الرقم</th>
									<th>الصورة</th>
									<th>الاسم</th>
									<th>الهاتف</th>
									<th>البريد الإلكتروني</th>
									<th>عدد العقارات</th>
									<th>عدد المستخدمين</th>
									<th>الباقة</th>
									<th>توثيق</th>
									<th>الإجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in owner">
									<td><input type="checkbox" class="msgs" v-bind:data-id="x.ID" /></td>
									<td>{{index + 1}}</td>
									<td><img v-bind:src="x.IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100"/></td>
									<td><a target="_blank" :href="x.LINK">{{x.NAME}}</a></td>
									<td>{{x.PHONE}}</td>
									<td>{{x.EMAIL}}</td>
									<td>{{x.LANDS}}</td>
									<td>{{x.STAFF}}</td>
									<td>{{pack[x.PK_ID].NAME}}</td>
									<td v-if="x.ACCEPT !== null && x.ACCEPT != 0">
										ينتهي بتاريخ {{x.COMM_EXPERD}}
									</td>
									<td v-else-if="x.REG_ID !== null">
										 المراجعة بالرقم {{x.REG_ID}}
									</td>
									<td v-else-if="x.COMM_EXPERD !== null">
										 إنتهي بتاريخ: {{x.COMM_EXPERD}}
									</td>
									<td v-else="">
										 لم يتم توثيق الحساب
									</td>
									<td>
										<button v-if="x.ACTIVE == 1" v-on:click.prevent="active(index)" v-else="" class='btn btn-warning rounded btn-sm '>تجميد</button>
										<button v-else="" v-on:click.prevent="active(index)" v-else="" class='btn btn-success rounded btn-sm '>فك تجميد</button>
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
			<form class="row g-3 text-right" v-on:submit.prevent="send_msg" id="msg_staff_form" method="post" action="<?php echo URL?>customer/msg_customer" data-model="msg_staff" data-type="msg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="msg_staff_title"><i class="fa fa-send"></i> إرسال رسالة</h5>
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
						<div class="col-auto">
							<input type="checkbox" name="sms_msg" class="form-control" value="SMS" />
							<label for="sms_msg">إرسال الرسالة بواسطة الرسائل النصية</label>
						</div>
						<div class="col-auto">
							<input type="checkbox" name="email_msg" class="form-control" value="EMAIL" checked />
							<label for="sms_msg">إرسال الرسالة بواسطة البريد الإلكتروني</label>
						</div>
						<div class="form_msg d-none">تم إرسال الرسائل</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-send"></i> إرسال</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>	
	
</main>
<script>
	var js_package 		= <?php echo json_encode($this->package); ?>;
</script>