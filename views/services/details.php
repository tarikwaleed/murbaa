<div><br><br><br><br><br></div>
<div id="vue_area_div" class="container vue_area_div" >
<!-- Property Details Section Begin -->
    <section class="property-details-section mt-5">
        <div class="property-pic-slider owl-carousel">
			<div class="ps-item">
                <div class="container p-1 ">
                    <div class="row">
						<div class="col-sm-8">
							<h5>{{SER.TITLE}}</h5>
							<div class="" v-html="SER.D_DESCR"></div>
							<div class="row" v-if="SER.FILES">
								<h5 class="col-12">ملفات الطلب</h5>
								<div class="col-auto" v-for="f in SER.FILES">
									<a target="_blank" :href="f.URL">{{f.NAME}}</a>
								</div>
							</div>
							<hr/>
							<div class="row border m-0" v-if="config.ID != SER.CO_ID && (!config.SER_REG_NO || (SER.SEL_CUS != null && SER.SEL_CUS != config.ID))">
								<b>للأسف لا يمكنك تقديم عرض</b>
							</div>
							<div class="row border m-0" v-else-if="config.ID != SER.CO_ID && !SER.MY_OFFER">
								<form class="offer_form" id="new_type_form" method="post" action="<?php echo URL?>services/new_offer">
									<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
									<input type="hidden" class="" id="ser_id" name="ser_id" :value="SER.ID" />
									<input type="hidden" class="" id="off_percentage" name="off_percentage" :value="config.SERVICE_PERCENTAGE" />
									<div class="row">
										<div class="col-sm mb-1">
											<label for="off_period">مدة التنفيذ</label>
											<input type="number" class="form-control" name="off_period" placeholder=" المدة"  min="1" required />
											<div class="err_notification" id="valid_off_period">هنالك خطأ في هذا الحقل</div>
										</div>
										<div class="col-sm mb-1">
											<label for="off_price">السعر</label>
											<input type="number" class="form-control" data-per_id="off_price_after_per" @change="ch_price" name="off_price" placeholder=" السعر" :min="SER.PRICE_FROM" required />
											<div class="err_notification" id="valid_off_price">هنالك خطأ في هذا الحقل</div>
										</div>
										<div class="col-sm mb-1">
											<label for="off_price">السعر بعد الخصم {{config.SERVICE_PERCENTAGE}} %</label>
											<input type="number" class="form-control" id="off_price_after_per" placeholder=" السعر" :min="SER.PRICE_FROM" readonly />
											<div class="err_notification" id="valid_off_price">هنالك خطأ في هذا الحقل</div>
										</div>
									</div>
									<div class="row">
										<div class="col-12">
											<label for="off_desc" class="">العرض</label>
											<textarea class="form-control" name="off_desc" placeholder=" وصف العرض" required></textarea>
											<div class="err_notification" id="valid_off_desc">هنالك خطأ في هذا الحقل</div>
										</div>
									</div>
									<div class="col-auto">
										<label for="new_files" class="">ملفات العرض</label>
										<input type="file" name="new_off_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
										<div class="err_notification" id="valid_new_off_files">هنالك خطأ في هذا الحقل</div>
									</div>
									<div class="form_msg d-none">تم حفط العرض</div>	
									<div class="row">
										<div class="col-6">
											<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> ارسال العرض</button>
										</div>
									</div>
								</form>	
							</div>
							
							<div class="row pr-3 pl-0 property-filters" v-if="SER.OFFERS.lenght == 0">
								لا توجد عروض
							</div>
							<div class="row pr-3 pl-0 property-filters">
								<div v-for="(x, index) in SER.OFFERS" class="col-md-11 m-0 property-item g-0 bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm position-relative">
									<div class="row">
									    <div class="col-6">
											<a target="_blank" :href="x.CO_LINK">
												<img :src="x.IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
												<span>{{x.CO_NAME}}</span>
											</a>
										</div>
										<div class="col-3" v-if="(x.CO_ID == config.ID || SER.CO_ID == config.ID) && x.PRICE != null">
											السعر: {{x.PRICE}} - المدة: {{x.PERIOD}}
										</div>
										<div class="col-3" v-else="">
										</div>
										<div class="col-3" v-if="x.CO_ID == config.ID || SER.CO_ID == config.ID">
											<a class="btn btn-block btn-success" v-bind:href="'<?php echo URL?>services/chat/'+x.ID">النقاش </a>
											<button v-if="config.ID == x.CO_ID && SER.STATUS == 'NEW' && x.PRICE != null" type="button" class="btn btn-block btn-warning" data-toggle="collapse" :data-target="'#collapseExample_'+index" aria-expanded="false" aria-controls="collapseExample">تعديل <i class="fa fa-edit"></i></button>
											<button v-else-if="config.ID == x.CO_ID && SER.STATUS == 'NEW'" type="button" class="btn btn-block btn-warning" data-toggle="collapse" :data-target="'#collapseoffer_'+index" aria-expanded="false" aria-controls="collapseExample">تقديم عرض <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="row " v-html="x.D_DESCR"></div>
									<div class="row" v-if="x.FILES.lenght && (config.ID == x.CO_ID || config.ID == SER.CO_ID)">
										<h5 class="col-12">ملفات العرض</h5>
										<div class="col-auto" v-for="f in x.FILES">
											<a target="_blank" :href="f.URL">{{f.NAME}}</a>
										</div>
									</div>
									<div class="collapse" :id="'collapseExample_'+index">
										<div class="card card-body" v-if="config.ID == x.CO_ID && SER.STATUS == 'NEW'">
											<h5>تعديل العرض</h5>
											<form class="offer_form" id="upd_offer_form" method="post" action="<?php echo URL?>services/upd_offer">
												<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
												<input type="hidden" class="" name="id" :value="x.ID" />
												<input type="hidden" class="" id="off_percentage" name="off_percentage" :value="config.SERVICE_PERCENTAGE" />
												<div class="row">
													<div class="col-sm mb-1">
														<label for="off_period">مدة التنفيذ</label>
														<input type="number" class="form-control" name="off_period" placeholder=" المدة"  min="1" :value="x.PERIOD" required />
														<div class="err_notification" id="valid_off_period">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-sm mb-1">
														<label for="off_price">السعر</label>
														<input type="number" class="form-control" data-per_id="off_price_after_per" @change="ch_price" name="off_price" placeholder=" السعر" :min="SER.PRICE_FROM" :value="x.PRICE" required />
														<div class="err_notification" id="valid_upd_off_price">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-sm mb-1">
														<label for="off_price_after_per">السعر بعد الخصم {{config.SERVICE_PERCENTAGE}} %</label>
														<input type="number" class="form-control" id="off_price_after_per" placeholder=" السعر" :value="x.CUS_AMOUNT" :min="SER.PRICE_FROM" readonly />
														<div class="err_notification" id="valid_off_price">هنالك خطأ في هذا الحقل</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<label for="off_desc" class="">العرض</label>
														<textarea class="form-control" name="off_desc" placeholder=" وصف العرض" required>{{x.DESCR}}</textarea>
														<div class="err_notification" id="valid_off_desc">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-auto">
														<label for="upd_files" class="">ملفات العرض</label>
														<input type="file" name="upd_off_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
														<div class="err_notification" id="valid_upd_off_files">هنالك خطأ في هذا الحقل</div>
													</div>
												</div>
												<div class="form_msg d-none">تم تعديل العرض</div>	
												<div class="row">
													<div class="col-6">
														<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> ارسال العرض</button>
													</div>
												</div>
											</form>	
										</div>
									</div>
									<div class="collapse" :id="'collapseoffer_'+index">
										<div class="card card-body" v-if="config.ID == x.CO_ID && SER.STATUS == 'NEW'">
											<h5>تقديم العرض</h5>
											<form class="offer_form" id="upd_offer_form" method="post" action="<?php echo URL?>services/upd_offer">
												<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
												<input type="hidden" class="" name="id" :value="x.ID" />
												<input type="hidden" class="" id="off_percentage" name="off_percentage" :value="config.SERVICE_PERCENTAGE" />
												<div class="row">
													<div class="col-sm mb-1">
														<label for="off_period">مدة التنفيذ</label>
														<input type="number" class="form-control" name="off_period" placeholder=" المدة"  min="1" :value="x.PERIOD" required />
														<div class="err_notification" id="valid_off_period">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-sm mb-1">
														<label for="off_price">السعر</label>
														<input type="number" class="form-control" data-per_id="off_price_after_per" @change="ch_price" name="off_price" placeholder=" السعر" :min="SER.PRICE_FROM" :value="x.PRICE" required />
														<div class="err_notification" id="valid_upd_off_price">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-sm mb-1">
														<label for="off_price_after_per">السعر بعد الخصم {{config.SERVICE_PERCENTAGE}} %</label>
														<input type="number" class="form-control" id="off_price_after_per" placeholder=" السعر" :value="x.CUS_AMOUNT" :min="SER.PRICE_FROM" readonly />
														<div class="err_notification" id="valid_off_price">هنالك خطأ في هذا الحقل</div>
													</div>
												</div>
												<div class="row">
													<div class="col-12">
														<label for="off_desc" class="">العرض</label>
														<textarea class="form-control" name="off_desc" placeholder=" وصف العرض" required>{{x.DESCR}}</textarea>
														<div class="err_notification" id="valid_off_desc">هنالك خطأ في هذا الحقل</div>
													</div>
													<div class="col-auto">
														<label for="upd_files" class="">ملفات العرض</label>
														<input type="file" name="upd_off_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
														<div class="err_notification" id="valid_upd_off_files">هنالك خطأ في هذا الحقل</div>
													</div>
												</div>
												<div class="form_msg d-none">تم تعديل العرض</div>	
												<div class="row">
													<div class="col-6">
														<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> ارسال العرض</button>
													</div>
												</div>
											</form>	
										</div>
									</div>
								</div>
							</div>
		
								
							
						</div>
						<div class="col-sm-4">
							<div class="card bg-light p-3 mb-3">
								<h5>بطاقة المشروع</h5>
								<div class="row">
									<div class="col-4">الحالة:</div>
									<div class="col-8">{{cont_status[SER.STATUS]}}</div>
								</div>
								<div class="row">
									<div class="col-4">نوع المشروع:</div>
									<div class="col-8">{{ser_type[SER.SEL_TYPE]}}</div>
								</div>
								<div class="row">
									<div class="col-4">المدينة:</div>
									<div class="col-8">{{SER.CITY}}</div>
								</div>
								<div class="row">
									<div class="col-4">الاضافة:</div>
									<div class="col-8">{{SER.CREATE_TIME}}</div>
								</div>
								<div class="row">
									<div class="col-4">الميزانية:</div>
									<div class="col-8">{{SER.PRICE_FROM}} - {{SER.PRICE_TO}}</div>
								</div>
								<div class="row">
									<div class="col-4">مدة التنفيذ:</div>
									<div class="col-8">{{SER.PERIOD}} يوم</div>
								</div>
								<div class="row">
									<div class="col-4">نوع العقد:</div>
									<div class="col-8">{{cont_type[SER.CONTRACT_TYPE]}}</div>
								</div>
								<div class="pa-info">
									<a target="_blank" :href="SER.CO_LINK">
										<img :src="SER.IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
										<span>{{SER.CO_NAME}}</span>
									</a>
								</div>
								<div class="pa-info" v-if="SER.CURR_OFF">
									<hr/>
									<a target="_blank" :href="SER.OFFERS[SER.CURR_OFF].CO_LINK">
										<img :src="SER.OFFERS[SER.CURR_OFF].IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
										<span>{{SER.OFFERS[SER.CURR_OFF].CO_NAME}}</span>
									</a>
								</div>
								<div class="pa-info" v-else-if="SER.SEL_TYPE == 'PRIVATE'">
									<hr/>
									<a target="_blank" :href="SER.OFFERS[SER.PRIV].CO_LINK">
										<img :src="SER.OFFERS[SER.PRIV].IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
										<span>{{SER.OFFERS[SER.PRIV].CO_NAME}}</span>
									</a>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script>
	var js_config= <?php echo json_encode($this->config); ?>;
	var js_ser	= <?php echo json_encode($this->service); ?>;
	var js_cont_type= <?php echo json_encode(lib::$contract_type); ?>;
	var js_cont_status= <?php echo json_encode(lib::$service_status); ?>;
	var js_ser_type= <?php echo json_encode(lib::$service_type); ?>;
	var upg_pay		= '';
	
</script>
