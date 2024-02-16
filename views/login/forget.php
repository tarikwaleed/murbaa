<section class="property-section spad vue_area_div">
<br><br>
	<div class="container">
		<div class="form-icon">
		
		</div>
		<h4 class="text-center mb-3">طلب استرجاع كلمة المرور</h4>
		
		<div class="row justify-content-center">
			<div class="card p-5 col-lg-4">
				<form id="forget_form">
					<input type="hidden" name="csrf" value="0" />
					<div class="form-group mb-2">
						<label for="usrname" class="sr-only">البريد الإلكتروني</label>
						<input type="text" class="form-control item" id="usrname" name="usrname" placeholder="البريد الإلكتروني" >
					</div>
					<div class="form-group mb-2">
						<button id="forget_send" type="button" class="btn btn-block create-account btn-primary mb-2">إرسال</button>
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
		<div id="forget_req" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<p>لقد تم ارسال طلب تغيير كلمة المرور, قم بمراجعة بريدك الالكتروني</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>




