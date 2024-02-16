		<!-- Footer Section Begin -->
		<footer class="footer-section" id="footer_area">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-5">
						<div class="fs-about fs-widget">
							<!--div class="fs-logo">
								<a href="#">
									<img src="<?php echo URL ?>public/IMG/logo.png" alt="">
								</a>
							</div--->
							<h5>تابعنا على</h5>
							<div class="fs-social">
								<a href="<?php echo session::get("FACEBOOK");?>"><i class="fa fa-facebook"></i></a>
								<a href="<?php echo session::get("TWITTER");?>"><i class="fa fa-twitter"></i></a>
								<a href="<?php echo session::get("INSTAGRAM");?>"><i class="fa fa-instagram"></i></a>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-sm-5">
						<div class="fs-widget">
							<!--h5>روابط</h5>
							<ul>
								<li><a href="<?php echo URL?>dashboard/about">عن الموقع</a></li>
								<li><a href="<?php echo URL?>dashboard/contact">اتصل بنا</a></li>
							</ul-->
                            <img src="<?php echo URL?>public/IMG/REGA_REG_GR.jpg" class="img-fluid align-self-center ml-2 w-100 border border-success" style="height:90px;width:120px !important">
						</div>
					</div>
					<div class="col-lg-2 col-sm-6">
						<div class="fs-widget">
							<h5>روابط</h5>
							<ul>
								<!--li><a href="<?php echo URL?>login/register">تسجيل جديد</a></li>
								<li><a href="<?php echo URL?>login/">تسجيل دخول</a></li-->
								<li><a href="<?php echo URL?>dashboard/policy">السياسات والخصوصية</a></li>
								<li><a href="<?php echo URL?>dashboard/terms">الشروط و الأحكام</a></li>
                                <li><a href="<?php echo URL?>dashboard/about">عن الموقع</a></li>
								<li><a href="<?php echo URL?>dashboard/contact">اتصل بنا</a></li>
							</ul>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="fs-widget">
							<h5>اشترك فى القائمة البريدية</h5>
							<form id="mail_list" action="<?php echo URL?>dashboard/mail_list" method="post" class="subscribe-form">
								<input type="hidden" name="csrf" value="<?php echo lib::get_CSRF(); ?>" />
								<input type="text" placeholder="اكتب بريدك الإلكترونى ليصل كل جديد" name="email_list" required />
								<button type="submit" class="site-btn">اشترك الان</button>
							</form>
						</div>
					</div>
				</div>
<div class="d-flex justify-content-center">
				<div class="d-flex bg-white mb-3 border border-white rounded shadow" style="width:250px; height:60px">
    				<img src="<?php echo URL?>public/IMG/mastercardandvisa.jpg" class="img-fluid mr-2">
					<img src="<?php echo URL?>public/IMG/stc-pay-01.png" class="img-fluid mx-2">
					<img src="<?php echo URL?>public/IMG/1cd63acc2107c45813ec3bb88180afaa_icon.png" class="img-fluid align-self-center ml-2" style="height:60px; width:85px">
                    <!--img src="<?php echo URL?>public/IMG/REGA_REG.jpg" class="img-fluid align-self-center ml-2" style="height:60px; width:85px"-->
				</div>
</div>
				<div class="copyright-text">
					<p>
						&copy;<span class="default_year_place"></span> 
						كل الحقوق محفوظة - 
						لموقع مربع .. السوق العقاري
					</p>
				</div>
			</div>
		</footer>
		<!-- Footer Section End -->

		<!-- Js Plugins -->
		<div class="d-none" id="targetLayer"></div>
		<!--Target Progress Bar-->
		<div id="targetProgress" class="modal">
			<div class="modal-body" style="max-width:200px">
				<div class="progress">
					<div id="progress_area" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			var URL = "<?php echo URL?>";
			var E_HIDE = "d-none";
		</script>
			
		<!-- Js Plugins -->
		<script src="<?php echo URL?>public/JS/vue.min.js"></script>	
		<script src="<?php echo URL?>public/JS/jquery/jquery-3.3.1.min.js"></script>
		<script src="<?php echo URL?>public/JS/bootstrap.min.js"></script>
		<!--script src="<?php echo URL?>public/JS/bootstrap.bundle-4.5.2.min.js"></script-->
		<script src="<?php echo URL?>public/JS/js/mixitup.min.js"></script>
		<script src="<?php echo URL?>public/JS/jquery/jquery-ui.min.js"></script>
		<script src="<?php echo URL?>public/JS/jquery/jquery.slicknav.js"></script>
		<script src="<?php echo URL?>public/JS/jquery/jquery.form.js"></script>
		<!--script src="<?php echo URL?>public/JS/jquery/jquery.datetimepicker.full.js"></script-->
		<script src="<?php echo URL?>public/JS/js/owl.carousel.min.js"></script>
		<script src="<?php echo URL?>public/JS/main.js"></script>
		
		<script src="<?php echo URL?>public/JS/default.js"></script>
		<script type="text/javascript">
			vm_page_permission = new Vue({
				el: '#vue_links_area_div',
				data: {
					public_page			: <?php echo json_encode(session::get('public_pages')); ?>,
					public_login_page	: <?php echo json_encode(session::get('public_login_pages')); ?>,
					user_page			: <?php echo json_encode(session::get('user_pages')); ?>,
					
				},
				created: function() {
					
				},
				methods: {
					h_access($cls,$pg = "",$clas = "index")
					{
						if(this.public_page.includes($cls))
						{
							return true;
						}
						if(this.public_login_page.includes($cls))
						{
							return true;
						}
						if(Object.keys(this.user_page).includes($cls) && this.user_page[$cls].includes($clas))
						{
							return true;
						}
						return false;
					},
					
				}
			});
			
		</script>
		
	<?php
		if(isset($this->JS))
		{
			foreach($this->JS as $v)
			{
				echo '<script src="'.URL.$v.'"></script>';
			}
		}
	?>
	</body>

</html>
