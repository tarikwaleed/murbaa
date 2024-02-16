<br/><br/><br/><br/><br/>
<main id="staff_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> خدمات العملاء</h4>
	</div>
	<!-- Search Section Begin -->
	<section class="search-section">
		<div class="container p-0">
			<form class="filter-form" id="ser_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>ser_admin/">
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<input name="name" class="form-control" placeholder="العنوان" />
						</div>	
						<div class="col-sm mb-3">
							<select name="cont_type" class="form-control" >
								<option value="" selected></option>
								<option v-for="(x,index) in cont_type" v-bind:value="index">{{x}}</option>
							</select>
						</div>		
						<div class="col-sm mb-3">
							<select name="cont_status" class="form-control" >
								<option value="" selected></option>
								<option v-for="(x,index) in cont_status" v-bind:value="index">{{x}}</option>
							</select>
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

	<!--owner List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th rowspan="2">العنوان</th>
									<th rowspan="2">المالك</th>
									<th rowspan="2">تاريخ الانشاء</th>
									<th rowspan="2">السعر</th>
									<th rowspan="2">نوع العقد</th>
									<th rowspan="2">عدد العروض</th>
									<th rowspan="2">الحالة</th>
									<th colspan="3">العرض المقبول</th>
                                </tr>
                                <tr>
									<th>صاحب العرض</th>
									<th>قيمة العرض</th>
									<th>مدة التنفيذ</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in services">
									<td>{{x.TITLE}}</td>
									<td>
										<a target="_blank" :href="x.CO_LINK">
											<img :src="x.CO_IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="--"> 
											<span>{{x.CO_NAME}}</span>
										</a>
									</td>
									<td>{{x.CREATE_TIME}}</td>
									<td>{{x.PRICE_FROM}} - {{x.PRICE_TO}}</td>
									<td>{{cont_type[x.CONTRACT_TYPE]}}</td>
                                    <td>{{x.OFF_NO}}</td>
									<td>{{cont_status[x.STATUS]}}</td>
									<td>
										<a target="_blank" :href="x.OFF_LINK">
											<img :src="x.OFF_IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="--"> 
											<span>{{x.OFF_CO_NAME}}</span>
										</a>
									</td>
									<td>{{x.OFFER_PRICE}}</td>
									<td>{{x.OFFER_PERIOD}}</td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</main>
<script>
	var js_cont_type= <?php echo json_encode(lib::$contract_type); ?>;
	var js_cont_status= <?php echo json_encode(lib::$service_status); ?>;
	var js_reg_type= <?php echo json_encode(lib::$ser_reg_type); ?>;
	var js_ser_type= <?php echo json_encode(lib::$service_type); ?>;
</script>