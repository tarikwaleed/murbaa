<div><br><br><br><br><br></div>
<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> مقدمي الخدمات</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="user_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>services/users">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="الاسم" />
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
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
			<div v-for="(x, index) in user_list" class="col-lg-4 col-md-6 col-sm-12 row">
				<div class="col-3" >
					<img v-bind:src="x.IMG" alt="..." class="img-thumbnail rounded-circle" width="70px" height="70px" alt="100x100" />
				</div>
				<div class="col-9 col-sm-12" >
					<a class="avatar m-0" :href="x.CO_LINK" >{{x.CO_NAME}}</a>
					<br/>
					<div>عدد المشاريع {{x.PROJECTS}}</div>
					<div v-if="x.PROJECTS > 0">
						<div class="row">
							<div class="col-7">الاحترافية بالتعامل</div>
							<div class="col-5"><i v-for="DE in x.DEAL" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VDEAL}}</div>
							
							<div class="col-7">التواصل والمتابعة</div>
							<div class="col-5"><i v-for="DE in x.COMM" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VCOMM}}</div>
						
							<div class="col-7">جودة العمل</div>
							<div class="col-5"><i v-for="DE in x.QUA" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VQUA}}</div>
						
							<div class="col-7">الخبرة</div>
							<div class="col-5"><i v-for="DE in x.EXP" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VEXP}}</div>
						
							<div class="col-7">التسليم فى الموعد</div>
							<div class="col-5"><i v-for="DE in x.TIM" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VTIM}}</div>
						
							<div class="col-7">التعامل معه مرّة أخرى</div>
							<div class="col-5"><i v-for="DE in x.AGIN" :key="DE" class="fa fa-star" style="color:gold"></i> {{x.VAGIN}}</div>
						</div>
						
					</div>
				</div>
				<div class="col-6" >
					<button type="button" class="btn btn-block btn-success" v-on:click.prevent="sel_user(index)" data-toggle="modal" data-target="#new_service">وظفني <i class="fa fa-plus"></i></button>
				</div>
			</div>
		</div>
        <br/><br/><br/>	
	</div>
	
	<!-- Modal For add new service -->
	<div id="new_service" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_service_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>services/new_service" data-model="new_service" data-type="new_service">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_service_title"><i class="fa fa-plus"></i> إضافة خدمة خاصة</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="ser_sel" :value="ser_sel.CO_ID" />
						<div class="col-auto">
							مقدم الخدمة المختار: {{ser_sel.CO_NAME}}
						</div>
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
	
</main>
<script>
	var js_config= <?php echo json_encode($this->config); ?>;
	var js_cont_type= <?php echo json_encode(lib::$contract_type); ?>;
	var js_reg_type= <?php echo json_encode(lib::$ser_reg_type); ?>;
	var js_ser_type= <?php echo json_encode(lib::$service_type); ?>;
	var js_cont_status= <?php echo json_encode(lib::$service_status); ?>;
</script>