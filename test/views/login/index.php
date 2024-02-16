<section class="property-section spad vue_area_div">
<br><br>
	<h4 class="text-center mb-3">تسجيل دخول</h4>
    
	<div class="container">
		<div class="row justify-content-center">
			<div class="card p-5 col-lg-4">
				<form action="<?php echo URL?>login/login" method="post">
					<input type="hidden" name="csrf" value="0" />
					
					<div class="form-group">
						<input type="email" class="form-control" id="usrname" name="usrname" required placeholder="البريد الالكتروني">
					</div>
					<div class="form-group">
						<input type="password" class="form-control mb-3" id="psw" name="psw" required placeholder="كلمة المرور">
					</div>
					<div class="form-group">
						<input type="txt" class="form-control" name="captcha" placeholder="رمز التحقق" required autocomplete="off" />
						<img src="<?php echo URL?>login/img" class="col-sm-5 mt-3 col-form-label" />
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary mb-3">تسجيل دخول</button>
						<a href="<?php echo URL?>login/forget" class="float-right">نسيت كلمة المرور؟</a>
						<a href="<?php echo URL?>login/register" class="float-left">ليس لديك حساب؟</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<br><br>
	<!-- Sign in END -->

	<!-- Model For Errors -->
	<div id="err_INPUT_ERROR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<p>إسم المستخدم و/ أو كلمة المرور خطأ</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Model For Errors -->
	<div id="err_UNACTIVE" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<p>لقد تم إيقاف حسابك</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
				</div>
			</div>
		</div>
	</div>
</section>