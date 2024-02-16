<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom sticky-top">
	<a class="navbar-brand logo" href="<?php echo URL?>"><img src="<?php echo URL ?>public/IMG/logo.png" alt=""><!-- عقارات--> </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto" id="nav-menu-links-list">
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>services">
					<span class="fa fa-ticket"></span>  العقود
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>my_land">
					<span class="fa fa-home"></span> عقاراتي
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>tickets">
					<span class="fa fa-ticket"></span> التذاكر
				</a>
			</li>
			<?php 
				if(session::get('PK_USERS') > 1)
				{
			?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo URL?>permission">
							<span class="fa fa-lock"></span> الصلاحيات
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo URL?>staff">
							<span class="fa fa-users"></span> الموظفين
						</a>
					</li>
			<?php
				}
			?>
			<li class="d-none dashboard_adv_search nav-item">
				<a class="btn btn-secondary btn-sm rounded" data-toggle="collapse" href="#searchco" role="button" aria-expanded="false" aria-controls="searchco">
					البحث المتقدم
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>payment"> 
					<span class="fa fa-usd"></span> المدفوعات</a></li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>my_co"> 
					<span class="fa fa-cog"></span> <?php echo session::get('com_name')?> المكتب
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>my_co/invites"> 
					<span class="fa fa-cog"></span> الدعوات
				</a>
			</li>
			<!--li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>my_co/registration"> 
					<span class="fa fa-cog"></span> توثيق الحساب
				</a>
			</li-->
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>profile">
					<span class="fa fa-user-circle"></span> بروفايل
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo URL?>login/logout">
					<span class="fa fa-sign-out"></span> خروج
				</a>
			</li>
		</ul>
	</div>
</nav>
