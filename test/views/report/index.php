<div><br><br><br><br><br></div>
<main id="types_settings" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-users text-primary"></i> البلاغات</h4>
	</div>
	
	<!--owner List-->
	<div class="property-comparison-section">
        <div class="container">
            <div class="row">
				<div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>الرقم</th>
									<th>صاحب البلاغ</th>
									<th>تاريخ البلاغ</th>
									<th>المبلغ عنه</th>
									<th>العقار</th>
									<th>البلاغ</th>
									
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in rep_list">
									<td>{{x.ID}}</td>
									<td>{{x.OWN_NAME}} -- <a target="_blank" :href="x.OWN_LINK"> {{x.OWN_CO_NAME}}</a></td>
									<td>{{x.REP_TIME}}</td>
									<td><a target="_blank" :href="x.CUS_LINK"> {{x.CUS_NAME}}</a></td>
									<td v-if="x.LAND !== null"><a target="_blank" :href="x.LAND_LINK"> {{x.LAND}}</a></td>
									<td v-else=""></td>
									<td>{{x.MSG}}</td>
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
	var js_list 	= <?php echo json_encode($this->rep_list); ?>;
</script>