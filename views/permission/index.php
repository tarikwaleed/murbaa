<div><br><br><br><br><br></div>
<div class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div" id="staff_settings">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-lock text-primary"></i> الصلاحيات</h4>
	</div>
	<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
	<div class="property-comparison-section">
        <div class="container">
			<div class="row">
				<div class="mb-3">
					<a target="_blank" href="<?php echo URL?>permission/new_group" class="btn btn-success">إضافة مجموعة جديدة <i class="fa fa-plus"></i></a>
				</div>
			</div>
            <div class="row">
                <div class="col-lg-12 p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-head-fixed text-right">
                            <thead>
								<tr>
									<th>الرقم</th>
									<th>الاسم</th>
									<th>الصلاحية</th>
									<th>عدد المستخدمين</th>
									<th colspan="2">الصفحة الافتراضية</th>
									<th colspan="2">الأجراء</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(x ,index) in group">
									<td>{{index + 1}}</td>
									<td>{{x.NAME}}</td>
									<td>{{x.DESCR}}</td>
									<td>{{x.STAFF}}</td>
									<td>{{x.DEF_CLS}}/{{x.DEF_PG}}</td>
									<td>{{x.DEF_DESC}}</td>
									<td><a target="_blank" :href="x.LINK" class="btn btn-sm btn-primary rounded">نفاصيل</a></td>
									<td><button class="btn btn-sm btn-danger rounded" v-if="x.ID > 2 && x.STAFF == 0" @click="del(x.ID)">حذف</button></td>
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
	var js_group 		= <?php echo json_encode($this->group); ?>;
	var js_pages 		= <?php echo json_encode($this->pages); ?>;
</script>