<div><br><br><br><br><br></div>
<div id="staff_settings" class="container">
	<!-- Search Section Begin ->
	<section class="search-section spad">
		<div class="container">
			<form class="filter-form" id="Staff_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>report/search">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="land" class="form-control" placeholder="رقم العقار في النظام" />
						</div>	
						<div class="col-sm mb-3">
							<input name="chatroom" class="form-control" placeholder="رقم العقد" /> 
						</div>	
						<div class="col-sm mb-3">
							<input name="bill" class="form-control" placeholder="رقم الإيصال" />
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

	<!--Bill List-->
	<div class="property-comparison-section spad">
		<p>
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#bills" aria-expanded="false" aria-controls="collapseExample">
				الفواتير
			</button>
			<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#services" aria-expanded="false" aria-controls="collapseExample">
				الخدمات
			</button>
		</p>
		<div class="collapse show" id="bills">
			<div class="card card-body">
				<h5>الفواتير</h5>
				<div class="table-responsive">
				    <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
					    <thead>
						    <tr>
							    <th>رقم الفاتورة</th>
							    <th>الحالة</th>
							    <th v-if="admin==1">العميل</th>
								<th>رقم العقار الداخلي</th>
								<th>رقم العقار</th>
								<th>مدة الاعلان المميز</th>
								<th>الباقة</th>
								<th>الخدمة المطلوبة</th>
								<th>المبلغ</th>
								<th>التاريخ</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(x ,index) in bills">
								<td>{{x.ID}}</td>
								<td>{{x.STATUS}}</td>
								<td v-if="admin==1">{{x.CO_NAME}}</td>
								<td>{{x.LAND_ID}}</td>
								<td>{{x.LAND_NO}}</td>
								<td>{{x.LAND_PERIOD}}</td>
								<td>{{x.BK_NAME}}</td>
								<td>{{x.TITLE}}</td>
								<td>{{x.AMOUNT}}</td>
								<td>{{x.BILL_DATE}}</td>
							</tr>
						</tbody>
					</table>
                </div>
			</div>
		</div>
		<div class="collapse show" id="services">
			<div class="card card-body">
				<h5>الخدمات</h5>
				<div id="accordion">
					<div class="card" v-if="services.length == 0" >
						<h5>لا توجد فواتير خدمات</h5>
					</div>
					<div class="card" v-else="" v-for="(x,index) in services" >
						<div class="card-header" :id="'heading_'+index">
							<h5 class="mb-0" v-if="admin==1">
								<button class="btn btn-link" data-toggle="collapse" :data-target="'#collapse_'+index" aria-expanded="true" aria-controls="collapseOne">
									{{x.name}}
								</button>
							</h5>
						</div>
						<div :id="'collapse_'+index" class="collapse show" :aria-labelledby="'heading_'+index" data-parent="#accordion">
							<div class="card-body">
								<div class="row">
									<div class="col-4 p-0">
										المبلغ المتاح: {{x.total}}
									</div>
									<div class="col-4 p-0">
										المبلغ قيد الاجراء: {{x.pend}}
									</div>
									<div v-if="x.total > 0 && admin==0" class="col-4 p-0">
										<button type="button" class="btn btn-block btn-success" v-on:click.prevent="accept_service(index)" data-toggle="modal" data-target="#new_service">طلب سحب مبلغ <i class="fa fa-plus"></i></button>
									</div>
								</div>
								<div class="container">
									<div class="row">
										<div class="col-lg-12 p-0">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover table-head-fixed text-right">
													<thead>
														<tr>
															<th>رقم التحويلة</th>
															<th>الخدمة</th>
															<th>رقم الحساب</th>
															<th>الحالة</th>
															<th>المبلغ</th>
															<th>التاريخ</th>
															<th>الاجراء</th>
														</tr>
													</thead>
													<tbody>
														<tr v-for="(d ,i) in x.data">
															<td>{{d.ID}}</td>
															<td v-if="d.TITLE" >{{d.TITLE}}</td>
															<td v-else="" >طلب سحب</td>
															<td v-if="d.TITLE" > -- </td>
															<td v-else="" >{{d.COMM}}</td>
															<td v-if="d.STATUS == 'A'" >مكتملة</td>
															<td v-else="" >قيد المراجعة</td>
															<td>
																<i v-if="d.TYPE == 'IN'" class="fa fa-plus"></i> 
																<i v-else="" class="fa fa-minus"></i> 
																{{d.AMOUNT}}
															</td>
															<td>{{d.BILL_DATE}}</td>
															<td v-if="d.STATUS == 'A'" >--</td>
															<td v-else="" >
																<button v-if="admin == 0" type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#dis_active" v-on:click.prevent="update_service(i,index)">إلغاء <i class="fa fa-edit"></i></button>
																<button v-else="" 		  type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#accept_active" v-on:click.prevent="update_service(i,index)">قبول <i class="fa fa-plus"></i></button>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div-->
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<!-- Modal For add new Request -->
	<div id="new_service" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_service_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>payment/withdraw" data-model="new_service" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_service_title"><i class="fa fa-plus"></i> طلب سحب مبلغ</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_account" class="">رقم الحساب</label>
							<input type="text" class="form-control" name="new_account" placeholder=" رقم الحساب" required />
							<div class="err_notification" id="valid_new_title">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="new_price">المبلغ</label>
							<input type="number" class="form-control" name="new_price" placeholder=" المبلغ" :max="upd_ser.total" :value="upd_service.total" required />
							<div class="err_notification" id="valid_new_price">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الطلب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> طلب السحب</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For add new Request -->
	<div id="dis_active" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="dis_active_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="dis_active_form" method="post" action="<?php echo URL?>payment/dis_active" data-model="dis_active" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="dis_active_title"><i class="fa fa-minus"></i> إلغاء طلب سحب مبلغ</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_ser.ID" />
						<div class="col-auto">
							هل أنت متأكد من أنك تريد الغاء طلب السحب بالرقم {{upd_ser.ID}} بالمبلغ {{upd_ser.AMOUNT}}
						</div>
						<div class="form_msg d-none">تم الالغاء</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-edit"></i> طلب الغاء</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For add new Request -->
	<div id="accept_active" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="active_title" aria-hidden="true">
		<div class="modal-dialog modal-lg text-right" role="document">
			<form class="row g-3 model_form" id="dis_active_form" method="post" action="<?php echo URL?>payment/active" data-model="accept_active" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="active_title"><i class="fa fa-plus"></i> تأكيد تحويل سحب</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_ser.ID" />
						<div class="col-auto">
							هل انت متأكد من انك تريد الكمال تحويل الطلبية رقم {{upd_ser.ID}} بالمبلغ {{upd_ser.AMOUNT}} الى الحساب {{upd_ser.COMM}}
						</div>
						<div class="form_msg d-none">تم تأكيد التحويلة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-edit"></i> تأكيد</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	
</div>
<script>
	var js_bills 		= <?php echo json_encode($this->bills); ?>;
	var js_services 	= <?php echo json_encode($this->services); ?>;
	var js_admin 		= <?php echo (empty(session::get('company')))?1:0; ?>;
	var js_co_id 		= <?php echo (empty(session::get('company')))?'0':session::get('company'); ?>;
	var js_statues 		= <?php echo json_encode(lib::$land_for); ?>;
</script>

