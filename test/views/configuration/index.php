<main id="staff_info" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-cogs text-primary"></i> الإعدادات</h4>
	</div>
	<!-- Modal For Settings -->
	<div class="container">
		<div>
			<!--To display Error if appeared Or save OK message-->
			<?php echo (empty($this->config_item['Error']))?"":$this->config_item['Error'];?>
		</div>
		<form class="g-3 border p-5 mb-3" id="staff_form" method="post" action="<?php echo URL?>configuration"  enctype="multipart/form-data">
			<input type="hidden" class="hid_info" id="csrf" name="csrf" value="<?php echo session::get('csrf'); ?>" />
			<div class="row mb-3">
				<div class="col-sm">
					<img id="new_pro_image" src="<?php echo URL."public/IMG/".session::get("LOGO");?>" width="150px" height="150px" class="img-thumbnail rounded-circle mb-3" alt="..."> <br />
					<input type="file" name="new_pro_image" class="form-control-small file-upload image_upload form-control-file" data-id="new_pro_image" id="img" accept="image/*">
					<div class="d-none err_notification" id="valid_new_staff_address">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="title" class="">عنوان الموقع</label>
					<input type="text" value="<?php echo session::get("TITLE");?>" class="form-control" name="title" id="title" placeholder=" العنوان" required />
					<div class="err_notification" id="valid_title">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">وصف الموقع</label>
					<input type="text" value="<?php echo session::get("DESC_INFO");?>" class="form-control" name="desc" id="desc" placeholder=" وصف الموقع" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">الرابط على Facebook</label>
					<input type="url" value="<?php echo session::get("FACEBOOK");?>" class="form-control" name="face" placeholder=" facebook.com" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">الرابط على Twitter</label>
					<input type="url" value="<?php echo session::get("TWITTER");?>" class="form-control" name="twitter" placeholder=" twitter.com" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">الرابط على Instagram</label>
					<input type="url" value="<?php echo session::get("INSTAGRAM");?>" class="form-control" name="instagram" placeholder=" instagram.com" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">مدة إعلان VIP</label>
					<input type="text" value="<?php echo session::get("VIP_PERIOD");?>" class="form-control" name="vip" placeholder=" العرض في الصفحة" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">قيمة إعلان VIP</label>
					<input type="text" value="<?php echo session::get("VIP_PRICE");?>" class="form-control" name="vip_price" placeholder=" العرض في الصفحة" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">PAGING</label>
					<input type="text" value="<?php echo session::get("PAGING");?>" class="form-control" name="paging" placeholder=" العرض في الصفحة" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">عمولة الايجار اليومي</label>
					<input type="number" lang="en" value="<?php echo session::get("RENT_DAY");?>" class="form-control" name="rent_day" placeholder=" عمولة الايجار" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">عمولة الايجار الشهري</label>
					<input type="number" lang="en" value="<?php echo session::get("RENT_MONTH");?>" class="form-control" name="rent_month" placeholder=" عمولة الايجار" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">عمولة الايجار السنوي</label>
					<input type="number" lang="en" value="<?php echo session::get("RENT_YEAR");?>" class="form-control" name="rent_year" placeholder=" عمولة الايجار" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">عمولة البيع</label>
					<input type="number" lang="en" value="<?php echo session::get("SALE");?>" class="form-control" name="sale" placeholder=" عمولة البيع" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">مدة الاعلان -يوم</label>
					<input type="number" lang="en" value="<?php echo $this->config_item['ADV_DAYS']['NAME'];?>" class="form-control" name="days" placeholder=" عمولة البيع" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">عمولة الاستثمار</label>
					<input type="number" lang="en" value="<?php echo session::get("INVESTMENT");?>" class="form-control" name="invest" placeholder=" عمولة البيع" >
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">أقل سعر للخدمة</label>
					<input type="number" lang="en" value="<?php echo $this->config_item['SERVICE_MIN_PRICE']['NAME'];?>" class="form-control" min="1" name="SER_MIN_PRICE" placeholder=" أقل سعر للخدمة" >
					<div class="err_notification" id="valid_SER_MIN_PRICE">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">نسبة عمولة الموقع</label>
					<input type="number" lang="en" value="<?php echo $this->config_item['SERVICE_PERCENTAGE']['NAME'];?>" class="form-control" min="0" max="99" name="SER_PERC" placeholder=" عمولة الموقع" >
					<div class="err_notification" id="valid_SER_PERC">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">الشروط والاحكام</label>
					<textarea value="" class="form-control terms_policy" id="terms" name="terms" placeholder=" العرض في الصفحة" ><?php echo $this->config_item['TERMS']['NAME'];?></textarea>
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm">
					<label for="desc" class="">السياسات والخصوصية</label>
					<textarea value="" class="form-control terms_policy" id="policy" name="policy" placeholder=" العرض في الصفحة" ><?php echo $this->config_item['POLICY']['NAME'];?></textarea>
					<div class="err_notification" id="valid_desc">this field required</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-sm">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> تحديث البيانات</button>
				</div>
			</div>
		</form>
	</div>
</main>
