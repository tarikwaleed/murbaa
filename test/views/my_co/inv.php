<main id="staff_info" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div" style="margin-top:100px;">
	<div class="mb-3 border-bottom">
		<h4 class="h4"> <i class="fa fa-user-circle text-primary mx-2"></i>الدعوات والاعلانات الخاصة </h4>
	</div>
	<div class="d-flex flex-column text-center">
		<div class="m-2">
			<h6>
للحصول على مميزات قم بدعوة عملائك, وكلما زاد العدد زاد عدد النجوم التي تميز حسابك.
يمكنك دعوة العملاء من خلال الرابط بالأسفل
</h6>
		</div>
<div class="container">
<div class="row">
<div class="share-ad mt-2 position-relative mx-auto col-sm-10 col-md-8 col-lg-5">
                            <p class="card p-3 m-0" style="padding-right:60px !important;"><?php echo URL."login/register/".session::get('company')?></p>
                            <i class="fa fa-share position-absolute a2a_dd" style="cursor:pointer" data-a2a-url=<?php echo URL."login/register/".session::get('company')?>>
<a href="https://www.addtoany.com/share"></a>
</i>
<!-- AddToAny BEGIN -->
<script async src="https://static.addtoany.com/menu/page.js"></script>
<!-- AddToAny END -->

		</div>
</div>
</div>
   	</div>
	<!--div class="container-fluid my-2" v-if="info.CO_ACCEPT == null || info.CO_ACCEPT == 0">
		<div class="row">
		<div class="col-lg-4 col-md-6 col-8 alert alert-info text-center w-50" id="err_registration">
			عليك ان توثق حسابك لكي تضيف قائمة اعلانات خاصة
		</div>
		</div>
	</div>
	<form v-else=""  class="g-3" id="staff_form" method="post" action="<?php echo URL?>my_co/invites" data-model="new_land" data-type="new_land">
		<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />

		<h5 class="mx-auto p-2 rounded m-3" style="width:fit-content">
            اختر مجموعة عملائك الخاصة
        </h5>

<div class="container">
		<div class="row">
        	<table class="table table-bordered table-striped table-head-fixed text-right">
				<thead class="text-light" style="background-color:#dcae5f">
					<tr align="center">
						<th><input type="checkbox" id='msgs' /></th>
						<th>الصورة</th>
						<th>الاسم</th>
						<!--th>الهاتف</th>
						<th>البريد الإلكتروني</th->
					</tr>
				</thead>
				<tbody>
					<tr align="center" v-for="(x ,index) in company">
						<td><input type="checkbox" name="company[]" class="msgs" :value="x.ID" :checked="x.ADV != null" /></td>
						<td><img v-bind:src="x.IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100"/></td>
						<td>{{x.NAME}}</td>
						<!--td>{{x.PHONE}}</td>
						<td>{{x.EMAIL}}</td->
					</tr>
				</tbody>
			</table>
		</div>
		<div class="text-center">
			<button type="submit" class="btn btn-primary p-3 rounded"><i class="fa fa-save"></i> حفط القائمة</button>
		</div>
</div>
	</form-->
</main>
<br/><br/>
<script>
	var js_company 	= <?php echo json_encode($this->comp_list); ?>;
	var js_info 	= <?php echo $this->sys_info; ?>;
	var upg_pay		= <?php echo (!empty($this->upgrade))?json_encode($this->upgrade):"''"; ?>;
	var JS_KEY		= <?php echo "'".P_JS_KEY."'"; ?>
</script>
