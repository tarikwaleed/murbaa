<main id="types_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<br/><br/><br/><br/><br/>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> الكبونات</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="cobon_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>cobon/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="الاسم" />
						</div>	
						<div class="col-sm mb-3">
							<select name="package" class="form-control" >
								<option value="" > الباقة</option>
								<option v-for="(x,index) in types" v-bind:value="index">{{x}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<select name="discount" class="form-control" >
								<option value="" > الخصم</option>
								<option v-for="(x,index) in pays" v-bind:value="index">{{x}}</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3">
							
						</div>
						<div class="col-sm mb-3">
							<button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_cobon">إضافة كوبون جديد <i class="fa fa-plus"></i></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->
	
	<!--cobon List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
				<div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>الاسم</th>
									<th>النوع</th>
									<th>المبلغ/ النسبة</th>
									<th>الكمية المتاحة</th>
									<th>الكمية المستخدمة</th>
									<th>تاريخ انتهاء الصلاحية</th>
									<th colspan="2">الأجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in cobon_list">
									<td>{{x.NAME}}</td>
									<td>{{types[x.TYPE]}}</td>
									<td>{{x.V_PRICE}}</td>
									<td>{{x.AMOUNT}}</td>
									<td>{{x.USED_BILL}}</td>
									<td>{{x.EXP}}</td>
									<td><button v-on:click.prevent="update_type(index)" data-toggle="modal" data-target="#upd_cobon" class='btn btn-warning rounded btn-sm '>تعديل</button></td>
									<td>
										<button v-if="x.ACTIVE == 1" v-on:click.prevent="active(index)" class='btn btn-warning rounded btn-sm '>تجميد</button>
										<button v-else="" v-on:click.prevent="active(index)"  class='btn btn-success rounded btn-sm '>فك تجميد</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For add new cobon -->
	<div id="new_cobon" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_cobon_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>cobon/add_cobon" data-model="new_cobon" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_cobon_title"><i class="fa fa-plus"></i> إضافة كوبون</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_name" class="">اسم الكبون</label>
							<input type="text" class="form-control" name="new_name" id="new_name" placeholder=" اسم / كود الكبون" required />
							<div class="err_notification" id="valid_new_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_type">الباقة</label>
							<select name="new_type" id="new_type" class="form-control">
								<option v-for="(x,index) in types" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification" id="valid_new_type">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-sm mb-3">
							<label for="new_discount">نوع الخصم</label>
							<select name="new_discount" class="form-control" >
								<option v-for="(x,index) in pays" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification " id="valid_new_discount">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_price" class="">قيمة الخصم</label>
							<input type="number" min="0" class="form-control" name="new_price" id="new_price" placeholder=" 0" required />
							<div class="err_notification" id="valid_new_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_amount" class="">الكمية المتاحة</label>
							<input type="number" min="1" class="form-control" name="new_amount" id="new_amount" placeholder=" 1" required />
							<div class="err_notification" id="valid_new_amount">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_exp" class="">تاريخ انتهاء الصلاحية</label>
							<input type="date" dateformat="d-M-y" class="form-control datepicker" name="new_exp" id="new_exp" placeholder=" تاريخ انتهاء الصلاحية" required />
							<div class="err_notification" id="valid_new_exp">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الكبون</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الكبون</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update cobon -->
	<div id="upd_cobon" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_cobon_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="upd_cobon_form" method="post" action="<?php echo URL?>cobon/upd_cobon" data-model="upd_cobon" data-type="update">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_cobon_title"><i class="fa fa-plus"></i> تعديل كوبون</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_cobon.ID" />
						<div class="col-auto">
							<label for="upd_name" class="">اسم الكبون</label>
							<input type="text" class="form-control" name="upd_name" id="upd_name" placeholder=" اسم / كود الكبون" :value="upd_cobon.NAME" required />
							<div class="err_notification" id="valid_upd_name">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="upd_type">الباقة</label>
							<select name="upd_type" id="upd_type" class="form-control">
								<option v-for="(x,index) in types" :selected="index == upd_cobon.TYPE" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification" id="valid_upd_type">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-sm mb-3">
							<label for="upd_discount">نوع الخصم</label>
							<select name="upd_discount" class="form-control" >
								<option v-for="(x,index) in pays" :selected="index == upd_cobon.PRICE_TYPE" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification " id="valid_upd_discount">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="upd_price" class="">قيمة الخصم</label>
							<input type="number" min="0" class="form-control" name="upd_price" id="upd_price" placeholder="0" :value="upd_cobon.PRICE" required />
							<div class="err_notification" id="valid_upd_price">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="upd_amount" class="">الكمية المتاحة</label>
							<input type="number" min="1" class="form-control" name="upd_amount" id="upd_amount" placeholder="1" :value="upd_cobon.AMOUNT" required />
							<div class="err_notification" id="valid_upd_amount">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="upd_exp" class="">تاريخ انتهاء الصلاحية</label>
							<input type="date" class="form-control" name="upd_exp" id="upd_exp" placeholder=" تاريخ انتهاء الصلاحية" :value="upd_cobon.EXP" required />
							<div class="err_notification" id="valid_upd_exp">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الكبون</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الكبون</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</main>
<script>
	var js_types= <?php echo json_encode(lib::$cobon_type); ?>;
	var js_pay 	= <?php echo json_encode(lib::$cobon_pay); ?>;
</script>