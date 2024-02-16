<div><br><br><br><br><br></div>
<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> خدماتي</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="service_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>services/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<input type="hidden" name="my_pro" class="" value="1" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="الاسم" />
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
						<div class="col-sm mb-3" >
							<button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_service">إضافة خدمة جديد <i class="fa fa-plus"></i></button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->
	
	<!--service List-->
	<div class="property-comparison-section">
		<div class="row property-filters ">
			<div v-for="(x, index) in service_list" v-if="x.CO_ID == config.ID || x.CURR_OFF == x.MY_OFFER || (x.STATUS == 'NEW' && x.CURR_OFF == null)" class="col-md-12 property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm position-relative">
				<div class="row ">
					<div class="col-8 p-4 d-flex flex-column position-static row">
						<h5><a v-bind:href="'<?php echo URL?>services/details/'+x.ID">{{x.TITLE}} </a> </h5>
						<b>{{cont_status[x.STATUS]}}</b>
						<span class="pa-info">
							<i class="fa fa-user"></i> {{x.CO_NAME}}
							<i class="fa fa-clock-o"></i> {{x.CREATE_TIME}}
							<i class="fa fa-ticket"></i> {{x.OFFERS.length}}
							<i class="fa fa-map-marker"></i>{{x.CITY}}
							{{ser_type[x.SEL_TYPE]}}
						</span>
					</div>
					<div class="col-4 row" v-if="x.CO_ID == config.ID">
						<div class="col-auto p-1" v-if="x.OFFERS.length == 0">
							<button type="button" class="btn btn-block btn-warning" v-on:click.prevent="update_service(index)" data-toggle="modal" data-target="#upd_service">تعديل <i class="fa fa-edit"></i></button>
						</div>
						<div class="col-auto p-1" v-if="x.STATUS == 'FREEZ' || x.STATUS == 'NEW' ">
							<button type="button" v-if="x.STATUS == 'FREEZ'" class="btn btn-block btn-success" v-on:click.prevent="active(index)">فك تجميد <i class="fa fa-edit"></i></button>
							<button type="button" v-else="" class="btn btn-block btn-warning" v-on:click.prevent="active(index)">تجميد <i class="fa fa-edit"></i></button>
						</div>
					</div>
				</div>
				<div class="row " v-html="x.D_SM_DESC">
				</div>
			</div>
		</div>	
	</div>
	
	<!-- Modal For add new service -->
	<div id="new_service" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_service_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>services/new_service" data-model="new_service" data-type="new_service">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_service_title"><i class="fa fa-plus"></i> إضافة خدمة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="ser_sel" value="" />
						<div class="col-auto">
							<label for="new_title" class="">العنوان</label>
							<input type="text" class="form-control" name="new_title" placeholder=" عنوان الخدمة" required />
							<div class="err_notification" id="valid_new_title">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_title" class="">المدينة</label>
							<input type="text" class="form-control" name="new_city" placeholder=" المدينة" required />
							<div class="err_notification" id="valid_new_city">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_price_from">السعر من</label>
							<input type="number" class="form-control" name="new_price_from" placeholder=" السعر" :min="config.SERVICE_MIN_PRICE" required />
							<div class="err_notification" id="valid_new_price_from">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_price_to">السعر الى</label>
							<input type="number" class="form-control" name="new_price_to" placeholder=" السعر" :min="config.SERVICE_MIN_PRICE" required />
							<div class="err_notification" id="valid_new_price_to">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_period">مدة التنفيذ</label>
							<input type="number" class="form-control" name="new_period" placeholder=" المدة"  min="1" required />
							<div class="err_notification" id="valid_new_period">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_cont_type">نوع العقد</label>
							<select name="new_cont_type" class="form-control" >
								<option v-for="(x,index) in cont_type" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification" id="valid_new_cont_type">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="new_sm_desc" class="">الوصف المختصر</label>
							<textarea class="form-control" name="new_sm_desc" placeholder=" وصف الخدمة" required></textarea>
							<div class="err_notification" id="valid_new_sm_desc">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_desc" class="">الوصف</label>
							<textarea class="form-control" name="new_desc" placeholder=" وصف الخدمة" required></textarea>
							<div class="err_notification" id="valid_new_desc">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_files" class="">ملفات الطلب</label>
							<input type="file" name="new_ser_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
							<div class="err_notification" id="valid_new_ser_files">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الخدمة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الخدمة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update service -->
	<div id="upd_service" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_service_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="upd_service_form" method="post" action="<?php echo URL?>services/upd_service" data-model="upd_service" data-type="upd">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_service_title"><i class="fa fa-edit"></i> تعديل خدمة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_service.ID" />
						<div class="col-auto">
							<label for="upd_title" class="">العنوان</label>
							<input type="text" class="form-control" name="upd_title" :value="upd_service.TITLE" placeholder=" عنوان الخدمة" required />
							<div class="err_notification" id="valid_upd_title">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_title" class="">المدينة</label>
							<input type="text" class="form-control" name="upd_city" placeholder=" المدينة" :value="upd_service.CITY" required />
							<div class="err_notification" id="valid_upd_city">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="upd_price_from">السعر من</label>
							<input type="number" class="form-control" name="upd_price_from" :value="upd_service.PRICE_FROM" placeholder=" السعر" min="20" required />
							<div class="err_notification" id="valid_upd_price_from">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-sm mb-3">
							<label for="upd_price_to">السعر الى</label>
							<input type="number" class="form-control" name="upd_price_to" :value="upd_service.PRICE_TO" placeholder=" السعر"  min="20" required />
							<div class="err_notification" id="valid_upd_price_to">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="upd_period">مدة التنفيذ</label>
							<input type="number" class="form-control" name="upd_period" :value="upd_service.PERIOD" placeholder=" المدة"  min="1" required />
							<div class="err_notification" id="valid_upd_period">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="upd_cont_type">نوع العقد</label>
							<select name="upd_cont_type" class="form-control" >
								<option v-for="(x,index) in cont_type" :selected="index == upd_service.CONTRACT_TYPE" v-bind:value="index">{{x}}</option>
							</select>
							<div class="err_notification" id="valid_new_cont_type">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="upd_sm_desc" class="">الوصف المختصر</label>
							<textarea class="form-control" name="upd_sm_desc" placeholder=" وصف الخدمة" required>{{upd_service.SM_DESC}}</textarea>
							<div class="err_notification" id="valid_upd_min_desc">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="upd_desc" class="">الوصف </label>
							<textarea class="form-control" name="upd_desc" placeholder=" وصف الخدمة" required>{{upd_service.DESCR}}</textarea>
							<div class="err_notification" id="valid_upd_desc">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="new_files" class="">ملفات الطلب</label>
							<input type="file" name="upd_ser_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
							<div class="err_notification" id="valid_upd_ser_files">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الخدمة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الخدمة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</main>
<script>
	var js_config= <?php echo json_encode($this->config); ?>;
	var js_cont_type= <?php echo json_encode(lib::$contract_type); ?>;
	var js_reg_type= <?php echo json_encode(lib::$ser_reg_type); ?>;
	var js_ser_type= <?php echo json_encode(lib::$service_type); ?>;
	var js_cont_status= <?php echo json_encode(lib::$service_status); ?>;
	
</script>