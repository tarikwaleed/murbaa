<style>


  .navbar-nav .nav-item a.btn-secondary{

    background-color: #d49a38;
    border-color: #d49a38;

  } 

  .nav-item .nav-link{
    color: rgb(43 167 111) !important
  }

.navbar{
    position:fixed !important;
    width:100% !important;
}

</style>


<nav class="navbar navbar-expand-lg navbar-light border-bottom sticky-top bg-white">
	<a class="navbar-brand logo" href="<?php echo URL?>"><img src="<?php echo URL."public/IMG/logo.png";?> " alt=""><!-- عقارات--> </a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="d-none nav-item dashboard_adv_search">
				<a class="btn btn-secondary rounded m-1" data-toggle="collapse" href="#searchco" role="button" aria-expanded="false" aria-controls="searchco">
					البحث المتقدم
				</a>
			</li>
			<li class="nav-item"><a href="<?php echo URL?>login/" class="btn btn-secondary m-1 rounded">إضافة إعلان</a></li>
			<li class="nav-item"><a class="nav-link" href="<?php echo URL?>login/register"><span class="fa fa-user-plus "></span> تسجيل جديد </a></li>
			<li class="nav-item"><a class="nav-link" href="<?php echo URL?>login/"><span class="fa fa-sign-in "></span> دخول</a></li>
		</ul>
	</div>
</nav>

