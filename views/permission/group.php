<div><br><br><br><br><br><br><br></div>
<div id="staff_settings" class="vue_area_div">
	<form id="form_action" method="post" href="" v-on:submit.prevent="add_upd">
		<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
		<input type="hidden" name="id" class="hid_info" :value="group.ID" />
		<!-- Search Section Begin -->
		<section class="" id="">
			<div class="container bg-white p-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title ">
							<h4>إضافة / تحديث الصلاحيات</h4>
						</div>
					</div>
				</div>
				<div class="">
					<div class="row p-0">
						<div class="col-sm mb-2">
							<label for="name" class="">اسم الصلاحية</label>
							<input type="text" class="form-control" name="name" :value="group.NAME" />
							<div class="err_notification" id="valid_name">هنالك خطأ في هذا الحقل</div>
						</div>	
						<div class="col-sm-6 mb-2">
							<label for="desc" class="">وصف الصلاحية</label>
							<input type="text" name="desc" class="form-control" :value="group.DESCR" />
							<div class="err_notification" id="valid_desc">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="col-sm mb-4">
							<label for="" class="">عدد المستخدمين: {{group.STAFF}}</label>
							
						</div>
					</div>
					<div class="row">
						
					</div>
					<div  id="accordion" class="row">
						<div class="col-lg-12">
							<div class="card" v-for="(PG ,CLS) in pages">
								<div class="card-header" :id="'CLS_'+CLS">
									<h4 class="btn btn-link collapsed mb-0 p-0" data-toggle="collapse" :data-target="'#pg_'+CLS" aria-expanded="false" aria-controls="collapseTwo">
										{{PG['index'].NAME}}
									</h4>
									<span class="pull-left"> اختر الصفحة الافتراضية
										<input name="def_page" type="radio" class="def_page" :data-cls="CLS" :value="PG.index.ID" :checked="PG.index.ID == group.DEF_PG_ID" />
										<span class="fa fa-caret-down collapsed" data-toggle="collapse" :data-target="'#pg_'+CLS" aria-expanded="false" aria-controls="collapseTwo"> </span>
									</span>
									
								</div>
								<div :id="'pg_'+CLS" class="collapse active" :aria-labelledby="'CLS_'+CLS" data-parent="#accordion">
									<div class="card-body table-responsive p-0">
										<table class="table table-bordered table-hover table_">
											<thead>
												<tr>
													<th>الامر</th>
													<th>الوصف</th>
													<th>الصلاحية</th>
													<th><input type="checkbox" class="select_all" :data-cls="CLS"  value="" /> اضافة / ازالة</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(x ,index) in PG">
													<td>{{index}}</td>
													<td>{{x.DEF_DESC}}</td>
													<td>{{x.PER_TYPE}}</td>
													<td><input name="pages[]" type="checkbox" class="per_select" :data-cls="x.CL_NAME" :data-pg="x.PG" :value="x.ID" :checked="group.PAGES.includes(x.ID)" /></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>	
					<div class="row">
						<div class="err_notification" id="valid_def_page">عليك ان تختار صفحة افتاضية</div>
						<div class="err_notification" id="valid_pages">عليك ان تختار صفحة واحدة على الاقل</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3 mt-3" v-if="group.ID > 2 || group.ID == ''">
							<button type="submit" id="search" class="btn btn-block btn-success"><i class="fa fa-save"></i> حفــظ الصلاحيات</button>
						</div>
						<div class="col-sm mb-3 mt-3" v-else="">
							<h4>لا يمكنك تعديل بيانات هذه المجموعة </h4>
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>		
</div>
<script>
	var js_group 		= <?php echo json_encode($this->group); ?>;
	var js_pages 		= <?php echo json_encode($this->pages); ?>;
</script>