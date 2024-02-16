<div><br><br><br><br><br></div>
<main id="city_area" class="col-md-9 ms-sm-auto col-lg-12 px-md-4 vue_area_div">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h4 class="h4"><i class="fa fa-map text-primary"></i> المدن</h4>
	</div>
	
	<!-- display table city -->
	<div id="accordion" class="container-sm">
		<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#new_city">إضافة مدينة <i class="fa fa-plus"></i></button>
		<button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#new_nei">إضافة حي <i class="fa fa-plus"></i></button>
		<div class="card" v-for="(CI ,CI_index) in cities">
			<div class="card-header" :id="'city_'+CI_index">
				<h4 class="btn btn-link collapsed mb-0 p-0" data-toggle="collapse" :data-target="'#neig_'+CI_index" aria-expanded="false" aria-controls="collapseTwo">
					{{CI.NAME}} -- {{CI.AREA}}
				</h4>
				<button v-on:click.prevent="upd_city(CI_index)" type="button" data-toggle="modal" data-target="#upd_city" class="btn btn-primary btn-sm rounded pull-left"><i class="fa fa-edit"></i> تحديث</button>
				<button v-on:click.prevent="del_city(CI_index)" v-if="CI.NEIGHBOR.length == 0" type="button" data-toggle="modal" data-target="#del_city" class="btn btn-primary btn-sm btn-danger pull-left ml-1"><i class="fa fa-close"></i> حذف</button>
			</div>
			<div :id="'neig_'+CI_index" :class="(CI_index==0)?'collapse active':'collapse'" :aria-labelledby="'city_'+CI_index" data-parent="#accordion">
				<div class="card-body table-responsive p-0">
					<table id="datatable" class="table table-bordered table-hover table_">
						<thead>
							<tr>
								<th>الرقم</th>
								<th>اسم الحي</th>
								<th>الحرف</th>
								<th>عدد العقارات</th>
								<th colspan="2">الاجراء</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(NEI ,NEI_index) in CI.NEIGHBOR">
								<td>{{NEI_index + 1}}</td>
								<td>{{NEI.NAME}}</td>
								<td>{{NEI.LETTER}}</td>
								<td>{{NEI.LANDS}}</td>
								<td><button v-on:click.prevent="upd_nei(NEI_index,CI_index)" type="button" data-toggle="modal" data-target="#upd_nei" class="btn btn-primary btn-sm rounded"><i class="fa fa-edit"></i> تحديث</button></td>
								<td><button v-on:click.prevent="del_nei(NEI_index,CI_index)" v-if="NEI.LANDS == 0" type="button" data-toggle="modal" data-target="#del_nei" class="btn btn-danger rounded btn-sm"><i class="fa fa-trash"></i> حذف</button></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal For add new city -->
	<div id="new_city" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_city_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="new_city_form" method="post" action="<?php echo URL?>city/new_city" data-model="new_city" data-type="new_city">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_city_title"><i class="fa fa-plus"></i> إضافة مدينة</h5>
						<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_name" class="">اسم المدينة</label>
							<input type="text" class="form-control" name="new_name" id="new_name" placeholder=" ادخل اسم المدينة" required />
							<div class="d-none err_notification" id="valid_new_name">this field required</div>
						</div>
						<div class="col-auto">
							<label for="new_area" class="">اسم المنطقة</label>
							<input type="text" class="form-control" name="new_area" id="new_area" placeholder=" ادخل اسم المنطقة" required />
							<div class="d-none err_notification" id="valid_new_area">this field required</div>
						</div>
						<div class="form_msg d-none">تم حفط المدينة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط المدينة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For update city -->
	<div id="upd_city" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_cat_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="upd_city_form" method="post" action="<?php echo URL?>city/upd_city" data-model="upd_city" data-type="upd_city">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_cat_title"><i class="fa fa-edit"></i> تحديث مدينة</h5>
						 <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" id="upd_id" value="" />
						<input type="hidden" class="" id="upd_index" value="" />
						<div class="col-auto">
							<label for="city" class="">اسم المدينة</label>
							<input type="text" class="form-control" name="upd_name" id="upd_name" placeholder=" ادخل اسم المدينة" required />
							<div class="d-none err_notification" id="valid_upd_name">this field required</div>
						</div>
						<div class="col-auto">
							<label for="upd_area" class="">اسم المنطقة</label>
							<input type="text" class="form-control" name="upd_area" id="upd_area" placeholder=" ادخل اسم المنطقة" required />
							<div class="d-none err_notification" id="valid_upd_area">this field required</div>
						</div>
						<div class="form_msg d-none">تم تحديث المدينة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-edit"></i> تحديث المدينة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For delete city -->
	<div id="del_city" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="del_city_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="upd_cat_form" method="post" action="<?php echo URL?>city/del_city" data-model="del_city" data-type="del_city">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="del_city"><i class="fa fa-trash"></i> حذف مدينة</h5>
						 <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" id="del_id" value="" />
						<input type="hidden" class="" id="del_index" value="" />
						<div class="col-auto">
							<label for="del_name" class="">اسم المدينة</label>
							<input type="text" class="form-control" name="del_name" id="del_name" placeholder=" ادخل اسم المدينة" readonly />
						</div>
						<div class="form_msg d-none">تم حذف المدينة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger mb-3"><i class="fa fa-trash"></i> حذف المدينة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For add new neigborhood -->
	<div id="new_nei" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="new_nei_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="new_city_form" method="post" action="<?php echo URL?>city/new_nei" data-model="new_nei" data-type="new_nei">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_nei_title"><i class="fa fa-plus"></i> إضافة حي</h5>
						<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="col-auto">
							<label for="new_nei_name" class="">اسم الحي</label>
							<input type="text" class="form-control" name="new_nei_name" id="new_nei_name" placeholder=" ادخل اسم الحي" required />
							<div class="d-none err_notification" id="valid_new_nei_name">this field required</div>
						</div>
						<div class="col-auto">
							<label for="new_letter" class="">الحرف</label>
							<select name="new_letter" id="new_letter" class="form-control" >
								<option value="" >إختار حرف الحى</option>
								<option v-for="x in letters" :value="x" >{{x}}</option>
							</select>
							<div class="d-none err_notification" id="valid_new_letter">this field required</div>
						</div>
						<div class="col-auto">
							<label for="new_nei_city">المدينة</label>
							<select id="new_nei_city" name="new_nei_city" class="form-control">
								<option value="" selected disabled></option>
								<option v-for="(ci,index) in cities" v-bind:data-id="index" v-bind:value="ci.ID">{{ci.NAME}}</option>
							</select>
							<div class="err_notification" id="valid_new_nei_city">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الحي</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الحي</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For update neigborhood -->
	<div id="upd_nei" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="upd_nei_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="new_city_form" method="post" action="<?php echo URL?>city/upd_nei" data-model="upd_nei" data-type="upd_nei">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_nei_title"><i class="fa fa-plus"></i> تحديث حي</h5>
						<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" id="upd_nei_id" name="id" value="" />
						<input type="hidden" class="" id="upd_nei_old_city" value="" />
						<input type="hidden" class="" id="upd_nei_index" value="" />
						<div class="col-auto">
							<label for="upd_nei_name" class="">اسم الحي</label>
							<input type="text" class="form-control" name="upd_nei_name" id="upd_nei_name" placeholder=" ادخل اسم الحي" required />
							<div class="d-none err_notification" id="valid_upd_nei_name">this field required</div>
						</div>
						<div class="col-auto">
							<label for="upd_letter" class="">الحرف</label>
							<select name="upd_letter" id="upd_letter" class="form-control" >
								<option value="" >إختار حرف الحى</option>
								<option v-for="x in letters" :value="x" >{{x}}</option>
							</select>
							<div class="d-none err_notification" id="valid_upd_letter">this field required</div>
						</div>
						<div class="col-auto">
							<label for="upd_nei_city">المدينة</label>
							<select id="upd_nei_city" name="upd_nei_city" class="form-control">
								<option value="" selected disabled></option>
								<option v-for="(ci,index) in cities" v-bind:data-id="index" v-bind:value="ci.ID">{{ci.NAME}}</option>
							</select>
							<div class="err_notification" id="valid_upd_nei_city">هنالك خطأ في هذا الحقل</div>
						</div>
						<div class="form_msg d-none">تم حفط الحي</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> حفط الحي</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For delete neigborhood -->
	<div id="del_nei" class="modal fade modal_with_form" tabindex="-1" aria-labelledby="del_nei_title" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="row g-3 model_form" id="upd_cat_form" method="post" action="<?php echo URL?>city/del_nei" data-model="del_nei" data-type="del_nei">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="del_nei_title"><i class="fa fa-trash"></i> حذف الحي</h5>
						 <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" id="del_nei_id" name="id" value="" />
						<input type="hidden" class="" id="del_nei_city" value="" />
						<input type="hidden" class="" id="del_nei_index" value="" />
						<div class="col-auto">
							<label for="del_name" class="">اسم الحي</label>
							<input type="text" class="form-control" name="del_nei_name" id="del_nei_name" placeholder=" ادخل اسم المدينة" readonly />
						</div>
						<div class="form_msg d-none">تم حذف الحي</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger mb-3"><i class="fa fa-trash"></i> حذف  الحي</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<br/><br/><br/><br/><br/><br/>
</main>


<script>
	var js_cities 	= <?php echo json_encode($this->cities); ?>;
	var js_letters 	= <?php echo json_encode(lib::$letters); ?>;
</script>