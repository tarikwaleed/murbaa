<div><br><br><br><br><br></div>
<div id="staff_settings" class="container">
	<!-- Search Section Begin ->
	<section class="search-section spad">
		<div class="container">
			<form class="filter-form" id="Staff_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>report/search">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="land" class="form-control" placeholder="رقم العقار في النظام" />
						</div>	
						<div class="col-sm mb-3">
							<input name="chatroom" class="form-control" placeholder="رقم العقد" /> 
						</div>	
						<div class="col-sm mb-3">
							<input name="bill" class="form-control" placeholder="رقم الإيصال" />
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->

	<!--Bill List-->
	<div class="property-comparison-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>رقم الفاتورة</th>
									<th v-if="admin==1">العميل</th>
									<th>رقم العقار الداخلي</th>
									<th>رقم العقار</th>
									<th>مدة الاعلان المميز</th>
									<th>الباقة</th>
									<th>المبلغ</th>
									<th>التاريخ</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in bills">
									<td>{{x.ID}}</td>
									<td v-if="admin==1">{{x.CO_NAME}}</td>
									<td>{{x.LAND_ID}}</td>
									<td>{{x.LAND_NO}}</td>
									<td>{{x.LAND_PERIOD}}</td>
									<td>{{x.BK_NAME}}</td>
									<td>{{x.AMOUNT}}</td>
									<td>{{x.BILL_DATE}}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<script>
	var js_bills 		= <?php echo json_encode($this->bills); ?>;
	var js_admin 		= <?php echo (empty(session::get('company')))?1:0; ?>;
	var js_statues 		= <?php echo json_encode(lib::$land_for); ?>;
</script>

