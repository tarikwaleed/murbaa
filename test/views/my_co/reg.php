<style>
@media only screen and (min-width: 200px) and (max-width: 767px)  {
.card {
width:90% !important;
}
    }
@media only screen and (min-width: 767px) and (max-width: 992px)  {
.card {
width:60% !important;
}
    }

@media only screen and (min-width: 992px) {
       .card {
width:40% !important;
}

    }
</style>
<div><br><br><br><br><br></div>
<main id="staff_info" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="mb-3 border-bottom">
		<h4 class="h4"> <i class="fa fa-user-circle text-primary"></i> البروفايل </h4>
	</div>
	
	<div class="d-flex justify-content-center ">
		<div class="card my-4">
  	        <img id="new_pro_image" v-bind:src="info.IMG" class="card-img-top" alt="..." />
 	        <ul class="list-group list-group-flush">
    	        <li class="list-group-item">
	                <div class="d-flex align-items-center">
	                    <div>الإسم</div>
	                    <div class="flex-fill rounded text-dark border border p-2 mx-3">{{info.NAME}}</div>
	                </div>
	            </li>
   	            <li class="list-group-item">
	                <div class="d-flex align-items-center">
	                    <div>البريد الإلكتروني</div>
	                    <div class="flex-fill rounded text-dark border border p-2 mx-3">{{info.EMAIL}}</div>
	                </div>
	            </li>
    	        <li class="list-group-item">
	                <div class="d-flex align-items-center">
	                    <div>رقم الهاتف</div>
	                    <div class="flex-fill rounded text-dark border border p-2 mx-3">{{info.PHONE}}</div>
	                </div>
	            </li>
	            <li class="list-group-item" v-if="info.CO_ACCEPT !== null && info.IS_EXP == 1 && info.CO_ACCEPT != 0">
	                <div class="d-flex align-items-center justify-content-around">
	                    <div>الحساب موثق - ينتهي بتاريخ {{info.COMM_EXPERD}}</div>
	                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upd_reg" ><i class="fa fa-warning"></i> تعديل بيانات توثيق الحساب</button>
	                </div>
	            </li>
	            <li class="list-group-item" v-else-if="info.REG_ID !== null && info.CO_ACCEPT == null">
	                <div>التوثيق تحت المراجعة بالرقم {{info.REG_ID}}</div>
	            </li>
	            <li class="list-group-item" v-else="">
	                <div class="" v-if="info.CO_ACCEPT == 0"> تم رفض التوثيق</div>
				    <div class="" v-else-if="info.COMM_EXPERD !== null"> إنتهي توثيق الحساب بتاريخ: {{info.COMM_EXPERD}}</div>
				    <div class="" v-else="">لم يتم توثيق الحساب </div>
				    <div class="">
					    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#com_reg" ><i class="fa fa-warning"></i> توثيق الحساب</button>
				    </div>
	            </li>
                 <!--REG SERVICE.......................................-->
    	        <li class="list-group-item">
	                <div class="d-flex align-items-center">
	                    <b>توثيق تقديم الخدمات</b>
	                </div>
	            </li>




	            <li class="list-group-item" v-if="info.SER_REG_ACCEPT !== null && info.SER_REG_IS_EXP == 1 && info.SER_REG_ACCEPT != 0">
	                <div class="d-flex align-items-center justify-content-around">
	                    <div>الحساب موثق - ينتهي بتاريخ {{info.SER_REG_EXPERD}}</div>
	                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upd_ser" ><i class="fa fa-warning"></i> تعديل بيانات توثيق الحساب</button>
	                </div>
	            </li>
	            <li class="list-group-item" v-else-if="info.SER_REG_ID !== null && info.SER_REG_ACCEPT == null">
	                <div>التوثيق تحت المراجعة بالرقم {{info.SER_REG_ID}}</div>
	            </li>
	            <li class="list-group-item" v-else="">
	                <div class="" v-if="info.SER_REG_ACCEPT == 0"> تم رفض التوثيق</div>
				    <div class="" v-else-if="info.SER_REG_EXPERD !== null"> إنتهي توثيق الحساب بتاريخ: {{info.SER_REG_EXPERD}}</div>
				    <div class="" v-else="">لم يتم توثيق الحساب</div>
				    <div class="">
					    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ser_reg" ><i class="fa fa-warning"></i> توثيق الحساب</button>
				    </div>
	            </li>
  	        </ul>
	    </div>
		
        <!--div class="g-3 border p-5 mb-3">
			<div class="row mb-3">
				<div class="col-sm">
					<img id="new_pro_image" v-bind:src="info.IMG" width="150px" height="150px" class="img-thumbnail rounded-circle mb-1" alt="..."> <br />
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<label for="new_co_name" class="">الإسم</label>
					<p>{{info.NAME}}</p>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_co_email" class="">البريد الإلكتروني</label>
					<p>{{info.EMAIL}}</p>
				</div>
				<div class="col-sm">
					<label for="new_co_phone" class="">رقم الهاتف</label>
					<p>{{info.PHONE}}</p>
				</div>
			</div>
			<div class="row mb-3" v-if="info.CO_ACCEPT !== null && info.IS_EXP == 1 && info.CO_ACCEPT != 0">
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
				<div class="col-sm" v-else-if="info.COMM_EXPERD !== null"> إنتهي توثيق الحساب بتاريخ: {{info.COMM_EXPERD}}</div>
				<div class="col-sm" v-else="">لم يتم توثيق الحساب</div>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#com_reg" ><i class="fa fa-warning"></i> توثيق الحساب</button>
				</div>
			</div>
			<!--REG SERVICE.......................................->
			<div class="row mb-3">
				<b>توثيق تقديم الخدمات</b>
			</div>
			<div class="row mb-3" v-if="info.SER_REG_ACCEPT !== null && info.SER_REG_IS_EXP == 1 && info.SER_REG_ACCEPT != 0">
				الحساب موثق - ينتهي بتاريخ {{info.SER_REG_EXPERD}}
				<br/>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#upd_ser" ><i class="fa fa-warning"></i> تعديل بيانات توثيق الحساب</button>
				</div>
			</div>
			<div class="row mb-3" v-else-if="info.SER_REG_ID !== null && info.SER_REG_ACCEPT == null">
				التوثيق تحت المراجعة بالرقم {{info.SER_REG_ID}}
			</div>
			<div class="row mb-3" v-else="">
				<div class="col-sm" v-if="info.SER_REG_ACCEPT == 0"> تم رفض التوثيق</div>
				<div class="col-sm" v-else-if="info.SER_REG_EXPERD !== null"> إنتهي توثيق الحساب بتاريخ: {{info.SER_REG_EXPERD}}</div>
				<div class="col-sm" v-else="">لم يتم توثيق الحساب</div>
				<div class="col-sm">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#ser_reg" ><i class="fa fa-warning"></i> توثيق الحساب</button>
				</div>
			</div>
			
			
			
		</div-->
	</div>
	
	<!-- Modal For Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="com_reg">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form1" method="post" action="<?php echo URL?>my_co/reg" data-model="com_reg" data-type="new_reg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="reg_title"><i class="fa fa-edit"></i> توثيق بيانات المعلن</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="row mb-3">
							<div class="col-sm">
								<label for="new_co_email" class="">نوع الهوية</label>
								<select name="new_id_type" id="new_co_type" class="form-control">
									<option value="" selected>إختار النوع</option>
									<option v-for="(x,id) in id_types" :value="id">{{x}}</option>
								</select>
							</div>
							<div class="col-sm">
								<label for="new_id_no" class="">رقم الهوية</label>
								<input type="text" class="form-control" v-bind:value="info.ID_NO" name="new_id_no" id="new_id_no" placeholder=" ادخل رقم الهوية" >
								<div class="d-none err_notification" id="valid_new_id_no">this field required</div>
								<div class="d-none err_notification" id="duplicate_new_id_no">duplicate Phone No </div>
							</div>
						</div>
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
							<!--div class="col-sm">
								<label for="new_reg_file" class="">ملف السجل التجاري أو ملف رخصة العمل الحر أو الهوية</label>
								<input type="file" name="new_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" />
								<div class="d-none err_notification" id="valid_new_reg_file">this field required</div>
							</div-->
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
						<div class="row mb-3 d-none wait_area">
							<div class="col-sm mb-12">
								جاري التحقق من البيانات, قد يأخد الامر عدة دقائق
							</div>
						</div>
						<div class="form_msg d-none">تم توثيق الحساب بنحاح</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> طلب توثيق</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For upd Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_reg">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form1" method="post" action="<?php echo URL?>my_co/upd_reg" data-model="upd_reg" data-type="upd_reg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_reg_title"><i class="fa fa-edit"></i> تحديث توثيق بيانات المكتب</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" name="id" :value="info.REG_ID" />
						<div class="row mb-3">
							<div class="col-sm">
								<label for="new_co_email" class="">نوع الهوية</label>
								<select name="new_id_type" id="new_co_type" class="form-control">
									<option value="" selected>إختار النوع</option>
									<option v-for="(x,id) in id_types" :value="id" :selected="id == info.ID_TYPE">{{x}}</option>
								</select>
							</div>
							<div class="col-sm">
								<label for="new_id_no" class="">رقم الهوية</label>
								<input type="text" v-bind:value="info.ID_NO" class="form-control" name="new_id_no" id="new_id_no" placeholder=" ادخل رقم الهوية" >
								<div class="d-none err_notification" id="valid_new_id_no">this field required</div>
								<div class="d-none err_notification" id="duplicate_new_id_no">duplicate Phone No </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="reg_no" class="">رقم المعلن</label>
								<input type="text" lang="en" name="upd_reg_co_no" id="upd_reg_co_no" class="form-control" :value="info.CO_REG_NO" placeholder="" required />
								<div class="d-none err_notification" id="valid_upd_reg_co_no">this field required</div>
							</div>
							<!--div class="col-sm mb-3">
								<label for="upd_reg_no" class="">رقم السجل التجاري أو رقم رخصة العمل الحر</label>
								<input type="text" lang="en" name="upd_reg_no" id="upd_reg_no" class="form-control" :value="info.CO_REG_NO" placeholder="" required />
								<div class="d-none err_notification" id="valid_upd_reg_no">this field required</div>
							</div>
							<div class="col-sm">
								<label for="upd_reg_file" class="">ملف السجل التجاري أو ملف رخصة العمل الحر او الهوية</label>
								<input type="file" name="upd_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" />
								<div class="d-none err_notification" id="valid_upd_reg_file">this field required</div>
							</div-->
						</div>
						<div class="row mb-3">
							<div class="col-sm mb-3">
								<label for="upd_reg_real_no" class="">رقم ترخيص الهيئة العامة للعقار</label>
								<input type="text" lang="en" name="upd_reg_real_no" id="upd_reg_real_no" class="form-control" :value="info.CO_REAL_NO" placeholder="" />
								<div class="d-none err_notification" id="valid_upd_reg_real_no">this field required</div>
							</div>
							<div class="col-sm mb-3">
								رقم ترخيص الهيئة العامة للعقار مطلوب فقط إذا كان لديك عقار تريد ان تعلن عنه كمسوق أو منشأ
							</div>
							
						</div>
						
						<div class="row mb-3 d-none wait_area">
							<div class="col-sm mb-12">
								جاري التحقق من البيانات, قد يأخد الامر عدة دقائق
							</div>
						</div>
						<div class="form_msg d-none">تم توثيق الحساب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> طلب توثيق</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
    <!--REG SERVICE.......................................-->
	<!-- Modal For Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="ser_reg">
		<div class="modal-dialog modal-lg">
			<form class="g-3 model_form" id="ser_reg_form" method="POST" action="<?php echo URL?>my_co/ser_reg" enctype="multipart/form-data" data-model="ser_reg" data-type="new_reg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="reg_title"><i class="fa fa-edit"></i> توثيق بيانات الخدمات</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" name="reg_ser_type" value="REG" />
						<div class="row mb-3">
							<div class="col-sm">
								<label for="new_ser_reg_type" class="">نوع الجهة</label>
								<select name="new_reg_type" @change="ser_reg_type_upd()" class="form-control">
									<option v-for="(x,id) in ser_reg_type" :value="id" :selected="id == info.SER_REG_TYPE">{{x}}</option>
								</select>
							</div>
							<div class="col-sm">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_name" class="">إسم المنشأه</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_name" class="">الإسم</label>
								<input type="text" class="form-control" name="new_reg_name" placeholder=" ادخل الإسم" >
								<div class="d-none err_notification" id="valid_new_reg_name">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_no" class="">السجل التجاري</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_no" class="">السجل المدني</label>
								<input type="text" lang="en" name="new_reg_no" class="form-control" value="" placeholder="" />
								<div class="d-none err_notification" id="valid_new_reg_no">this field required</div>
							</div>
							<div class="col-sm">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_no_file" class="">ملف السجل التجاري</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_no_file" class="">ملف السجل المدني</label>
								<input type="file" name="new_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" />
								<div class="d-none err_notification" id="valid_new_reg_no_file">this field required</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm mb-3">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_num" class="">رقم التصنيف</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_num" class="">رقم المعرف</label>
								<input type="text" lang="en" name="new_reg_num" class="form-control" value="" placeholder="" />
								<div class="d-none err_notification" id="valid_new_reg_num">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تم ارسال البيانات</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفظ</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For upd Registration	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_ser">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="vip_land_form1" method="post" action="<?php echo URL?>my_co/upd_ser_reg" enctype="multipart/form-data" data-model="upd_ser" data-type="upd_ser">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_reg_title"><i class="fa fa-edit"></i> تحديث توثيق بيانات الخدمات</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" name="id" :value="info.SER_REG_ID" />
						<div class="row mb-3">
							<div class="col-sm">
								<label for="new_ser_reg_type" class="">نوع الجهة</label>
								<select name="new_reg_type" @change="ser_reg_type_upd()" class="form-control">
									<option v-for="(x,id) in ser_reg_type" :value="id" :selected="id == info.SER_REG_TYPE">{{x}}</option>
								</select>
							</div>
							<div class="col-sm">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_name" class="">إسم المنشأه</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_name" class="">الإسم</label>
								<input type="text" class="form-control" name="new_reg_name" placeholder=" ادخل الإسم" :value="info.SER_REG_NAME" >
								<div class="d-none err_notification" id="valid_new_reg_name">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_no" class="">السجل التجاري</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_no" class="">السجل المدني</label>
								<input type="text" lang="en" name="new_reg_no" class="form-control" :value="info.SER_REG_NO"  placeholder="" />
								<div class="d-none err_notification" id="valid_new_reg_no">this field required</div>
							</div>
							<div class="col-sm">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_no_file" class="">ملف السجل التجاري</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_no_file" class="">ملف السجل المدني</label>
								<input type="file" name="new_reg_file" class="form-control-small file-upload form-control-file" id="file" accept="image/*,application/pdf" />
								<div class="d-none err_notification" id="valid_new_reg_no_file">this field required</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-sm mb-3">
								<label v-if="curr_reg_type=='CREA'" for="new_reg_num" class="">رقم التصنيف</label>
								<label v-else-if="curr_reg_type=='MARKET'" for="new_reg_num" class="">رقم المعرف</label>
								<input type="text" lang="en" name="new_reg_num" class="form-control" :value="info.SER_REG_NUM"  placeholder="" />
								<div class="d-none err_notification" id="valid_new_reg_num">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تم ارسال البيانات</div>
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
	var js_id_types = <?php echo json_encode($this->nat_type); ?>;
	var js_ser_type = <?php echo json_encode(lib::$ser_reg_type); ?>;
	var js_types 	= <?php echo json_encode(lib::$company_type); ?>;
	var js_info 	= <?php echo $this->sys_info; ?>;
	var upg_pay		= <?php echo (!empty($this->upgrade))?json_encode($this->upgrade):"''"; ?>;
	var JS_KEY		= <?php echo "'".P_JS_KEY."'"; ?>
</script>
