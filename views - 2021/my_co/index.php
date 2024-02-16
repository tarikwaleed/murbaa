<div><br><br><br><br><br></div>
<main id="staff_info" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="mb-3 border-bottom">
		<h4 class="h4"> <i class="fa fa-user-circle text-primary"></i> الإعدادات </h4>
	</div>
	
	<div class="d-flex justify-content-center ">
		<form class="g-3 border p-5 mb-3" id="staff_form"  v-on:submit.prevent="onSubmitupd" method="post" action="<?php echo URL?>my_co/upd"  enctype="multipart/form-data">
			<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
			<div class="row mb-3">
				<div class="col-sm">
					<img id="new_pro_image" v-bind:src="info.IMG" width="150px" height="150px" class="img-thumbnail rounded-circle mb-1" alt="..."> <br />
					<input type="file" name="new_pro_image" class="form-control-small file-upload image_upload form-control-file" data-id="new_pro_image" id="img" accept="image/*">
					<div class="d-none err_notification" id="valid_new_co_address">this field required</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<label for="new_co_name" class="">الاسم</label>
					<input type="text" v-bind:value="info.NAME" class="form-control" name="new_co_name" id="new_co_name" placeholder=" ادخل اسم المستخدم" required>
					<div class="d-none err_notification" id="valid_new_co_name">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_co_email" class="">البريد الإلكتروني</label>
					<input id="new_co_email" name="new_co_email" type="email" v-bind:value="info.EMAIL" class="form-control" required />
				</div>
				<div class="col-sm">
					<label for="new_co_phone" class="">رقم الهاتف</label>
					<input type="text" v-bind:value="info.PHONE" class="form-control" name="new_co_phone" id="new_co_phone" placeholder=" ادخل رقم الهاتف" >
					<div class="d-none err_notification" id="valid_new_co_phone">this field required</div>
					<div class="w3-hide err_notification w3-small w3-text-red" id="duplicate_new_co_phone">duplicate Phone No </div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_co_address" class="">العنوان</label>
					<input v-bind:value="info.ADDRESS" type="text" class="form-control" name="new_co_address" id="new_co_address" placeholder=" ادخل العنوان" />
					<div class="d-none err_notification" id="valid_new_co_address">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_co_desc" class="">الوصف</label>
					<input v-bind:value="info.DESCR" type="text" class="form-control" name="new_co_desc" id="new_co_desc" placeholder=" ادخل الوصف" />
					<div class="d-none err_notification" id="valid_new_co_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					الباقة الحالية: {{pkg[info.PKG].NAME}}<span v-if="info.BK_END !== null && info.BK_END.length"><br/> ستنتهي في {{info.BK_END}}</span> 
				</div>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#vip_pkg" ><i class="fa fa-warning"></i> طلب تحديث الباقة</button>
				</div>
			</div>
			<div class="row mb-3" v-if="info.CO_ACCEPT !== null && info.CO_ACCEPT != 0">
				الحساب موثق - ينتهي بتاريخ {{info.COMM_EXPERD}}
				<br/>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#upd_reg" ><i class="fa fa-warning"></i> تعديل بيانات توثيق الحساب</button>
				</div>
			</div>
			<div class="row mb-3" v-else-if="info.REG_ID !== null && info.CO_ACCEPT == null">
				التوثيق تحت المراجعة بالرقم {{info.REG_ID}}
			</div>
			<div class="row mb-3" v-else="">
				<div class="col-sm" v-if="info.CO_ACCEPT == 0"> تم رفض التوثيق</div>
				<div class="col-sm" v-else-if="info.COMM_EXPERD !== null"> انتهى توثيق الحساب بتاريخ: {{info.COMM_EXPERD}}</div>
				<div class="col-sm" v-else="">لم يتم توثيق الحساب</div>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#com_reg" ><i class="fa fa-warning"></i> توثيق الحساب</button>
				</div>
			</div>
			<div class="row">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> تحديث البيانات</button>
			</div>
		</form>
	</div>
	
	<!-- Modal For vip land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="vip_pkg">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form" method="post" action="<?php echo URL?>my_co/upgrade"  data-type="add">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> ترقية العقار للباقة المميزة</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="row m-1">
							 <div class="table-responsive">
								<table class="table table-bordered table-striped table-hover table-head-fixed text-right">
									<thead>
										<tr>
											<th>اختيار</th>
											<th>الاسم</th>
											<th>النجوم</th>
											<th>السعر</th>
											<th>المستخدمين</th>
											<th>الرسائل</th>
											<th>المساحة الإعلانية</th>
											<th>المميزة</th>
											<th>الدفع للإعلان</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(x ,index) in pkg">
											<td>
												<p v-if="x.ID == info.PKG">الباقة الحالية <input type="radio" class="new_pkg d-none" checked="1" name="new_pkg" :value="x.ID" :data-price="x.PRICE" @change="ch_price()"></p>
												<input v-else="" type="radio" class="new_pkg" name="new_pkg" :value="x.ID" :data-price="x.PRICE" @change="ch_price()">
											</td>
											<td>{{x.NAME}}</td>
											<td>{{x.STARS}}</td>
											<td>{{x.PRICE}}</td>
											<td>{{x.USERS}}</td>
											<td>{{x.MSG}}</td>
											<td>{{x.ADV_AREA}}</td>
											<td>{{x.VIP}}</td>
											<td><i v-if="x.ADV_PAY == 1" class="fa text-success fa-check" title="دفع"></i></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="d-none err_notification" id="valid_new_pkg">this field required</div>
						</div>
						<div class="row">
							<!--div class="col-sm mb-3 new_home_area">
								<label for="vip_range" class="">المدة: <span id="vip_range_val">1</span> سنة/ سنوات</label>
								<input type="range" :min="1" max="10" value="1" data-view="vip_range_val" @change="ch_price" id="vip_range" name="vip_range" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_vip_range">this field required</div>
							</div-->
							<input type="hidden" value="1" data-view="vip_range_val" @change="ch_price" id="vip_range" name="vip_range" class="form-control range_input" />
								
							<div class="col-sm mb-3">
								<label for="vip_price" class="">السعر</label>
								<input type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" value="" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row" :class="(total ==0)?'d-none':''">
							<div class="col-sm mb-3">
								<label for="vip_card" class="">رقم البطاقة</label>
								<input type="text" data-paylib="number" lang="en" dir="ltr" autocomplete="off" size="20" class="form-control" value="" placeholder="رقم البطاقة" />
								<div class="d-none err_notification" id="valid_vip_card">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label>تاريخ انتهاء الصلاحية (YYYY/MM)</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="text" data-paylib="expmonth" autocomplete="off"  size="3" placeholder="الشهر">
										<input type="text" data-paylib="expyear" autocomplete="off"  size="5" placeholder="السنة">
									</div>
								</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_pass" class="">رقم CVV</label>
								<input type="text" lang="en" data-paylib="cvv" size="4" class="form-control" value="" placeholder="CVV" />
								<div class="d-none err_notification" lang="en" autocomplete="off" id="valid_vip_pass">this field required</div>
							</div>
						</div>
						<div class="row" class="" id="paymentErrors"></div>
						<div class="form_msg d-none">تم تعديل الباقة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> تعديل الباقة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="com_reg">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form" method="post" action="<?php echo URL?>my_co/reg" data-model="com_reg" data-type="new_reg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="reg_title"><i class="fa fa-edit"></i> توثيق بيانات المكتب</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="row">
							<!--div class="col-sm mb-3">
								<label for="reg_no" class="">رقم السجل التجاري أو رقم رخصة العمل الحر</label>
								<input type="text" lang="en" name="reg_no" id="reg_no" class="form-control" value="" placeholder="" required />
								<div class="d-none err_notification" id="valid_reg_no">this field required</div>
							</div-->
							<div class="col-sm mb-3">
								<label for="reg_no" class="">رقم المعلن</label>
								<input type="text" lang="en" name="reg_co_no" id="reg_co_no" class="form-control" value="" placeholder="" />
								<div class="d-none err_notification" id="valid_reg_co_no">this field required</div>
							</div>
							<div class="col-sm">
								<label for="new_reg_file" class="">ملف السجل التجاري أو ملف رخصة العمل الحر أو الهوية</label>
								<input type="file" name="new_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" required />
								<div class="d-none err_notification" id="valid_new_reg_file">this field required</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm mb-3">
								<label for="reg_real_no" class="">رقم ترخيص الهيئة العامة للعقار</label>
								<input type="text" lang="en" name="reg_real_no" id="reg_real_no" class="form-control" value="" placeholder="" />
								<div class="d-none err_notification" id="valid_reg_real_no">this field required</div>
							</div>
							<div class="col-sm mb-3">
								رقم ترخيص الهيئة العامة للعقار مطلوب فقط إذا كان لديك عقار تريد ان تعلن عنه كمسوق أو منشأ
							</div>
						</div>
						
						<div class="form_msg d-none">تم إرسال البيانات</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفظ</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For upd Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_reg">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form" method="post" action="<?php echo URL?>my_co/upd_reg" data-model="upd_reg" data-type="upd_reg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_reg_title"><i class="fa fa-edit"></i> تحديث توثيق بيانات المكتب</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" name="id" :value="info.REG_ID" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="reg_no" class="">رقم المعلن</label>
								<input type="text" lang="en" name="upd_reg_co_no" id="upd_reg_co_no" class="form-control" :value="info.CO_WORK_NO" placeholder="" required />
								<div class="d-none err_notification" id="valid_upd_reg_co_no">this field required</div>
							</div>
							<!--div class="col-sm mb-3">
								<label for="upd_reg_no" class="">رقم السجل التجاري أو رقم رخصة العمل الحر</label>
								<input type="text" lang="en" name="upd_reg_no" id="upd_reg_no" class="form-control" :value="info.CO_REG_NO" placeholder="" required />
								<div class="d-none err_notification" id="valid_upd_reg_no">this field required</div>
							</div-->
							<div class="col-sm">
								<label for="upd_reg_file" class="">ملف السجل التجاري أو ملف رخصة العمل الحر أو الهوية</label>
								<input type="file" name="upd_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" />
								<div class="d-none err_notification" id="valid_upd_reg_file">this field required</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm mb-3">
								<label for="upd_reg_real_no" class="">رقم ترخيص الهيئة العامة للعقار</label>
								<input type="text" lang="en" name="upd_reg_real_no" id="upd_reg_real_no" class="form-control" :value="info.CO_REAL_NO" placeholder="" />
								<div class="d-none err_notification" id="valid_upd_reg_real_no">this field required</div>
							</div>
							<div class="col-sm mb-3">
								رقم ترخيص الهيئة العامة للعقار مطلوب فقط إذا كان لديك عقار تريد أن تعلن عنه كمسوق أو منشأ
							</div>
							
						</div>
						
						<div class="form_msg d-none">تم إرسال البيانات</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفظ</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</main>
<script>
	var js_package 	= <?php echo json_encode($this->package); ?>;
	var js_info 	= <?php echo $this->sys_info; ?>;
	var upg_pay		= <?php echo (!empty($this->upgrade))?json_encode($this->upgrade):"''"; ?>;
	var JS_KEY		= <?php echo "'".P_JS_KEY."'"; ?>
</script>

<script src="<?php echo P_JS_FILE; ?>"></script>