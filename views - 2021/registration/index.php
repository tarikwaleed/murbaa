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
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>الرقم</th>
									<th>الحالة</th>
									<th colspan="3">العميل</th>
									<th>رقم المعلن</th>
									<!--th>رقم السجل التجاري/ رخصة العمل</th-->
									<th>رقم ترخيص الهيئة العامة للعقار</th>
									<th>الملف</th>
									<th colspan="2">الإجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in requests">
									<td>{{x.ID}}</td>
									<td v-if="x.EXP_DATE === null">جديد</td>
									<td v-else="">تحديث طلب ينتهي بتاريخ {{x.EXP_DATE}}</td>
									<td>{{x.C_NAME}}</td>
									<td>{{x.C_PHONE}}</td>
									<td>{{x.C_EMAIL}}</td>
									<td>{{x.NO}}</td>
									<!--td>{{x.CO_NUM}}</td-->
									<td>{{x.REAL_NO}}</td>
									<td><a :href="x.FILE" target="_blank"> {{x.FILE_NAME}}</a></td>
									<td><button v-on:click.prevent="accept(index)" data-toggle="modal" data-target="#reg_ok" class='btn btn-success rounded btn-sm '>قبول</button></td>
									<td><button v-on:click.prevent="accept(index)" data-toggle="modal" data-target="#reg_deny" class='btn btn-warning rounded btn-sm '>رفض</button></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For accept -->
	<div id="reg_ok" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_type_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>registration/active" data-model="reg_ok" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_type_title"><i class="fa fa-plus"></i> قبول الطلب</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" id="status" name="status" value="1" />
						<input type="hidden" class="accept_id" id="id" name="id" value="" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_date" class="">تاريخ النهاية</label>
								<input type="date" class="form-control" name="new_date" id="new_date" data-date-format="DD-MM-YYYY" placeholder="dd-mm-yyy" required />
								<div class="err_notification" id="valid_new_date">هنالك خطأ في هذا الحقل</div>
							</div>
						</div>
						<div class="form_msg d-none">تم قبول الطلب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> قبول الطلب</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For deny -->
	<div id="reg_deny" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_type_title" aria-hidden="true">
		<div class="modal-dialog text-right" role="document">
			<form class="row g-3 model_form" id="new_type_form" method="post" action="<?php echo URL?>registration/active" data-model="reg_deny" data-type="new">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_type_title"><i class="fa fa-plus"></i> رفض الطلب</h5>
						<button type="button" class="btn-close btn bg-white p-0" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" id="status" name="status" value="0" />
						<input type="hidden" class="accept_id" id="del_id" name="id" value="" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_date" class="">هل انت متأكد من رفض الطلب</label>
								
							</div>
						</div>
						<div class="form_msg d-none">تم رفض الطلب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> رفض الطلب</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</div>
<script>
	var js_requests 		= <?php echo json_encode($this->requests); ?>;
	
</script>

