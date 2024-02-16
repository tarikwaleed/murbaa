<section class="property-section spad vue_area_div">
	<h4 class="text-center mb-3">فتح حساب جديد</h4>
	<div class="container">
		<div class="row justify-content-center">
			<div class="card p-5 col-lg-4">
				<form action="<?php echo URL?>login/reg" method="post">
					<input type="hidden" name="csrf" value="0" />
					<?php if(!empty($this->MSG)){echo $this->MSG['Error'];}?>
					<div class="form-group">
						<input type="text" class="form-control" id="usrname" name="usrname" placeholder="إسم المستخدم">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" id="email" name="email" placeholder="البريد الإلكترونى">
					</div>
					<div class="form-group">
						<input type="phone" class="form-control" id="phone" name="phone" placeholder="رقم الهاتف">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="psw" name="psw" placeholder="كلمة المرور">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="psw2" name="psw2" placeholder="تأكيد كلمة المرور">
					</div>
					<div class="form-group">
						<input type="checkbox" class="" id="accept" name="accept" placeholder="أوافق على الشروط والاحكام" value="1" />
						أوافق على 
						<a href="<?php echo URL?>dashboard/terms" target="_blank"> الشروط والاحكام</a>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-block btn-primary">تسجيل</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>