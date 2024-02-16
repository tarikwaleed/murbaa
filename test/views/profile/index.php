<main id="staff_info" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div" style="margin-top:100px;">
	<div class="mb-3 border-bottom">
		<h4 class="h4"> <i class="fa fa-user-circle text-primary"></i> الملف الشخصي </h4>
	</div>
	
	
	<!-- Modal For Staff -->
	<div class="d-flex justify-content-center ">
		<form class="g-3 border p-5 mb-3" id="staff_form"  v-on:submit.prevent="onSubmitupd" method="post" action="<?php echo URL?>profile/upd_info"  enctype="multipart/form-data">
			<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
			<div class="row mb-3">
				<div class="col-sm">
					<img id="new_pro_image" v-bind:src="info.IMG" width="150px" height="150px" class="img-thumbnail rounded-circle mb-1" alt="..."> <br />
					<input type="file" name="new_pro_image" class="form-control-small file-upload image_upload form-control-file" data-id="new_pro_image" id="img" accept="image/*">
					<div class="d-none err_notification" id="valid_new_staff_address">this field required</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
					<label for="new_staff_name" class="">الاسم</label>
					<input type="text" v-bind:value="info.NAME" class="form-control" name="new_staff_name" id="new_staff_name" placeholder=" أدخل اسم المستخدم" required>
					<div class="d-none err_notification" id="valid_new_staff_name">this field required</div>
				</div>
				<div class="col-sm">
					<label for="new_staff_phone" class="">رقم الهاتف</label>
					<input type="text" v-bind:value="info.PHONE" class="form-control" name="new_staff_phone" id="new_staff_phone" placeholder=" أدخل رقم الهاتف" >
					<div class="d-none err_notification" id="valid_new_staff_phone">this field required</div>
					<div class="w3-hide err_notification w3-small w3-text-red" id="duplicate_new_staff_phone">duplicate Phone No </div>
				</div>
				<div class="col-sm">
					<label for="new_staff_nat" class="">رقم الهوية</label>
					<input type="text" v-bind:value="info.NAT_NO" class="form-control" name="new_staff_nat" id="new_staff_nat" placeholder=" أدخل رقم الهوية" >
					<div class="d-none err_notification" id="valid_new_staff_nat">this field required</div>
					<div class="w3-hide err_notification w3-small w3-text-red" id="duplicate_new_staff_nat">duplicate ID no </div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_staff_email" class="">البريد الإلكتروني</label>
					<input id="new_staff_email" readonly type="text" v-bind:value="info.EMAIL" class="form-control" />
				</div>
				<div class="col-sm">
					
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="new_staff_address" class="">العنوان</label>
					<input v-bind:value="info.ADDRESS" type="text" class="form-control" name="new_staff_address" id="new_staff_address" placeholder=" أدخل العنوان" />
					<div class="d-none err_notification" id="valid_new_staff_address">this field required</div>
				</div>
			</div>
			
			<div class="row">
				<h5>تحديث كلمة المرور</h5>
				<span class="text-danger mr-3">( اترك حقل كلمة المرور الجديدة فارغاً اذا كنت لا تريد تحديث كلمة المرور الخاصة بك )</span>
				<hr/>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="curr_staff_pass" class="">كلمة المرور الحالية</label>
					<input type="password" class="form-control" name="curr_staff_pass" id="curr_staff_pass" placeholder=" أدخل كلمة المرور" required >
					<div class="d-none err_notification" id="valid_new_staff_pass">this field required</div>
				</div>
				<div class="col-sm">
					<label for="new_staff_pass" class="">كلمة المرور الجديدة </label>
					<input type="password" class="form-control" name="new_staff_pass" id="new_staff_pass" placeholder=" كلمة المرور الجديدة" >
					<div class="d-none err_notification" id="valid_new_staff_pass">this field required</div>
				</div>
				<div class="col-sm">
					<label for="new_staff_pass2" class="">تأكيد كلمة المرور</label>
					<input type="password" class="form-control" name="new_staff_pass2" id="new_staff_pass2" placeholder="تأكيد كلمة المرور الجديدة " >
					<div class="d-none err_notification" id="valid_new_staff_pass2">this field required</div>
				</div>
			</div>
			<div class="row">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> تحديث البيانات</button>
			</div>
		</form>
	</div>
</main>
<script>
	var js_info = <?php echo $this->sys_info; ?>;
</script>
