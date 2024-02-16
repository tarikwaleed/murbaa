<div><br><br><br><br><br></div>
<main id="types_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> أنواع العقارات</h4>
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
									<th>الغرض</th>
									<th>مبني</th>
									<th>عدد العقارات</th>
									<th colspan="2">الإجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in types">
									<td>{{index + 1}}</td>
									<td>{{x.NAME}}</td>
									<td>{{l_status[x.TY_FOR]}}</td>
									<td v-if="x.BUILD == 0">أرض</td>
									<td v-else-if="x.BUILD == 1">مبني</td>
									<td v-else-if="x.BUILD == 2">مزرعة</td>
									<td>{{x.LANDS}}</td>
									<td><button v-on:click.prevent="update_type(index)" data-toggle="modal" data-target="#upd_type" class='btn btn-warning rounded btn-sm '>تعديل</button></td>
									<td v-if="x.LANDS != 0">--</td>
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
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>land_types/add_type" data-model="new_type" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_type_title"><i class="fa fa-plus"></i> إضافة نوع عقار</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_name" class="">اسم النوع عقار</label>
							<input type="text" class="form-control" name="new_name" id="new_name" placeholder=" ادخل اسم النوع عقار" required />
							<div class="err_notification" id="valid_new_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_for">الحالة</label>
							<select name="new_for" id="new_for" class="form-control">
								<option value="" selected disabled >حدد الحالة</option>
								<option  v-for="(x,id) in l_status" :value="id">{{x}}</option>
							</select>
						</div>	
						<div class="col-sm mb-3"><label for="new_phone">الحالة</label></div>
						<div class="col-auto">
							<table class="table">
								<tr>
									<td><label for="new_phone">أرض</label></td>
									<td><input type="radio" name="new_build" value="0" class="form-control" /></td>
								</tr>
								<tr>
									<td><label for="new_phone">مبني</label></td>
									<td><input type="radio" name="new_build" value="1" class="form-control" /></td>
								</tr>
								<tr>
									<td><label for="new_phone">مزرعة</label></td>
									<td><input type="radio" name="new_build" value="2" class="form-control" /></td>
								</tr>
							</table>
							<div class="err_notification " id="valid_new_build">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط النوع عقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط النوع عقار</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update land_type -->
	<div id="upd_type" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_type_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>land_types/upd_type" data-model="upd_type" data-type="upd">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_type_title"><i class="fa fa-plus"></i> تعديل نوع عقار</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_type.ID" />
						<div class="col-auto">
							<label for="upd_name" class="">اسم النوع عقار</label>
							<input type="text" class="form-control" name="upd_name" id="upd_name" :value="upd_type.NAME" required />
							<div class="err_notification" id="valid_upd_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="upd_for">الحالة</label>
							<select name="upd_for" id="upd_for" class="form-control">
								<option  v-for="(x,id) in l_status" :value="id" :selected="id == upd_type.TY_FOR">{{x}}</option>
							</select>
						</div>
						<div class="col-sm mb-3"><label for="upd_build">الحالة</label></div>
						<div class="col-auto">
							<label for="upd_build">أرض</label>
							<input type="radio" name="upd_build" :checked="0 == upd_type.BUILD" value="0" class="form-control" />
							<label for="new_phone">مبني</label>
							<input type="radio" name="upd_build" :checked="1 == upd_type.BUILD" value="1" class="form-control" />
							<label for="new_phone">مزرعة</label>
							<input type="radio" name="upd_build" :checked="2 == upd_type.BUILD" value="2" class="form-control" />
							<div class="err_notification " id="valid_upd_build">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم تعديل النوع عقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> تعديل النوع عقار</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</main>
<script>
	var js_types 		= <?php echo json_encode($this->types); ?>;
	var js_status 	= <?php echo json_encode(lib::$land_for); ?>;
</script>