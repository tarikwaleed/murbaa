<section class="property-section spad vue_area_div">
	<div class="container">
		<h4 class="text-center mb-3">اعادة ضبط كلمة المرور</h4>
		<div class="row justify-content-center">
			<div class="card p-5 col-lg-4">
				<form id="reset_form" action="<?php echo URL?>login/update_res_password" method="post">
					<input type="hidden" name="csrf" value="0" />
					<input type="hidden" name="id" value="<?php echo $this->id ;?>" />
					<div class="form-group mb-2">
						<label for="psw" class="sr-only">كلمة المرور</label>
						<input type="password" class="form-control item" id="psw" name="psw" placeholder="كلمة المرور" >
						<div class="d-none err_notification " id="valid_psw">هذا الحقل مطلوب</div>
					</div>
					<div class="form-group mb-2">
						<label for="psw2" class="sr-only">تأكيد كلمة المرور</label>
						<input type="password" class="form-control item" id="psw2" name="psw2" placeholder="تأكيد كلمة المرور" >
						<div class="d-none err_notification " id="valid_psw2">هذا الحقل مطلوب</div>
					</div>
					<div class="form-group mb-2">
						<button id="reset_send" type="button" class="btn btn-block create-account btn-primary mb-2">إرسال</button>
					</div>
					<div class="form-group mb-2">
						<a href="<?php echo URL?>login/" class="float-right">تسجيل دخول</a>
					</div>
				</form>
				
				<div class="social-media text-center">
					<div id="error_msg" class="d-none"><?php echo (!empty($this->MSG))?$this->MSG:"";?></div>
				</div>
			</div>
		</div>
		
		<!-- Model For Errors -->
		<div id="reset_req_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<p>لقد تم اعادة ضبط كلمة المرور</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Reset END -->

