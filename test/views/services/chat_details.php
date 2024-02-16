<div><br><br><br><br><br></div>
<div id="vue_area_div" class="container vue_area_div" >
<!-- Property Details Section Begin -->
    <section class="property-details-section mt-5">
        <div class="property-pic-slider owl-carousel">
			<div class="ps-item">
                <div class="container p-1 ">
                    <div class="row">
						<div class="col-sm-8">
							<h5>المشروع: {{SER.TITLE}}</h5>
							<div class="" v-html="SER.D_DESCR"></div>
							<div class="row" v-if="SER.FILES">
								<div class="col-auto" v-for="f in SER.FILES">
									<a target="_blank" :href="f.URL">{{f.NAME}}</a>
								</div>
							</div>
							<div class="row" v-if="SER.SER_FILES">
								<div class="col-auto" v-for="f in SER.SER_FILES">
									<a target="_blank" :href="f.URL">{{f.NAME}}</a>
								</div>
							</div>
							<hr/>
							<h5>العرض</h5>
							<div v-if="SER.OFFER_PRICE != null" class="row pr-3 pl-0 property-filters">
								<div class="col-md-11 m-0 property-item g-0 bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm position-relative">
									<div class="row">
									    <div class="col-7">
											<a target="_blank" :href="SER.OFF_LINK">
												<img :src="SER.OFF_IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
												<span>{{SER.OFF_CO_NAME}}</span>
											</a>
										</div>
										<div class="col-3" >
											السعر: {{SER.OFFER_PRICE}} - المدة: {{SER.OFFER_PERIOD}}
										</div>
									</div>
									<div class="row " v-html="SER.OFFER_D_DESC"></div>
									<div class="row" v-if="SER.OFF_FILES">
										<div class="col-auto" v-for="f in SER.OFF_FILES">
											<a target="_blank" :href="f.URL">{{f.NAME}}</a>
										</div>
									</div>
								</div>
							</div>
							<div v-else="">
								لم يتم تقديم العرض<br/>
								<a v-if="SER.OFF_CO_ID == config.ID" :href="'<?php echo URL?>services/details/'+SER.ID">تقديم العرض</a>
								<hr/>
							</div>
							<div id="chatRoomData" >
								<h5>النقاشات</h5>
								<div class="card-body">
									<div v-for="(CHAT,index) in SER.CHAT" class="chat book_chat my-2 border" >
										<div class="chat-user" >
											<a class="avatar m-0" v-if="CHAT.CO == SER.CO_ID">
												<img v-bind:src="SER.CO_IMG" alt="..." class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" />
												{{SER.CO_NAME}} - {{CHAT.NAME}}
											</a>
											<a class="avatar m-0" v-else="">
												<img v-bind:src="SER.OFF_IMG" alt="..." class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" />
												{{SER.OFF_CO_NAME}} - {{CHAT.NAME}}
											</a>
											<span class="chat-time mt-1">{{CHAT.DATE}}</span>
										</div>
										<div class="chat-detail">
											<div class="" v-html="CHAT.TEXT"></div>											
											<div class="row" v-if="CHAT.FILES">
												<div class="col-auto" v-for="f in CHAT.FILES">
													<a target="_blank" :href="f.URL">{{f.NAME}}</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<form id="chat_form" class="container border" method="post" action="<?php echo URL?>services/add_chat" data-type="add">
										<input type="hidden" class="hid_info" name="csrf" id="csrf" value="<?php echo session::get('csrf'); ?>" />
										<input type="hidden" class="hid_info" name="chatroom" v-bind:value="SER.OFFER_ID" />
										<div class="row my-2">
											<div class="col-12">
												<textarea name="chat_msg" class="form-control" placeholder="الرسالة"></textarea>
												<div class="err_notification" id="valid_chat_msg">هنالك خطأ في هذا الحقل</div>
											</div>
										</div>
										<div class="row  my-2">
											<div class="col-2">
												<label class="">ملفات:</label>
											</div>
											<div class="col-8">
												<input type="file" name="chat_files[]" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" multiple />
												<div class="err_notification" id="valid_new_off_files">هنالك خطأ في هذا الحقل</div>
											</div>
											<div class="col-2">
												<button type="submit" class="btn btn-primary d-flex align-items-center"><i class="fa fa-paper-plane-o" aria-hidden="true"></i><span class="d-none d-lg-block ml-1">إرسال</span></button>
											</div>
										</div>
									</form>
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
										<img :src="SER.CO_IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
										<span>{{SER.CO_NAME}}</span>
									</a>
								</div>
								<div class="pa-info">
									<hr/>
									<a target="_blank" :href="SER.OFF_LINK">
										<img :src="SER.OFF_IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="100x100"> 
										<span>{{SER.OFF_CO_NAME}}</span>
									</a>
								</div>
								<div class="pa-info" v-if="SER.CO_ID == config.ID">
									<button v-if="SER.CURR_OFF == null && SER.OFFER_PRICE != null" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#accept_offer">قبول العرض <i class="fa fa-plus"></i></button>
									<button v-if="SER.STATUS == 'SIM_END'" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#finish_project" >استلام المشروع <i class="fa fa-plus"></i></button>
								</div>
								<div class="pa-info" v-else-if="SER.STATUS == 'WORK'">
									<button type="button" class="btn btn-block btn-success" v-on:click.prevent="finish()">طلب استلام <i class="fa fa-plus"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Modal For Accept Offer-->
	<div id="accept_offer" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="accept_offer_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3" id="accept_offer_form" method="post" action="<?php echo URL?>services/accept_offer" data-model="accept_offer" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="accept_offer_title"><i class="fa fa-plus"></i> قبول عرض</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="offer_id" :value="SER.OFFER_ID" />
						<div class="col-auto">
							<label for="price" class="">السعر</label>
							<input type="text" class="form-control" name="price" :value="SER.OFFER_PRICE" required readonly />
							<div class="err_notification" id="valid_price">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="period">المدة</label>
							<input type="text" class="form-control" name="period" :value="SER.PERIOD" required readonly />
							<div class="err_notification" id="valid_period">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="cont_type">نوع العقد</label>
							<input type="text" class="form-control" name="cont_type" :value="cont_type[SER.CONTRACT_TYPE]" required readonly />
							<div class="err_notification" id="valid_cont_type">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="desc" class="">العرض</label>
							<textarea class="form-control" name="desc" readonly required>{{SER.OFFER_DESC}}</textarea>
							<div class="err_notification" id="valid_desc">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="vip_card" class="">رقم البطاقة</label>
							<input type="text" data-paylib="number" lang="en" dir="ltr" autocomplete="off" size="20" class="form-control" value="" placeholder="رقم البطاقة" />
							<div class="d-none err_notification" id="valid_vip_card">this field required</div>
						</div>
						<div class="col-auto">
							<label>تاريخ انتهاء الصلاحية (YYYY/MM)</label>
							<div class="row">
								<div class="col-sm mb-3">
									<input type="text" data-paylib="expmonth" autocomplete="off"  size="2" placeholder="الشهر">
									<input type="text" data-paylib="expyear" autocomplete="off"  size="4" placeholder="السنة">
								</div>
							</div>
						</div>
						<div class="col-auto">
							<label for="vip_pass" class="">رقم CVV</label>
							<input type="text" lang="en" data-paylib="cvv" size="4" class="form-control" value="" placeholder="CVV" />
							<div class="d-none err_notification" lang="en" autocomplete="off" id="valid_vip_pass">this field required</div>
						</div>
						<div class="row" class="" id="paymentErrors"></div>
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
	
    <!-- Modal For Finish Project-->
	<div id="finish_project" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="finish_project_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="finish_project_form" method="post" action="<?php echo URL?>services/finish" data-model="finish_project" data-type="finish">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="finish_project_title"><i class="fa fa-plus"></i> إستلام المشروع والتقييم</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="SER.ID" />
						<div class="col-auto">
							<label for="price" class="">الاحترافية بالتعامل <span id="deal_view">5</span></label>
							<input type="range" class="form-control range_input" name="deal" min="0" max="5" data-view="deal_view" value="5" required>
							<div class="err_notification" id="valid_deal">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="period">التواصل والمتابعة <span id="comm_view">5</span></label>
							<input type="range" class="form-control range_input" name="comm" min="0" max="5" data-view="comm_view" value="5" required>
							<div class="err_notification" id="valid_comm">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-3">
							<label for="cont_type">جودة العمل <span id="quality_view">5</span></label>
							<input type="range" class="form-control range_input" name="quality" min="0" max="5" data-view="quality_view" value="5" required>
							<div class="err_notification" id="valid_quality">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-auto">
							<label for="desc" class="">الخبرة <span id="experiance_view">5</span></label>
							<input type="range" class="form-control range_input" name="experiance" min="0" max="5" data-view="experiance_view" value="5" required>
							<div class="err_notification" id="valid_experiance">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-auto">
							<label for="vip_card" class="">التسليم فى الموعد <span id="times_view">5</span></label>
							<input type="range" class="form-control range_input" name="times" min="0" max="5" data-view="times_view" value="5" required>
							<div class="d-none err_notification" id="valid_times">this field required</div>
						</div>
						<div class="col-auto">
							<label for="vip_card" class="">التعامل معه مرّة أخرى <span id="again_view">5</span></label>
							<input type="range" class="form-control range_input" name="again" min="0" max="5" data-view="again_view" value="5" required>
							<div class="d-none err_notification" id="valid_again">this field required</div>
						</div>	
						<div class="col-auto">
							<label for="desc" class="">التعليق</label>
							<textarea class="form-control" name="comments" ></textarea>
							<div class="err_notification" id="valid_comments">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم إستلام المشروع</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الخدمة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</div>

<script>
	var js_config		= <?php echo json_encode($this->config); ?>;
	var js_ser			= <?php echo json_encode($this->service); ?>;
	var js_cont_type	= <?php echo json_encode(lib::$contract_type); ?>;
	var js_cont_status	= <?php echo json_encode(lib::$service_status); ?>;
	var js_ser_type		= <?php echo json_encode(lib::$service_type); ?>;
	var upg_pay			= <?php echo (!empty($this->upgrade))?json_encode($this->upgrade):"''"; ?>;
	var JS_KEY			= <?php echo "'".P_JS_KEY."'"; ?>
</script>

<script src="<?php echo P_JS_FILE; ?>"></script>