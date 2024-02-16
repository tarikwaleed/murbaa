<div><br><br><br><br><br></div>
<main id="staff_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> الموظفين  - <span v-if="!conf.admin || conf.no_user != null">المتاح {{conf.no_user - staff.length}}</span></h4>
	</div>
	
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="Staff_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>staff/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="اسم المستخدم" />
						</div>	
						<div class="col-sm mb-3">
							<input name="email" class="form-control" placeholder="البريد الإلكتروني" />
						</div>	
						<div class="col-sm mb-3">
							<input name="phone" class="form-control" placeholder="الهاتف" /> 
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3">
							<button v-if="conf.admin || conf.msg" type="button" class="btn btn-block btn-primary" v-on:click.prevent="message()"><i class="fa fa-send"></i> ارسال رسالة</button>
						</div>
						<div class="col-sm mb-3">
							<button v-if="conf.admin || conf.no_user == null || conf.no_user > staff.length"  type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_staff">
								إضافة مستخدم جديد <i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->

	<!--Staff List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th v-if="conf.admin || conf.msg"><input type="checkbox" id='msgs' v-on:change="change_msg()" /></th>
									<th>الرقم</th>
									<th>الصورة</th>
									<th>الاسم</th>
									<th>الهاتف</th>
									<th>البريد الإلكتروني</th>
									<th>الصلاحيات</th>
									<th colspan="3">الأجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in staff">
									<td v-if="conf.admin || conf.msg"><input type="checkbox" class="msgs" v-bind:data-id="x.ID" /></td>
									<td>{{index + 1}}</td>
									<td><img v-bind:src="x.IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100"/></td>
									<td>{{x.NAME}}</td>
									<td>{{x.PHONE}}</td>
									<td>{{x.EMAIL}}</td>
									<td>{{per_list[x.PER].NAME}}</td>
									<td v-if="x.ADMIN == 1" colspan="2">مدير النظام / المستخدم الحالي</td>
									<td v-else=""><button v-on:click.prevent="update_staff(index)" type="button" data-toggle="modal" data-target="#upd_staff" class="btn rounded btn-primary btn-sm" > تحديث</button></td>
									<td v-if="x.ADMIN != 1">
										<button v-if="x.ACTIVE == 1" v-on:click.prevent="active(index)" v-else="" class='btn rounded btn-warning btn-sm '>تجميد</button>
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
	
	<!-- Modal For add new Staff -->
	<div id="new_staff" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_staff_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_staff_form" method="post" action="<?php echo URL?>staff/new_staff" data-model="new_staff" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_staff_title"><i class="fa fa-plus"></i> إضافة مستخدم</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_name" class="">اسم المستخدم</label>
							<input type="text" class="form-control" name="new_name" id="new_name" placeholder=" ادخل اسم المستخدم" required />
							<div class="err_notification" id="valid_new_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_permission">الصلاحيات</label>
							<select name="new_permission" id="new_permission" class="form-control" >
								<option value="" >إختار الصلاحيات</option>
								<option v-for="(x,index) in per_list" v-bind:value="index">{{x.NAME}}</option>
							</select>
							<div class="err_notification " id="valid_new_permission">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="new_nat_no">رقم الهوية</label>
							<input type="text" name="new_nat_no" id="new_nat_no" class="form-control" />
							<div class="err_notification " id="valid_new_nat_no">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_new_nat_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="col-auto">
							<label for="new_phone">الهاتف</label>
							<input type="phone" name="new_phone" id="new_phone" class="form-control" />
							<div class="err_notification " id="valid_new_phone">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_new_phone">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="col-auto">
							<label for="new_email">البريد الإلكتروني</label>
							<input type="email" name="new_email" id="new_email" class="form-control" />
							<div class="err_notification " id="valid_new_email">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_new_email">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="col-auto">
							<label for="new_pass">كلمة المرور</label>
							<input name="new_pass" type="password" class="form-control"/>
							<div class="w3-hide err_notification " id="valid_new_pass">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_conf_pass">تأكيد كلمة المرور</label>
							<input name="new_conf_pass" type="password" class="form-control"/>
							<div class="w3-hide err_notification " id="valid_new_conf_pass">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط المستخدم</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط المستخدم</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update Staff -->
	<div id="upd_staff" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_staff_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form text-right" id="upd_staff_form" method="post" action="<?php echo URL?>staff/upd_staff" data-model="upd_staff" data-type="upd">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_staff_title"><i class="fa fa-pencil"></i> تعديل بيانات المستخدم</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" id="upd_index" :value="upd_staff.INDEX" />
						<input type="hidden" id="upd_id" name="upd_id" :value="upd_staff.ID" />
						<div class="col-auto">
							<label for="upd_name" class="">اسم المستخدم</label>
							<input type="text" class="form-control" name="upd_name" id="upd_name" :value="upd_staff.NAME" placeholder=" ادخل اسم المستخدم" required />
							<div class="err_notification" id="valid_upd_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="upd_permission">الصلاحيات</label>
							<select name="upd_permission" id="upd_permission" class="form-control" >
								<option value="" >إختار الصلاحيات</option>
								<option v-for="(x,index) in per_list" v-bind:value="index" :selected="index == upd_staff.PER">{{x.NAME}}</option>
							</select>
							<div class="err_notification " id="valid_upd_permission">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="new_nat_no">رقم الهوية</label>
							<input type="text" name="upd_nat_no" id="upd_nat_no" :value="upd_staff.NAT_NO" class="form-control" />
							<div class="err_notification " id="valid_upd_nat_no">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_upd_nat_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="col-auto">
							<label for="upd_phone">الهاتف</label>
							<input type="phone" name="upd_phone" id="upd_phone" :value="upd_staff.PHONE" class="form-control" />
							<div class="err_notification " id="valid_upd_phone">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_upd_phone">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="col-auto">
							<label for="upd_email">البريد الإلكتروني</label>
							<input type="email" name="upd_email" id="upd_email" :value="upd_staff.EMAIL" class="form-control" />
							<div class="err_notification " id="valid_upd_email">هنالك خطأ في هذا الحقل</div>
							<div class="err_notification " id="duplicate_upd_email">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
						</div>
						<div class="form_msg d-none">تعديل بيانات المستخدم</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط المستخدم</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For MSG staff -->
	<div id="msg_staff" class="modal fade" tabindex="-1" aria-labelledby="msg_staff_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 text-right" v-on:submit.prevent="send_msg" id="msg_staff_form" method="post" action="<?php echo URL?>staff/msg_staff" data-model="upd_staff" data-type="upd">
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
						<div class="col-auto">
							<input type="checkbox" name="sms_msg" class="form-control" value="SMS" />
							<label for="sms_msg">ارسال الرسالة بواسطة الرسائل النصية</label>
						</div>
						<div class="col-auto">
							<input type="checkbox" name="email_msg" class="form-control" value="EMAIL" checked />
							<label for="sms_msg">ارسال الرسالة بواسطة البريد الإلكتروني</label>
						</div>
						<div class="form_msg d-none">تعديل بيانات المستخدم</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-send"></i> ارسال</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</main>
<script>
	var js_conf_list = <?php echo json_encode($this->conf_list);?>;
	var js_per_list = <?php echo json_encode($this->per_list);?>;
</script>
