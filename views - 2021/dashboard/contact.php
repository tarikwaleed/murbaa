<!-- Breadcrumb Section Begin -->

	
	<div><br><br><br></div>
  <!-- Contact Form Section Begin -->
    <section class="contact-form-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cf-content">
                        <div class="cc-title">
                            <h2>تواصل معنا</h2>
                            <p>لأي استفسار قم بمراسلتنا </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	
	<!-- Contact Section Begin -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <div class="ci-item">
                            <div class="ci-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="ci-text">
                                <h5>العنوان</h5>
                                <p>المملكة العربية السعودية</p>
                            </div>
                        </div>
                        <div class="ci-item">
                            <div class="ci-icon">
                                <i class="fa fa-mobile"></i>
                            </div>
                            <div class="ci-text">
                                <h5>الهاتف</h5>
                                <ul>
                                    <p><?php echo PHONE_NUM?></p>
                                </ul>
                            </div>
                        </div>
                        <div class="ci-item">
                            <div class="ci-icon">
                                <i class="fa fa-headphones"></i>
                            </div>
                            <div class="ci-text">
                                <h5>البريد الإلكتروني</h5>
                                <p><?php echo EMAIL_ADD?></p>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="col-lg-6 spad">
					<form class="cc-form" id="contact_form" action="<?php echo URL?>dashboard/cont" method="post">
						<input type="hidden" class="hid_info" name="csrf" id="csrf" value="<?php echo lib::get_CSRF(); ?>" />
						<input type="text" name="name" class="form-control mb-3" value="<?php echo session::get('user_name'); ?>" placeholder="الاسم" data-rule="minlen:4" data-msg="من فضلك ادخل 3 حروف على الاقل" />
						<div class="err_notification" id="valid_name">
							الرجاء مراجعة هذا الحقل
						</div>
						<input type="email" class="form-control mb-3" name="email" value="<?php echo session::get('user_email'); ?>" placeholder="بريدك الإلكتروني" data-rule="email" data-msg="من فضلك ادخل بريدك الإلكتروني" />
						<div class="err_notification" id="valid_email">
							الرجاء مراجعة هذا الحقل
						</div>
						<input type="text" class="form-control mb-3" name="subject" placeholder="الموضوع" data-rule="minlen:4" data-msg="من فضلك ادخل 20 حرف على الاقل" />
						<div class="err_notification" id="valid_subject">
							الرجاء مراجعة هذا الحقل
						</div>
						<textarea class="form-control mb-3" name="message" rows="5" data-rule="required" data-msg="من فضلك رسالتك" placeholder="اكتب رسالتك هنا..."></textarea>
						<div class="err_notification" id="valid_message">
							الرجاء مراجعة هذا الحقل
						</div>
						<button type="submit" class="btn btn-primary">إرسال</button>
					</form>
				</div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->
