<div><br><br><br><br><br></div>
<main id="types_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> أنواع الباقات</h4>
	</div>
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-warning text-primary"></i> تنبيه, لكي يعمل النظام بدون مشاكل لا بد من توفر باقة مجانية (سعرها 0), وكذلك كل باقة بسعر يختلف عن الباقات الاخرى</h4>
	</div>
	
	<!--owner List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
				<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#new_type">إضافة نوع جديد <i class="fa fa-plus"></i></button>
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>الرقم</th>
									<th>الاسم</th>
									<th>النجوم</th>
									<th>السعر</th>
									<th>عدد المستخدمين</th>
									<th>رسائل المستخدمين</th>
									<th>عدد المساحات الاعلانية</th>
									<th>الدفع للاعلان</th>
									<th>عدد مساحات VIP</th>
									<th>عدد العملاء</th>
									<th colspan="2">الأجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in types">
									<td>{{index + 1}}</td>
									<td>{{x.NAME}}</td>
									<td>{{x.STARS}}</td>
									<td>{{x.PRICE}}</td>
									<td>{{x.USERS}}</td>
									<td>{{x.MSG}}</td>
									<td>{{x.ADV}}</td>
									<td>{{x.ADV_PAY}}</td>
									<td>{{x.VIP}}</td>
									<td>{{x.CO_NO}}</td>
									<td><button v-on:click.prevent="update_type(index)" data-toggle="modal" data-target="#upd_type" class='btn btn-warning rounded btn-sm '>تعديل</button></td>
									<td v-if="x.CO_NO != 0">--</td>
									<td v-else=""><button v-on:click.prevent="del(index)" class='btn btn-danger rounded btn-sm '>حذف</button></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For add new land_type -->
	<div id="new_type" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_type_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>pkg_types/add_type" data-model="new_type" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_type_title"><i class="fa fa-plus"></i> إضافة باقة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_name" class="">اسم الباقة</label>
								<input type="text" class="form-control" name="new_name" id="new_name" placeholder=" ادخل اسم الباقة" required />
								<div class="err_notification" id="valid_new_name">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_user" class="">عدد المستخدمين</label>
								<input type="number" lang="en" class="form-control" name="new_user" id="new_user" min="1" placeholder=" ادخل عدد المستخدمين" required />
								<div class="err_notification" id="valid_new_user">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_msg">رسائل المستخدمين</label>
								<input type="checkbox" name="new_msg" id="new_msg" value="1" class="form-control" />
								<div class="err_notification " id="valid_new_msg">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_price" class="">السعر</label>
								<input type="number" lang="en" class="form-control" name="new_price" id="new_price" min="0" placeholder=" ادخل السعر" required />
								<div class="err_notification" id="valid_new_price">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_stars">النجوم</label>
								<input type="number" lang="en" class="form-control" name="new_stars" id="new_stars" min="1" max="5" placeholder=" ادخل النجوم" required />
								<div class="err_notification " id="valid_new_stars">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_adv" class="">المساحات الإعلانية</label>
								<input type="number" lang="en" class="form-control" name="new_adv" id="new_adv" min="1" placeholder=" ادخل عدد المساحات الإعلانية" required />
								<div class="err_notification" id="valid_new_adv">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_adv_pay" class="">الدقع للمساحات الإعلانية</label>
								<input type="checkbox" lang="en" class="form-control" name="new_adv_pay" id="new_adv_pay" value="1" />
								<div class="err_notification" id="valid_new_adv">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_vip">المساحات VIP</label>
								<input type="number" lang="en" class="form-control" name="new_vip" id="new_vip" min="0" placeholder=" ادخل عدد مساحات VIP" required />
								<div class="err_notification " id="valid_new_vip">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="form_msg d-none">تم حفط الباقة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الباقة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update land_type -->
	<div id="upd_type" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_type_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>pkg_types/upd_type" data-model="upd_type" data-type="upd">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_type_title"><i class="fa fa-plus"></i> تعديل باقة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_type.ID" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_name" class="">اسم الباقة</label>
								<input type="text" class="form-control" name="upd_name" id="upd_name" :value="upd_type.NAME" placeholder=" ادخل اسم الباقة" required />
								<div class="err_notification" id="valid_upd_name">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_user" class="">عدد المستخدمين</label>
								<input type="number" lang="en" class="form-control" name="upd_user" id="upd_user" min="1" :value="upd_type.USERS" placeholder=" ادخل عدد المستخدمين" required />
								<div class="err_notification" id="valid_upd_user">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_msg">رسائل المستخدمين</label>
								<input type="checkbox" lang="en" name="upd_msg" id="upd_msg" value="1" :checked="upd_type.MSG == 1" class="form-control" />
								<div class="err_notification " id="valid_upd_msg">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_price" class="">السعر</label>
								<input type="number" lang="en" class="form-control" name="upd_price" id="upd_price" min="0" :value="upd_type.PRICE" placeholder=" ادخل السعر" required />
								<div class="err_notification" id="valid_upd_price">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_stars">النجوم</label>
								<input type="number" lang="en" class="form-control" name="upd_stars" id="upd_stars" min="1" max="5" :value="upd_type.STARS" placeholder=" ادخل النجوم" required />
								<div class="err_notification " id="valid_upd_stars">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_adv" class="">المساحات الإعلانية</label>
								<input type="number" lang="en" class="form-control" name="upd_adv" id="upd_adv" min="1" :value="upd_type.ADV" placeholder=" ادخل عدد المساحات الإعلانية" required />
								<div class="err_notification" id="valid_upd_adv">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_adv_pay" class="">الدقع للمساحات الإعلانية</label>
								<input type="checkbox" lang="en" class="form-control" name="upd_adv_pay" id="upd_adv_pay" value="1" :checked="upd_type.ADV_PAY == 1" />
								<div class="err_notification" id="valid_new_adv">هنالك خطأ في هذا الحقل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_vip">المساحات VIP</label>
								<input type="number" lang="en" class="form-control" name="upd_vip" id="upd_vip" min="0" :value="upd_type.VIP" placeholder=" ادخل عدد مساحات VIP" required />
								<div class="err_notification " id="valid_upd_vip">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
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
</main>
<script>
	var js_types 		= <?php echo json_encode($this->types); ?>;
</script>