<style>
	button.page-link {
		display: inline-block;
	}

	.map_area {
		height: 200px;
		width: 100%;
	}
</style>

<div id="vue_area_div" class="container vue_area_div">
	<!-- Search Section Begin -->
	<section class="search-section bg-white spad">
		<div class="container bg-white p-3">
			<!--div class="row">
				<div class="col-lg-12">
					<div class="section-title">
						<h4>العقارات/الطلبات التي اديرها</h4>
					</div>
				</div>
			</div-->
			<form class="filter-form" id="land_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL ?>my_land/">
				<input type="hidden" name="paging_curr_no" id="paging_curr_no" value="1" />
				<input type="hidden" name="limit" id="paging_length" value="<?php echo session::get("PAGING") ?>" />
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<select name="city" id="city" class="form-control" @change="onChangeCity($event)">
								<option value="" selected disabled>اختر المدينة</option>
								<option v-for="(x,id) in cities" v-bind:data-no="id" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<select name="neighborhood" id="neighborhood" class="form-control">
								<option value="" selected>اختر الحي</option>
								<option v-for="x in neighborhood" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<input type="number" name="block" class="form-control" placeholder="المربع" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<select name="adv" id="adv" class="form-control">
								<option value="" selected>اختر النوع</option>
								<option v-for="(x,id) in adv" v-bind:value="id">{{x}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<select name="types" id="types" class="form-control">
								<option value="" selected>اختر النوع</option>
								<option v-for="(x,id) in types" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<input name="rooms" type="number" class="form-control" placeholder="عدد الغرف" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3 small">
							<span>المساحة الإعلانية المتاحة: <span v-if="(config.AREA - config.ALL_LANDS) > 0">{{config.AREA - config.ALL_LANDS}}</span><span v-else="">0</span> </span> <br />
							<span>المساحة الإعلانية VIP المتاحة: <span v-if="(config.VIP - config.CURR_VIP) > 0">{{config.VIP - config.CURR_VIP}}</span><span v-else="">0</span> </span>
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary">بحـــث <i class="fa fa-search"></i></button>
						</div>
						<div class="col-sm mb-3" v-if="config.AREA - config.ALL_LANDS > 0 ">
							<button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_land_api">إضافة إعلان <i class="fa fa-plus"></i></button>
						</div>
						<div class="col-sm mb-3" v-else="">
							<button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_error">إضافة إعلان <i class="fa fa-plus"></i></button>
						</div>
						<div class="col-sm mb-3">
							<button type="button" v-on:click.prevent="new_land('REQ')" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_request">إضافة طلب <i class="fa fa-plus"></i></button>
						</div>

					</div>
				</div>
				<div class="more-option">
					<div class="accordion" id="accordionExample">
						<div class="card">
							<div class="card-heading active">
								<a data-toggle="collapse" data-target="#collapseOne">
									خيارات بحث إضافية
								</a>
							</div>
							<div id="collapseOne" class="collapse" data-parent="#accordionExample">
								<div class="card-body">
									<div class="mo-list">
										<div class="ml-column">
											<label for="interface">واجهة على شارع رئيسي
												<input type="checkbox" id="interface" name="interface" value="1">
												<span class="checkbox"></span>
											</label>
											<label for="corner">زاوية
												<input type="checkbox" id="corner" name="corner">
												<span class="checkbox"></span>
											</label>
										</div>
										<div class="ml-column">
											<div class="row mb-3">
												<label>الغرف</label>
												<div class="col">
													<input name="rooms_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="rooms_max" class="form-control" type="number" />
												</div>
											</div>
											<div class="row mb-3">
												<label>السعر</label>
												<div class="col">
													<input name="price_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="price_max" class="form-control" type="number" />
												</div>
											</div>
										</div>
										<div class="ml-column">
											<div class="row mb-3">
												<label>الحجم</label>
												<div class="col">
													<input name="size_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="size_max" class="form-control" type="number" />
												</div>
											</div>
											<div class="row mb-3">
												<label>الطابق</label>
												<div class="col">
													<input name="floor_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="floor_max" class="form-control" type="number" />
												</div>
											</div>
										</div>
										<div class="ml-column last-column">
											<div class="row mb-3">
												<label class="">الحمامات</label>
												<div class="col">
													<input name="baths_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="baths_max" class="form-control" type="number" />
												</div>
											</div>
											<div class="row mb-3">
												<label class="">الشارع</label>
												<div class="col">
													<input name="road_min" class="form-control" type="number" />
												</div> -
												<div class="col">
													<input name="road_max" class="form-control" type="number" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<!-- Search Section End -->

	<!-- Property Section Begin -->
	<section class="property-section latest-property-section pt-2 ">
		<div class="container ">
			<div class="row property-filters ">
				<div v-for="(x , land_index) in displayedPosts" class="col-md-12">
					<div class="row property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm h-md-200 position-relative" style="height:auto">
						<div class="col p-4 d-flex flex-column position-static">
							<div class="pi-text">
								<h5><a v-bind:href="'<?php echo URL ?>dashboard/land/'+x.ID">{{types[x.TYPE].NAME}} <span v-if="x.NEI_NAME"> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}} </span></a></h5>
								<div class="pt-price">{{x.PRICE}} <b class="pt-price" v-if="x.PRICE_M"> - {{x.PRICE_M}}</b> {{x.CURRENCY}}<span v-if="x.FOR != 'SALE' && x.FOR != 'INVEST'"> / {{statues[x.FOR].S_NAME}}</span></div>
								<!--div>
									<a v-bind:href="x.LOCATION" target="_blank" v-if="x.LOCATION ">
										<span class="fa fa-map-marker"></span> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}
									</a>
								</div>
								<!--div class="pi-date">{{x.ACT_DATE}}</div-->
								<div class="pi-span d-none d-md-block d-lg-block">
									<span><i class="fa fa-object-group"></i> {{x.SIZE}} <span v-if="x.SIZE_M"> - {{x.SIZE_M}}</span> م²</span>
									<span v-if="x.IS_RES"><i class="fa fa-bathtub"></i> {{x.BATHS}} <span v-if="x.BATHS_M"> - {{x.BATHS_M}}</span></span>
									<span v-if="x.IS_RES"><i class="fa fa-bed"></i> {{x.ROOMS}} <span v-if="x.ROOMS_M"> - {{x.ROOMS_M}}</span></span>
									<span v-if="x.IS_RES"><i class="fa fa-automobile"></i> {{x.CARS}}</span>
								</div>
								<div class="pi-span d-none d-md-block d-lg-block">
									<div class="dec"> {{x.DESC}} </div>
								</div>
								<div class="mt-2">
									<button v-if="x.ADV == 'ADV'" type="button" v-on:click="update_land(land_index)" class="btn btn-primary mb-3" data-toggle="modal" data-target="#upd_land">تعديل </button>
									<button v-else="" type="button" v-on:click="update_land(land_index)" class="btn btn-primary mb-3" data-toggle="modal" data-target="#upd_request">تعديل </button>
									<button v-if="x.ACTIVE == 1" v-on:click.prevent="active(land_index)" class='btn  btn-warning mb-3'>تجميد</button>
									<button v-else="" v-on:click.prevent="active(land_index)" class='btn  btn-success mb-3 '>فك تجميد</button>
									<button type="button" v-on:click="update_land(land_index)" class="btn btn-danger mb-3" data-toggle="modal" data-target="#del_land">حذف </button>
									<span v-if="x.ADV == 'ADV'">

										<p v-if="x.PACKAGE_START">متميز من {{x.PACKAGE_START}} - {{x.PACKAGE_END}}</p>
										<button v-else-if="config.VIP - config.CURR_VIP > 0" v-on:click.prevent="vip(land_index)" class='btn btn-success mb-3 '>عرض مميز</button>
										<button v-else="" v-on:click.prevent="by_vip(land_index)" class='btn btn-success mb-3' data-toggle="modal" data-target="#vip_land">شراء عرض مميز</button>

										<button v-if="config.PK_PRICE == 1 && x.BILL == 0" v-on:click.prevent="by_land(land_index)" class='btn btn-success mb-3' data-toggle="modal" data-target="#vip_land">دفع عمولة الإعلان</button>
										<form method="POST" action="<?php echo URL ?>my_land">
											<input type="hidden" name="refrech" value="refrech">
											<input type="hidden" name="id_land" v-bind:value="x.ID">
											<button type="submit" class="btn btn-success mb-3">إعادة النشر </button>
										</form>
									</span>
								</div>
							</div>
						</div>
						<div class="col-1 text-center d-none d-md-block d-lg-block"><br>
							<span v-if="x.VISIT != 0">
								<h5><i class="fa fa-eye"></i></h5>{{x.VISIT}}
							</span>
							<span v-else="">
								<h5><i class="fa fa-eye-slash"></i></h5><br />
							</span>
							<span>
								<h5><i class="fa fa-clock-o"></i></h5>
								{{x.DATE}}
							</span>
							<span title="تحديث بيانات العقار يجدد تاريخ الانتهاء">
								<h5><i class="fa fa-hourglass-half"></i></h5>
								{{x.EXPERED}}
							</span>
						</div>
						<div class="col-auto">
							<a v-bind:href="'<?php echo URL ?>dashboard/land/'+x.ID" target="_blank">
								<div class="pi-pic set-bg rounded" v-bind:data-setbg="x.IMG">
									<div v-bind:class="get_type_color(x.FOR)"> {{statues[x.FOR].NAME}} - {{x.ADV_NAME}} </div>

								</div>
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- pagination -->
			<nav aria-label="" data-aos="fade-up" id="paging">
				<ul class="pagination justify-content-center mt-3">
					<li class="page-item">
						<button type="button" class="page-link" v-if="current_page != 1" @click="current_page--"> السابق </button>
					</li>
					<li class="page-item">
						<button type="button" class="page-link" v-for="pageNumber in pages.slice(current_page-1, current_page+5)" @click="current_page = pageNumber"> {{pageNumber}} </button>
					</li>
					<li class="page-item">
						<button type="button" class="page-link" v-if="current_page < pages.length" @click="current_page++"> التالى </button>
					</li>
				</ul>
			</nav>
		</div>
	</section>

	<!-- Property Section End -->
	<style>
		.floating-panel {
			position: absolute;
			top: 10px;
			left: 25%;
			z-index: 5;
			background-color: #fff;
			padding: 5px;
			border: 1px solid #999;
			text-align: center;
			font-family: "Roboto", "sans-serif";
			line-height: 30px;
			padding-left: 10px;
		}
	</style>

	<div class="modal bd-example-modal-lg" id="new_error">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="new_error_title"><i class="fa fa-plus"></i> إضافة إعلان</h5>
					<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
				</div>
				<div class="modal-body">
					<h4>للأسف لا يمكنك إضافة إعلان, عليك التحقق من بيانات الحساب</h4>
					<br />
					<div v-if="config.AREA - config.ALL_LANDS <= 0"> لقد بلغ عدد الإعلانات الحد المسموح به, قم بترقية حسابك لكي تضيف إعلان جديد</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal For add new Request -->
	<div class="modal bd-example-modal-lg modal_with_form" id="new_request">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="new_request_form" method="post" action="<?php echo URL ?>my_land/new_request" data-model="new_request" data-type="new_request">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_request_title"><i class="fa fa-plus"></i> إضافة طلب</h5>
						<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div class="floating-panel">
							<input class="delete-circle" type="button" value="مسح دائرة" />
							<input class="delete-rectangle" type="button" value="مسح مستطيل">
						</div>
						<div id="request_map_area" class="map_area"></div>
						<input type="hidden" id="request_map_data" name="request_map_data" value='' />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="request_desc" class="">وصف العقار</label>
								<textarea type="text" id="request_desc" name="request_desc" class="form-control" placeholder="وصف العقار" required></textarea>
								<div class="d-none err_notification" id="valid_request_desc">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="request_space" class="">المساحة</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_space_from" name="request_space_from" class="form-control" placeholder="المساحة من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_space_to" name="request_space_to" class="form-control" placeholder="المساحة الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_request_space">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="request_type" class="">النوع</label>
								<select name="request_type" id="request_type" class="form-control" required @change="onChangeType($event,'request_home_area','request_farm_area')">
									<option value="" selected disabled>اختر النوع</option>
									<option v-for="(x,id) in types" :value="x.ID" :data-id="id">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="request_for" class="">الغرض</label>
								<select name="request_for" id="request_for" class="form-control" required @change="onChangeFor($event,'sails')">
									<option value="" selected disabled>اختر الغرض</option>
									<option v-for="(x,id) in statues" :value="id" :data-id="id">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_request_for">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="request_price" class="">السعر</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_price" name="request_price_from" class="form-control" placeholder="السعر من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_price" name="request_price_to" class="form-control" placeholder="السعر الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_request_price">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="request_currency" class="">العملة</label>
								<select name="request_currency" id="request_currency" class="form-control" required>
									<option v-for="(x,id) in currency" v-bind:value="x">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_request_currency">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="request_interface" class="">واجهة</label>
								<select name="request_interface" id="request_interface" class="form-control">
									<option value="" selected disabled>اختر الواجهة</option>
									<option v-for="(x,id) in interf" v-bind:value="id">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_request_interface">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 request_farm_area">
								<label for="request_rooms" class="">عدد الآبار: <span id="request_well_range_val"></span></label>
								<input type="range" min="0" max="10" data-view="request_well_range_val" value="0" id="request_well" name="request_well" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_request_well">this field required</div>
							</div>
							<div class="col-sm mb-3 request_farm_area">
								<label for="request_hall" class="">مشب: <span id="request_mushub_range_val"></span></label>
								<input type="range" min="0" max="10" data-view="request_mushub_range_val" value="0" id="request_mushub" name="request_mushub" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_request_mushub">this field required</div>
							</div>
							<div class="col-sm mb-3 request_farm_area">
								<label for="request_bath" class="">عدد النخيل</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_tree" name="request_tree_from" class="form-control" placeholder="عدد النخيل من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_tree" name="request_tree_to" class="form-control" placeholder="عدد النخيل الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_request_tree">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 request_home_area">
								<label for="request_hall" class="">الصالات: <span id="request_hall_range_val"></span></label>
								<input type="range" min="0" max="80" data-view="request_hall_range_val" value="0" id="request_hall" name="request_hall" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_request_hall">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_rooms" class="">عدد الغرف:</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_rooms" name="request_rooms_from" class="form-control" placeholder="عدد الغرف من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_rooms" name="request_rooms_to" class="form-control" placeholder="عدد الغرف الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_request_rooms">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_bath" class="">عدد الحمامات: </label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_bath" name="request_bath_from" class="form-control" placeholder="عدد الحمامات من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="request_bath" name="request_bath_to" class="form-control" placeholder="عدد الحمامات إلى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_request_bath">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 request_home_area">
								<label for="request_floor" class="">الطابق: <span id="request_floor_range_val"></span></label>
								<input type="range" min="0" max="50" data-view="request_floor_range_val" value="0" id="request_floor" name="request_floor" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_request_floor">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="request_road" class="">عرض الشارع: <span id="request_road_range_val"></span></label>
								<input type="range" min="2" max="150" step="2" data-view="request_road_range_val" name="request_road" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_request_road">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_unit_nun" class="">عدد الوحدات: <span id="request_unit_num_val">1</span></label>
								<input type="range" min="1" max="100" step="1" data-view="request_unit_num_val" name="request_unit_nun" class="form-control-range range_input" value="1" />
								<div class="d-none err_notification" id="valid_request_unit_num">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 request_home_area">
								<label for="request_car">
									<input type="checkbox" id="request_car" name="request_car" value="1">
									مواقف سيارات
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_car">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_duplex">
									<input type="checkbox" id="request_duplex" name="request_duplex" value="1">
									دوبليكس
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_duplex">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_corner">
									<input type="checkbox" id="request_corner" name="request_corner" value="1">
									زاوية
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_corner">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_append">
									<input type="checkbox" id="request_append" name="request_append" value="1">
									ملحق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_append">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="new_basment">
									<input type="checkbox" id="request_basment" name="request_basment" value="1">
									بدروم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_basment">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_monsters">
									<input type="checkbox" id="request_monsters" name="request_monsters" value="1">
									الحوش
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_monsters">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 request_home_area">
								<label for="request_swim">
									<input type="checkbox" id="request_swim" name="request_swim" value="1">
									المسبح
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_swim">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="new_kitchen">
									<input type="checkbox" id="request_kitchen" name="request_kitchen" value="1">
									المطبخ
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_kitchen">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_elevator">
									<input type="checkbox" id="request_elevator" name="request_elevator" value="1">
									المصعد
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_elevator">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_ser_room">
									<input type="checkbox" id="request_ser_room" name="request_ser_room" value="1">
									غرفة الخادم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_ser_room">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="new_dr_room">
									<input type="checkbox" id="request_dr_room" name="request_dr_room" value="1">
									غرفة السائق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_dr_room">this field required</div>
							</div>
							<div class="col-sm mb-3 request_home_area">
								<label for="request_air_cond">
									<input type="checkbox" id="request_air_cond" name="request_air_cond" value="1">
									التكييف
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_request_air_cond">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تم حفظ الطلب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ الطلب</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For update request -->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_request">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="upd_request_form" method="post" action="<?php echo URL ?>my_land/upd_request" data-model="upd_request" data-type="upd_request">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_request_title"><i class="fa fa-plus"></i> تعديل طلب</h5>
						<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_land.ID" />
						<div class="floating-panel">
							<input class="delete-circle" type="button" value="مسح دائرة" />
							<input class="delete-rectangle" type="button" value="مسح مستطيل">
						</div>
						<div id="upd_request_map_area" class="map_area"></div>
						<input type="hidden" id="upd_request_map_data" name="upd_request_map_data" :value="upd_land.LOCATION" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_request_desc" class="">وصف العقار</label>
								<textarea type="text" id="upd_request_desc" name="upd_request_desc" class="form-control" placeholder="وصف العقار" required>{{upd_land.DESC}}</textarea>
								<div class="d-none err_notification" id="valid_upd_request_desc">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_request_space" class="">المساحة</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_space_from" name="upd_request_space_from" class="form-control" :value="upd_land.SIZE" placeholder="المساحة من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_space_to" name="upd_request_space_to" class="form-control" :value="upd_land.SIZE_M" placeholder="المساحة الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_upd_request_space">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_request_type" class="">النوع</label>
								<select name="upd_request_type" id="upd_request_type" class="form-control" required @change="onChangeType($event,'upd_request_home_area','upd_farm_area')">
									<option value="" selected disabled>اختر النوع</option>
									<option v-for="(x,id) in types" :value="x.ID" :data-id="id" :selected="x.ID == upd_land.TYPE">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_request_for" class="">الغرض</label>
								<select name="upd_request_for" id="upd_request_for" class="form-control" required @change="onChangeFor($event,'sails')">
									<option value="" selected disabled>اختر الغرض</option>
									<option v-for="(x,id) in statues" :value="id" :data-id="id" :selected="id == upd_land.FOR">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_request_for">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_request_price" class="">السعر</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_price" name="upd_request_price_from" :value="upd_land.M_PRICE" class="form-control" placeholder="السعر من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_price" name="upd_request_price_to" :value="upd_land.M_PRICE_M" class="form-control" placeholder="السعر الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_upd_request_price">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_request_currency" class="">العملة</label>
								<select name="upd_request_currency" id="upd_request_currency" class="form-control" required>
									<option v-for="(x,id) in currency" v-bind:value="x" :selected="id == upd_land.CURRENCY">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_request_currency">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_request_interface" class="">واجهة</label>
								<select name="upd_request_interface" id="upd_request_interface" class="form-control">
									<option value="" selected disabled>اختر الواجهة</option>
									<option v-for="(x,id) in interf" v-bind:value="id" :selected="id == upd_land.INTERFACE">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_request_interface">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_farm_area">
								<label for="upd_request_rooms" class="">عدد الابار: <span id="upd_request_well_range_val">{{upd_land.WELL}}</span></label>
								<input type="range" min="0" max="10" data-view="upd_request_well_range_val" :value="(upd_land.WELL == null)?0:upd_land.ROOMS" id="upd_request_well" name="upd_request_well" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_request_well">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_farm_area">
								<label for="upd_request_hall" class="">مشب: <span id="upd_request_mushub_range_val">{{upd_land.MUSHUB}}</span></label>
								<input type="range" min="0" max="10" data-view="upd_request_mushub_range_val" :value="(upd_land.MUSHUB == null)?0:upd_land.MUSHUB" id="upd_request_mushub" name="upd_request_mushub" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_request_mushub">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_farm_area">
								<label for="upd_request_bath" class="">عدد النخيل</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_tree" name="upd_request_tree_from" :value="upd_land.TREES" class="form-control" placeholder="عدد النخيل من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_tree" name="upd_request_tree_to" :value="upd_land.TREES_M" class="form-control" placeholder="عدد النخيل الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_upd_request_tree">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_request_home_area">
								<label for="upd_request_hall" class="">الصالات: <span id="upd_request_hall_range_val">{{upd_land.HALLS}}</span></label>
								<input type="range" min="0" max="80" data-view="upd_request_hall_range_val" :value="(upd_land.HALLS == null)?0:upd_land.HALLS" id="upd_request_hall" name="upd_request_hall" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_request_hall">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_request_home_area">
								<label for="upd_request_rooms" class="">عدد الغرف:</label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_rooms" name="upd_request_rooms_from" :value="upd_land.ROOMS" class="form-control" placeholder="عدد الغرف من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_rooms" name="upd_request_rooms_to" :value="upd_land.ROOMS_M" class="form-control" placeholder="عدد الغرف الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_upd_request_rooms">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_request_home_area">
								<label for="upd_request_bath" class="">عدد الحمامات: </label>
								<div class="row">
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_bath" name="upd_request_bath_from" :value="upd_land.BATHS" class="form-control" placeholder="عدد الحمامات من" />
									</div>
									<div class="col-sm mb-3">
										<input type="number" lang="en" id="upd_request_bath" name="upd_request_bath_to" :value="upd_land.BATHS_M" class="form-control" placeholder="عدد الحمامات الى" />
									</div>
								</div>
								<div class="d-none err_notification" id="valid_upd_request_bath">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_request_home_area">
								<label for="upd_request_floor" class="">الطابق: <span id="upd_request_floor_range_val">{{upd_land.FLOOR }}</span></label>
								<input type="range" min="0" max="50" data-view="upd_request_floor_range_val" :value="(upd_land.FLOOR == null)?0:upd_land.FLOOR" id="upd_request_floor" name="upd_request_floor" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_request_floor">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_request_road" class="">عرض الشارع: <span id="upd_request_road_range_val">{{upd_land.ROAD}}</span></label>
								<input type="range" min="2" max="150" step="2" :value="(upd_land.ROAD == null)?0:upd_land.ROAD" data-view="upd_request_road_range_val" name="upd_request_road" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_request_road">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_request_home_area">
								<label for="upd_request_unit_nun" class="">عدد الوحدات: <span id="upd_request_unit_num_val">{{upd_land.UNIT_NUM}}</span></label>
								<input type="range" min="1" max="50" step="1" data-view="upd_request_unit_num_val" name="upd_request_unit_nun" :value="upd_land.UNIT_NUM" class="form-control-range range_input" value="1" />
								<div class="d-none err_notification" id="valid_upd_request_unit_num">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_car">
									<input type="checkbox" id="upd_request_car" name="upd_request_car" :checked="upd_land.CARS == 1" value="1">
									مواقف سيارات
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_car">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_duplex">
									<input type="checkbox" id="upd_request_duplex" name="upd_request_duplex" :checked="upd_land.DUPLEX == 1" value="1">
									دوبليكس
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_duplex">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_corner">
									<input type="checkbox" id="upd_request_corner" name="upd_request_corner" :checked="1 == upd_land.CORNER" value="1">
									زاوية
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_corner">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_append">
									<input type="checkbox" id="upd_request_append" name="upd_request_append" :checked="upd_land.APPEND == 1" value="1">
									ملحق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_append">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="new_basment">
									<input type="checkbox" id="upd_request_basment" name="upd_request_basment" :checked="upd_land.BASEMENT == 1" value="1">
									بدروم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_basment">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_monsters">
									<input type="checkbox" id="upd_request_monsters" name="upd_request_monsters" :checked="upd_land.MONSTER == 1" value="1">
									الحوش
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_monsters">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_swim">
									<input type="checkbox" id="upd_request_swim" name="upd_request_swim" :checked="upd_land.SWIM == 1" value="1">
									المسبح
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_swim">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="new_kitchen">
									<input type="checkbox" id="upd_request_kitchen" name="upd_request_kitchen" :checked="upd_land.KITCHEN == 1" value="1">
									المطبخ
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_kitchen">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_elevator">
									<input type="checkbox" id="upd_request_elevator" name="upd_request_elevator" :checked="upd_land.ELEVATOR == 1" value="1">
									المصعد
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_elevator">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_ser_room">
									<input type="checkbox" id="upd_request_ser_room" name="upd_request_ser_room" :checked="upd_land.SER_ROOM == 1" value="1">
									غرفة الخادم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_ser_room">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="new_dr_room">
									<input type="checkbox" id="upd_request_dr_room" name="upd_request_dr_room" :checked="upd_land.DR_ROOM == 1" value="1">
									غرفة السائق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_dr_room">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_request_air_cond">
									<input type="checkbox" id="upd_request_air_cond" name="upd_request_air_cond" :checked="upd_land.AIR_COND == 1" value="1">
									التكييف
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_request_air_cond">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تم حفظ الطلب</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ الطلب</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For add new land API -->
	<div class="modal bd-example-modal-lg" id="new_land_api">
		<div class="modal-dialog modal-lg">
			<form class="row g-3" id="new_api_form" v-on:submit.prevent="get_api_land()" method="post" action="<?php echo URL ?>my_land/new_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_land_api_title"><i class="fa fa-plus"></i> إضافة عقار</h5>
						<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="hid_info" name="get_info" value="API" />
						<input type="hidden" class="hid_info" name="new_land_type" value="API" />
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_id_type" class="">نوع الهوية</label>
								<select name="new_id_type" class="form-control border border-primary rounded">
									<option value="1" :selected="config.ID_TYPE == 1">هوية وطنية</option>
									<option value="2" :selected="config.ID_TYPE == 2">منشأه</option>
								</select>
								<div class="d-none err_notification" id="valid_new_id_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_id" class="">رقم الهوية </label>
								<input type="text" name="new_id" :value="config.ID_NO" class="form-control" placeholder="رقم الهوية" required />
								<div class="d-none err_notification" id="valid_new_id">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_type" class="">رقم ترخيص الاعلان</label>
								<input type="text" id="new_delegate" name="new_delegate" class="form-control" placeholder="رقم التفويض" required />
								<div class="d-none err_notification" id="valid_new_delegate">this field required</div>
							</div>
						</div>
						<div class="d-flex justify-content-center">
							<div id="kk_loader" class="spinner-border text-success d-none" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>
						<div class="form_msg d-none">تم حفظ العقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> بحث </button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For add new land -->
	<div class="modal bd-example-modal-lg modal_with_form" id="new_land">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="new_staff_form" method="post" action="<?php echo URL ?>my_land/new_land" data-model="new_land" data-type="new_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_land_title"><i class="fa fa-plus"></i> إضافة عقار</h5>
						<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<!--
					    $land['l_adv']          = "ADV";
			$land['l_neighborhood'] = $result['location']['districtCode'];
			
			$land['l_street']       = $result['location']['street'];
			$land['l_unit_no']      = $result['location']['additionalNumber'];
			$land['l_no']           = $result['location']['buildingNumber'];
			$land['l_block']        = $result['planNumber'];
			$land['l_street_width'] = $result['streetWidth'];
			
			$land['l_mortgage'] 	= (!empty($result['isPawned']) && $result['isPawned'] != 'false' && $result['isPawned'] != false )?$result['isPawned']:null;
			$land['l_law'] 	        = (!empty($result['isConstrained']) && $result['isConstrained'] != 'false' && $result['isConstrained'] != false )?$result['isConstrained']:null;
			$land['l_disputes'] 	= (!empty($result['obligationsOnTheProperty']) && $result['obligationsOnTheProperty'] != 'false' && $result['obligationsOnTheProperty'] != false )?$result['obligationsOnTheProperty']:null;
			
			$land['l_size']         = $result['propertyArea'];
			$land['l_price']        = $result['propertyPrice'];
			$land['l_m_price']      = $result['propertyPrice'];
			$land['l_currency']     = "SAR";
			$land['l_rooms']        = $result['numberOfRooms'];
			
			$land['l_type']         = $type;
			$land['l_co_relation'] 	= $fdata['new_relation'];
			$land['l_co'] 			= session::get('company');
			$land['l_adv_no']    	= $fdata['new_delegate'];
			$land['l_desc']    	    = $fdata['new_desc'];
			
			$land['l_for']          = ($result['advertisementType'] == "بيغ")?"SALE":"RENT_Y";
			$land['l_interface']    = array_search($result['propertyFace'],lib::$land_interface);
			$land['l_expered']      = $exp;
					    -->
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" name="new_relation_type" value='REG' />
						<input type="hidden" name="new_delegate" :value='api_land.adLicenseNumber' />
						<div id="map_area" class="map_area"></div>
						<input type="hidden" id="new_map_data" name="new_map_data" value='' />
						<input type="hidden" lang="en" step="any" id="new_lng" name="new_lng" :value="api_land.location.longitude" />
						<input type="hidden" lang="en" id="new_lat" step="any" name="new_lat" :value="api_land.location.latitude" />
						<input type="hidden" lang="en" id="new_location" name="new_location" :value="api_land.LOCATION_A" />
						<div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="new_block" class="">رقم هوية المعلن: </label>
								{{api_land.advertiserId}}
							</div>
							<div class="col-sm mb-3">
								<label for="new_no" class="">رقم ترخيص الاعلان</label>
								{{api_land.adLicenseNumber}}
							</div>
							<div class="col-sm mb-3">
								<label for="new_space" class="">رقم صك الملكية</label>
								{{api_land.deedNumber}}
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="new_space" class="">أسم المعلن</label>
								{{api_land.advertiserName}}
							</div>
							<div class="col-sm mb-3">
								<label for="new_space" class="">رقم الهاتف </label>
								{{api_land.phoneNumber}}
							</div>
							<div class="col-sm mb-3">
								<label for="new_space" class="">رقم رخصة الوساطة والتسويق العقاري</label>
								{{api_land.brokerageAndMarketingLicenseNumber}}
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="isConstrained">وجود قيد؟</label>
								<input type="checkbox" id="isConstrained" name="isConstrained" :value="api_land.isConstrained">
							</div>
							<div class="col-sm mb-3">
								<label for="isPawned">وجود رهن؟</label>
								<input type="checkbox" id="isPawned" name="isPawned" :value="api_land.isPawned">
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-sm mb-3">
								<label for="new_type" class="">العلاقة بالعقار</label>
								<select name="new_relation" id="new_relation" class="form-control border border-primary rounded" @change="onChangeRelation($event)" >
									<option v-for="(x,id) in relation" v-bind:value="id" v-bind:data-dele="x.del">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_relation">this field required</div>
							</div>
							<div class="col-sm mb-3" v-else-if="local_delegate == 'LOCAL'">
								<label for="new_delegate_file" class="">ملف التفويض</label>
								<input type="file" name="new_delegate_file" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload form-control-file form-control-sm" id="new_delegate_file" accept="*" />
								<div class="d-none err_notification" id="valid_new_delegate">this field required</div>
							</div>
						</div> -->
						<!-- <div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="new_block" class="">المربع</label>
								<input type="number" lang="en" name="new_block" id="new_block" :value="api_land.planNumber" class="form-control border border-primary rounded" placeholder="المربع" required />
								<div class="d-none err_notification" id="valid_new_block">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_no" class="">رقم العقار</label>
								<input type="number" lang="en" id="new_no" name="new_no" :value="api_land.location.buildingNumber" class="form-control border border-primary rounded" placeholder="رقم العقار" required />
								<div class="d-none err_notification" id="valid_new_no">this field required</div>
								<div class="err_notification " id="duplicate_new_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_space" class="">المساحة</label>
								<input type="number" lang="en" step="any" id="new_space" :value="api_land.propertyArea" name="new_space" class="form-control border border-primary rounded" placeholder="المساحة" required />
								<div class="d-none err_notification" id="valid_new_space">this field required</div>
							</div>
						</div> -->
						<div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="streetWidth" class="">عرض الشارع</label>
								<input type="number" lang="en" id="streetWidth" :value="api_land.streetWidth" class="form-control border border-primary rounded" placeholder="عرض الشارع" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="propertyArea" class="">المساحة</label>
								<input type="number" lang="en" step="any" id="propertyArea" :value="api_land.propertyArea" class="form-control border border-primary rounded" placeholder="المساحة" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="propertyPrice" class="">سعر العقار</label>
								<input type="number" lang="en" step="any" id="propertyPrice" :value="api_land.propertyPrice" class="form-control border border-primary rounded" placeholder="سعر العقار" readonly />
							</div>
						</div>
						<div class="row mt-3">
							<div class="col-sm mb-3">
								<label for="numberOfRooms" class="">عدد الغرف</label>
								<input type="number" lang="en" id="numberOfRooms" :value="api_land.numberOfRooms" class="form-control border border-primary rounded" placeholder="عدد الغرف" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="propertyType" class="">نوع العقار</label>
								<input type="text" lang="en" id="propertyType" :value="api_land.propertyType" class="form-control border border-primary rounded" placeholder="نوع العقار" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="propertyAge" class="">عمر العقار</label>
								<input type="text" lang="en" id="propertyAge" :value="api_land.propertyAge" class="form-control border border-primary rounded" placeholder="عمر العقار" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="advertisementType" class="">نوع الإعلان</label>
								<input type="text" lang="en" id="advertisementType" :value="api_land.advertisementType" class="form-control border border-primary rounded" placeholder="نوع الإعلان" readonly />
							</div>
						</div>
						<!-- Label for the location section -->
						<div class="row mt-3">
							<div class="col-sm">
								<label class="font-weight-bold">المنطقة</label>
							</div>
						</div>

						<!-- Rows for location details -->
						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="region" class="">المنطقة</label>
								<input type="text" lang="en" id="region" :value="api_land.location.region" class="form-control border border-primary rounded" placeholder="المنطقة" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="regionCode" class="">كود المنطقة</label>
								<input type="text" lang="en" id="regionCode" :value="api_land.location.regionCode" class="form-control border border-primary rounded" placeholder="كود المنطقة" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="city" class="">المدينة</label>
								<input type="text" lang="en" id="city" :value="api_land.location.city" class="form-control border border-primary rounded" placeholder="المدينة" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="cityCode" class="">كود المدينة</label>
								<input type="text" lang="en" id="cityCode" :value="api_land.location.cityCode" class="form-control border border-primary rounded" placeholder="كود المدينة" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="district" class="">الحي</label>
								<input type="text" lang="en" id="district" :value="api_land.location.district" class="form-control border border-primary rounded" placeholder="الحي" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="districtCode" class="">كود الحي</label>
								<input type="text" lang="en" id="districtCode" :value="api_land.location.districtCode" class="form-control border border-primary rounded" placeholder="كود الحي" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="street" class="">الشارع</label>
								<input type="text" lang="en" id="street" :value="api_land.location.street" class="form-control border border-primary rounded" placeholder="الشارع" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="postalCode" class="">الرمز البريدي</label>
								<input type="text" lang="en" id="postalCode" :value="api_land.location.postalCode" class="form-control border border-primary rounded" placeholder="الرمز البريدي" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="buildingNumber" class="">رقم المبنى</label>
								<input type="text" lang="en" id="buildingNumber" :value="api_land.location.buildingNumber" class="form-control border border-primary rounded" placeholder="رقم المبنى" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="additionalNumber" class="">الرقم الإضافي</label>
								<input type="text" lang="en" id="additionalNumber" :value="api_land.location.additionalNumber" class="form-control border border-primary rounded" placeholder="الرقم الإضافي" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="longitude" class="">خط الطول</label>
								<input type="text" lang="en" id="longitude" :value="api_land.location.longitude" class="form-control border border-primary rounded" placeholder="خط الطول" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="latitude" class="">خط العرض</label>
								<input type="text" lang="en" id="latitude" :value="api_land.location.latitude" class="form-control border border-primary rounded" placeholder="خط العرض" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="propertyFace" class="">واجهة العقار</label>
								<input type="text" lang="en" id="propertyFace" :value="api_land.propertyFace" class="form-control border border-primary rounded" placeholder="واجهة العقار" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="planNumber" class="">رقم المخطط</label>
								<input type="text" lang="en" id="planNumber" :value="api_land.planNumber" class="form-control border border-primary rounded" placeholder="رقم المخطط" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="obligationsOnTheProperty" class="">الالتزامات على العقار</label>
								<input type="text" lang="en" id="obligationsOnTheProperty" :value="api_land.obligationsOnTheProperty" class="form-control border border-primary rounded" placeholder="الالتزامات على العقار" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="guaranteesAndTheirDuration" class="">الضمانات ومدتها</label>
								<input type="text" lang="en" id="guaranteesAndTheirDuration" :value="api_land.guaranteesAndTheirDuration" class="form-control border border-primary rounded" placeholder="الضمانات ومدتها" readonly />
							</div>
						</div>

						<div class="row mt-2">
							<div class="col-sm mb-3">
								<label for="theBordersAndLengthsOfTheProperty" class="">الحدود والأطوال للعقار</label>
								<input type="text" lang="en" id="theBordersAndLengthsOfTheProperty" :value="api_land.theBordersAndLengthsOfTheProperty" class="form-control border border-primary rounded" placeholder="الحدود والأطوال للعقار" readonly />
							</div>
							<div class="col-sm mb-3">
								<label for="complianceWithTheSaudiBuildingCode" class="">مطابقة كود البناء السعودي</label>
								<input type="text" lang="en" id="complianceWithTheSaudiBuildingCode" :value="api_land.complianceWithTheSaudiBuildingCode" class="form-control border border-primary rounded" placeholder="مطابقة كود البناء السعودي" readonly />
							</div>
						</div>


						<div class="row align-items-center">
							<div class="col-sm mb-3">
								<img id="new_land_img" src="<?php echo URL; ?>public/IMG/land/default.png" width="150px" height="150px" class="img-thumbnail mb-1" alt="الصورة">
							</div>
							<div class="col-sm mb-3">
								<input type="file" name="new_land_img" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload image_upload form-control-file form-control-sm" data-id="new_land_img" id="img" accept="image/*" />
								<div class="d-none err_notification" id="valid_new_land_img">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_desc" class="">وصف العقار</label>
								<textarea type="text" id="new_desc" name="new_desc" class="form-control border border-primary rounded" placeholder="وصف العقار" required></textarea>
								<div class="d-none err_notification" id="valid_new_desc">this field required</div>
							</div>
						</div>
						<div class="row">
							<input type="hidden" id="new_adv" name="new_adv" value="" />
							<div class="col-sm mb-3">
								<label for="new_type" class="">النوع</label>
								<select name="new_type" id="new_type" class="form-control border border-primary rounded" required @change="onChangeType($event,'new_home_area','new_farm_area')">
									<option value="" selected disabled>اختر النوع</option>
									<option v-for="(x,id) in types" :value="x.ID" :selected="x.NAME == api_land.propertyType" :data-id="id">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_for" class="">الغرض </label>{{api_land.advertisementType}}
								<select name="new_for" id="new_for" class="form-control border border-primary rounded" required @change="onChangeFor($event,'sails')">
									<option value="" selected disabled>اختر الغرض</option>
									<option v-for="(x,id) in statues" :value="id" :data-id="id">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_for">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_price" class="">السعر</label>
								<input type="number" lang="en" id="new_price" name="new_price" :value="api_land.propertyPrice" class="form-control border border-primary rounded" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_new_price">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_currency" class="">العملة</label>
								<select name="new_currency" id="new_currency" class="form-control border border-primary rounded" required>
									<option v-for="(x,id) in currency" :selected="x == 'SAR'" v-bind:value="x">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_currency">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_interface" class="">واجهة</label>
								<select name="new_interface" id="new_interface" class="form-control border border-primary rounded">
									<option value="" selected disabled>اختر الواجهة</option>
									<option v-for="(x,id) in interf" selected="x == api_land.propertyFace" v-bind:value="id">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_interface">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_unit_no" class="">رقم الوحدة</label>
								<input type="number" lang="en" id="new_unit_no" :value="api_land.propertyPrice" name="new_unit_no" class="form-control border border-primary rounded" placeholder="الوحدة" />
								<div class="d-none err_notification" id="valid_new_unit_no">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_year" class="">سنة التشييد</label> {{api_land.propertyAge}}
								<input type="date" data-view="new_year_range_val" id="new_year" name="new_year" class="form-control border border-primary rounded" />
								<div class="d-none err_notification" id="valid_new_year">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_farm_area">
								<label for="new_bath" class="">عدد النخيل</label>
								<input type="number" lang="en" id="new_tree" name="new_tree" class="form-control border border-primary rounded" placeholder="عدد النخيل" />
								<div class="d-none err_notification" id="valid_new_tree">this field required</div>
							</div>
							<div class="col-sm mb-3 new_farm_area">
								<label for="new_rooms" class="">عدد الابار: <span id="new_well_range_val"></span></label>
								<input type="range" min="0" max="10" data-view="new_well_range_val" value="0" id="new_well" name="new_well" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_well">this field required</div>
							</div>
							<div class="col-sm mb-3 new_farm_area">
								<label for="new_hall" class="">مشب: <span id="new_mushub_range_val"></span></label>
								<input type="range" min="0" max="10" data-view="new_mushub_range_val" value="0" id="new_mushub" name="new_mushub" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_mushub">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_rooms" class="">عدد الغرف:
									<span id="new_r_range_val">{{api_land.numberOfRooms}}</span></label>
								<input type="range" min="0" max="80" data-view="new_r_range_val" :value="api_land.numberOfRooms" id="new_rooms" name="new_rooms" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_bath" class="">عدد الحمامات: <span id="new_bath_range_val"></span></label>
								<input type="range" min="0" max="80" data-view="new_bath_range_val" value="0" id="new_bath" name="new_bath" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_bath">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_hall" class="">الصالات: <span id="new_hall_range_val"></span></label>
								<input type="range" min="0" max="80" data-view="new_hall_range_val" value="0" id="new_hall" name="new_hall" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_hall">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_floor" class="">الطابق: <span id="new_floor_range_val"></span></label>
								<input type="range" min="0" max="80" data-view="new_floor_range_val" value="0" id="new_floor" name="new_floor" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_floor">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_road" class="">عرض الشارع: <span id="new_road_range_val"></span></label>
								<input type="range" min="2" max="150" step="2" :value="api_land.streetWidth" data-view="new_road_range_val" name="new_road" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_road">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_unit_nun" class="">عدد الوحدات: <span id="new_unit_num_val">1</span></label>
								<input type="range" min="1" max="80" step="1" data-view="new_unit_num_val" name="new_unit_nun" class="form-control-range range_input" value="1" />
								<div class="d-none err_notification" id="valid_new_unit_num">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_car">
									<input type="checkbox" id="new_car" name="new_car" value="1">
									مواقف سيارات
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_car">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_duplex">
									<input type="checkbox" id="new_duplex" name="new_duplex" value="1">
									دوبليكس
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_duplex">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_corner">
									<input type="checkbox" id="new_corner" name="new_corner" value="1">
									زاوية
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_corner">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_append">
									<input type="checkbox" id="new_append" name="new_append" value="1">
									ملحق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_append">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_basment">
									<input type="checkbox" id="new_basment" name="new_basment" value="1">
									بدروم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_basment">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_monsters">
									<input type="checkbox" id="new_monsters" name="new_monsters" value="1">
									الحوش
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_monsters">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_swim">
									<input type="checkbox" id="new_swim" name="new_swim" value="1">
									المسبح
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_swim">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_kitchen">
									<input type="checkbox" id="new_kitchen" name="new_kitchen" value="1">
									المطبخ
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_kitchen">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_elevator">
									<input type="checkbox" id="new_elevator" name="new_elevator" value="1">
									المصعد
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_elevator">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_ser_room">
									<input type="checkbox" id="new_ser_room" name="new_ser_room" value="1">
									غرفة الخادم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_ser_room">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_dr_room">
									<input type="checkbox" id="new_dr_room" name="new_dr_room" value="1">
									غرفة السائق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_dr_room">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_air_cond">
									<input type="checkbox" id="new_air_cond" name="new_air_cond" value="1">
									التكييف
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_new_air_cond">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_mortage" class="">هل يوجد الرهن أو القيد الذي يمنع او يحد من التصرف او الانتفاع من العقار؟</label>
								<textarea type="text" id="new_mortage" name="new_mortage" :value="api_land.obligationsOnTheProperty" class="form-control border border-primary rounded" placeholder="لا يوجد">{{api_land.obligationsOnTheProperty}}</textarea>
								<div class="d-none err_notification" id="valid_new_mortage">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_law" class="">الحقوق والالتزامات على العقار الغير موثقة في وثيقة العقار</label>
								<textarea type="text" id="new_law" name="new_law" class="form-control border border-primary rounded" placeholder="لا يوجد">لا يوجد</textarea>
								<div class="d-none err_notification" id="valid_new_law">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_info" class="">المعلومات التي قد تؤثر على العقار سواء في خفض قيمته او التأثير على قرار المستهدف بالإعلان</label>
								<textarea type="text" id="new_info" name="new_info" class="form-control border border-primary rounded" placeholder="لا يوجد">لا يوجد</textarea>
								<div class="d-none err_notification" id="valid_new_info">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_dispute" class=""> النزاعات القائمة على العقار</label>
								<textarea type="text" id="new_dispute" name="new_dispute" class="form-control border border-primary rounded" placeholder="لا يوجد">لا يوجد</textarea>
								<div class="d-none err_notification" id="valid_new_dispute">this field required</div>
							</div>
						</div>
						<div class="row ">
							<div class="col-sm mb-3">
								حدود واطوال العقار
							</div>
							{{api_land.theBordersAndLengthsOfTheProperty}}
						</div>
						<div class="row">
							<div class="col col-sm-3 mb-3">
								<label for="new_des_n" class="">الطول من الشمال</label>
								<input type="number" lang="en" id="new_des_n" name="new_des_n" class="form-control border border-primary rounded" placeholder="الطول من الشمال" step="any" />
								<div class="d-none err_notification" id="valid_new_des_n">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="new_des_s" class="">الطول من الجنوب</label>
								<input type="number" lang="en" id="new_des_s" name="new_des_s" class="form-control border border-primary rounded" placeholder="الطول من الجنوب" step="any" />
								<div class="d-none err_notification" id="valid_new_des_s">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="new_des_e" class="">الطول من الشرق</label>
								<input type="number" lang="en" id="new_des_e" name="new_des_e" class="form-control border border-primary rounded" placeholder="الطول من الشرق" step="any" />
								<div class="d-none err_notification" id="valid_new_des_e">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="new_des_w" class="">الطول من الغرب</label>
								<input type="number" lang="en" id="new_des_w" name="new_des_w" class="form-control border border-primary rounded" placeholder="الطول من الغرب" step="any" />
								<div class="d-none err_notification" id="valid_new_des_w">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col col-sm-3 mb-3">
								<label for="file" class="label-control">المرفقات</label>
								<input type="file" name="new_file_image[]" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload multi_image_upload form-control-file" data-id="new_images_area" multiple />
								<div class="d-none err_notification" id="valid_new_file_image">this field required</div>
							</div>
						</div>
						<div class="row clear_form_area" id="new_images_area"></div>
						<div class="row">
							<div class="col-sm  mb-3">
								<label class="label-control"></label>
								<label class="label-control">قم بإختيار الجهات التي تريد ان يكون الاعلان محدد لها او لا تختار اي جهة ليبقى اعلان عام</label>

							</div>
						</div>
						<div class="row">
							<table class="table table-bordered table-striped table-head-fixed text-right">
								<thead class="text-light" style="background-color: rgb(220, 174, 95);">
									<tr align="center">
										<th><input type="checkbox" id='msgs' /></th>
										<th>الصورة</th>
										<th>الاسم</th>
										<!--th>الهاتف</th>
										<th>البريد الإلكتروني</th-->
									</tr>
								</thead>
								<tbody>
									<tr align="center" v-for="(x ,index) in company">
										<td><input type="checkbox" name="company[]" class="msgs" :value="x.ID" /></td>
										<td><img v-bind:src="x.IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" /></td>
										<td>{{x.NAME}}</td>
										<!--td>{{x.PHONE}}</td>
										<td>{{x.EMAIL}}</td-->
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form_msg d-none">تم حفظ العقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ العقار</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For update land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_land">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL ?>my_land/upd_land" data-model="upd_land" data-type="upd_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> تعديل عقار</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_land.ID" />
						<div v-if="upd_land.ADV_NO.length == 0" id="upd_map_area" class="map_area"></div>
						<input type="hidden" lang="en" step="any" id="upd_lng" name="upd_lng" :value="upd_land.LOC_LNG" />
						<input type="hidden" lang="en" step="any" id="upd_lat" name="upd_lat" :value="upd_land.LOC_LAT" />
						<input type="hidden" lang="en" id="upd_location" name="upd_location" :value="upd_land.LOCATION" />
						<div class="row">
							<div class="col-sm mb-3">
								<img id="upd_land_img" :src="upd_land.IMG" width="150px" height="150px" class="img-thumbnail mb-1" alt="الصورة">
							</div>
							<div class="col-sm mb-3">
								<input type="file" name="upd_land_img" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload image_upload form-control-file form-control-sm" data-id="upd_land_img" id="img" accept="image/*" />
								<div class="d-none err_notification" id="valid_new_land_img">this field required</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_space" class="">المساحة</label>
								<input type="number" :readonly="upd_land.ADV_NO.length != 0" lang="en" name="upd_space" class="form-control" :value="upd_land.M_SIZE" placeholder="المساحة" required />
								<div class="d-none err_notification" id="valid_upd_space">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_block" class="">المربع</label>
								<input type="number" name="upd_block" class="form-control" placeholder="المربع" required :value="upd_land.BLOCK" />
								<div class="d-none err_notification" id="valid_upd_block">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_no" class="">رقم العقار</label>
								<input type="number" :readonly="upd_land.ADV_NO.length != 0" lang="en" name="upd_no" class="form-control" :value="upd_land.NO" placeholder="رقم العقار" required />
								<div class="d-none err_notification" id="valid_upd_no">this field required</div>
								<div class="err_notification " id="duplicate_upd_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_relation" class="">العلاقة بالعقار</label>
								<select name="upd_relation" :readonly="upd_land.ADV_NO.length != 0" id="upd_relation" class="form-control" @change="onChangeRelation($event)">
									<option v-for="(x,id) in relation" v-bind:value="id" v-bind:data-dele="x.del" :selected="id == upd_land.RELATION">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_relation">this field required</div>
							</div>
							<div class="col-sm mb-3" v-if="delegate">
								<label for="upd_relation_type" class="">نوع التفويض</label>
								<select name="upd_relation_type" id="upd_relation_type" class="form-control" @change="onChangeRelation_type($event)">
									<option value=""></option>
									<option value="REG">تفويض من الهيئة العامة للعقار</option>
									<option value="LOCAL">تفويض كتابي</option>
								</select>
								<div class="d-none err_notification" id="valid_new_relation_type">this field required</div>
							</div>
							<div class="col-sm mb-3" v-if="local_delegate == 'REG'">
								<label for="new_type" class="">رقم التفويض</label>
								<input type="text" id="upd_delegate" name="upd_delegate" :value="upd_land.DELEGATION" class="form-control" placeholder="رقم التفويض" required />
								<div class="d-none err_notification" id="valid_upd_delegate">this field required</div>
							</div>
							<div class="col-sm mb-3" v-else-if="local_delegate == 'LOCAL'">
								<label for="upd_delegate_file" class="">ملف التفويض <a v-if="upd_land.DELEGATE_FILE" :href="upd_land.DELEGATE_FILE" target="_blank">{{upd_land.DELEGATE_FILE_NAME}}</a></label>
								<input type="file" name="upd_delegate_file" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload form-control-file form-control-sm" id="new_delegate_file" accept="*" />
								<div class="d-none err_notification" id="valid_new_delegate">this field required</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_desc" class="">وصف العقار</label>
								<textarea name="upd_desc" class="form-control" placeholder="وصف العقار" required>{{upd_land.DESC}}</textarea>
								<div class="d-none err_notification" id="valid_upd_desc">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_adv" class="">النوع الاعلان</label>
								<select name="upd_adv" id="upd_adv" class="form-control">
									<option v-for="(x,id) in adv" v-bind:value="id" :selected="id == upd_land.ADV">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_adv">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_type" class="">النوع</label>
								<select v-if="upd_land.ADV_NO.length == 0" name="upd_type" class="form-control" required @change="onChangeType($event,'upd_home_area','upd_farm_area')">
									<option value="" selected disabled>اخترر النوع</option>
									<option v-for="(x,id) in types" v-bind:value="x.ID" :selected="x.ID == upd_land.TYPE">{{x.NAME}}</option>
								</select>
								<template v-eles="">
									<input type="hidden" name="upd_type" :value="upd_land.TYPE" />
									{{types[upd_land.TYPE].NAME}}
								</template>
								<div class="d-none err_notification" id="valid_upd_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_for" class="">الغرض</label>
								<select v-if="upd_land.ADV_NO.length == 0" name="upd_for" id="upd_for" class="form-control" required @change="onChangeFor($event,'upd_sails')">
									<option value="" selected disabled>اختر الغرض</option>
									<option v-for="(x,id) in statues" :value="id" :data-id="id" :selected="id == upd_land.FOR">{{x.NAME}}</option>
								</select>
								<template v-eles="">
									<input type="hidden" name="upd_for" :value="upd_land.FOR" />
									{{statues[upd_land.FOR].NAME}}
								</template>
								<div class="d-none err_notification" id="valid_new_for">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_price" class="">السعر</label>
								<input type="number" lang="en" name="upd_price" class="form-control" :value="upd_land.UPD_PRICE" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_currency" class="">العملة</label>
								<select name="upd_currency" class="form-control" required>
									<option value="" selected disabled>اختر العملة</option>
									<option v-for="(x,id) in currency" v-bind:value="x" :selected="id == upd_land.CURRENCY">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_currency">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_interface" class="">واجهة</label>
								<select v-if="upd_land.ADV_NO.length == 0" name="upd_interface" id="upd_interface" class="form-control">
									<option value="" selected disabled>اختر الواجهة</option>
									<option v-for="(x,id) in interf" v-bind:value="id" :selected="id == upd_land.INTERFACE">{{x}}</option>
								</select>
								<template v-eles="">
									<input type="hidden" name="upd_interface" :value="upd_land.INTERFACE" />
									{{interf[upd_land.INTERFACE]}}
								</template>
								<div class="d-none err_notification" id="valid_upd_interface">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_unit_no" class="">رقم الوحدة</label>
								<input :readonly="upd_land.ADV_NO.length != 0" type="number" lang="en" id="upd_unit_no" name="upd_unit_no" class="form-control" placeholder="الوحدة" :value="upd_land.UNIT_NO" />
								<div class="d-none err_notification" id="valid_upd_unit_no">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_year" class="">سنة التشييد</label>
								<input type="date" :value="(upd_land.BULID == null)?0:upd_land.BULID" id="upd_year" name="upd_year" class="form-control" />
								<div class="d-none err_notification" id="valid_upd_year">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_farm_area">
								<label for="upd_tree" class="">عدد النخيل</label>
								<input type="number" lang="en" id="upd_tree" name="upd_tree" :value="upd_land.TREES" class="form-control" placeholder="عدد النخيل" />
								<div class="d-none err_notification" id="valid_upd_tree">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_farm_area">
								<label for="upd_well" class="">عدد الابار: <span id="new_well_range_val">{{upd_land.WELL}}</span></label>
								<input type="range" min="0" max="10" data-view="new_well_range_val" :value="(upd_land.WELL == null)?0:upd_land.ROOMS" id="upd_well" name="upd_well" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_upd_well">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_farm_area">
								<label for="new_hall" class="">مشب: <span id="new_mushub_range_val">{{upd_land.MUSHUB}}</span></label>
								<input type="range" min="0" max="10" data-view="new_mushub_range_val" :value="(upd_land.MUSHUB == null)?0:upd_land.MUSHUB" id="new_mushub" name="new_mushub" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_mushub">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_rooms" class="">عدد الغرف: <span id="upd_r_range_val">{{upd_land.ROOMS}}</span></label>
								<input v-if="upd_land.ADV_NO.length == 0" type="range" min="0" max="80" data-view="upd_r_range_val" :value="(upd_land.ROOMS == null)?0:upd_land.ROOMS" id="upd_rooms" name="upd_rooms" class="form-control range_input" />
								<input v-eles="" type="hidden" name="upd_rooms" :value="upd_land.ROOMS" />
								<div class="d-none err_notification" id="valid_upd_rooms">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_bath" class="">عدد الحمامات: <span id="upd_bath_range_val">{{upd_land.BATHS}}</span></label>
								<input type="range" min="0" max="80" data-view="upd_bath_range_val" :value="(upd_land.BATHS == null)?0:upd_land.BATHS" id="upd_bath" name="upd_bath" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_bath">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_hall" class="">الصالات: <span id="upd_hall_range_val">{{upd_land.HALLS}}</span></label>
								<input type="range" min="0" max="80" data-view="upd_hall_range_val" :value="(upd_land.HALLS == null)?0:upd_land.HALLS" id="upd_hall" name="upd_hall" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_hall">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_unit_num" class="">عدد الوحدات: <span id="upd_unit_num_val">{{upd_land.UNIT_NUM}}</span></label>
								<input type="range" min="1" max="80" step="1" data-view="upd_unit_num_val" name="upd_unit_nun" class="form-control-range range_input" :value="upd_land.UNIT_NUM" />
								<div class="d-none err_notification" id="valid_new_unit_num">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_floor" class="">الطابق: <span id="upd_floor_range_val">{{upd_land.FLOOR}}</span></label>
								<input type="range" min="0" max="80" data-view="upd_floor_range_val" :value="(upd_land.FLOOR == null)?0:upd_land.FLOOR" value="0" id="upd_floor" name="upd_floor" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_floor">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_road" class="">عرض الشارع: <span id="upd_road_range_val">{{upd_land.ROAD}}</span></label>
								<input v-if="upd_land.ADV_NO.length == 0" type="range" min="2" max="150" step="2" :value="(upd_land.ROAD == null)?0:upd_land.ROAD" data-view="upd_road_range_val" name="upd_road" id="upd_road" class="form-control range_input" />
								<input v-eles="" type="hidden" name="upd_road" :value="upd_land.ROAD" />
								<div class="d-none err_notification" id="valid_upd_road">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_car">
									<input type="checkbox" id="upd_car" name="upd_car" :checked="upd_land.CARS == 1" value="1">
									مواقف سيارات
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_car">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_duplex">
									<input type="checkbox" id="upd_duplex" name="upd_duplex" value="1" :checked="upd_land.DUPLEX == 1">
									دوبليكس
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_duplex">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_corner">
									<input type="checkbox" id="upd_corner" name="upd_corner" :checked="1 == upd_land.CORNER" value="1">
									زاوية
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_corner">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_append">
									<input type="checkbox" id="upd_append" name="upd_append" :checked="upd_land.APPEND == 1" value="1">
									ملحق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_append">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_basment">
									<input type="checkbox" id="upd_basment" name="upd_basment" :checked="upd_land.BASEMENT == 1" value="1">
									بدروم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_basment">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_monsters">
									<input type="checkbox" id="upd_monsters" name="upd_monsters" :checked="upd_land.MONSTER == 1" value="1">
									الحوش
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_monsters">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_swim">
									<input type="checkbox" id="upd_swim" name="upd_swim" :checked="upd_land.SWIM == 1" value="1">
									المسبح
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_swim">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_kitchen">
									<input type="checkbox" id="upd_kitchen" name="upd_kitchen" :checked="upd_land.KITCHEN == 1" value="1">
									المطبخ
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_kitchen">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_elevator">
									<input type="checkbox" id="upd_elevator" name="upd_elevator" :checked="upd_land.ELEVATOR == 1" value="1">
									المصعد
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_elevator">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_ser_room">
									<input type="checkbox" id="upd_ser_room" name="upd_ser_room" :checked="upd_land.SER_ROOM == 1" value="1">
									غرفة الخادم
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_ser_room">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_dr_room">
									<input type="checkbox" id="upd_dr_room" name="upd_dr_room" :checked="upd_land.DR_ROOM == 1" value="1">
									غرفة السائق
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_dr_room">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="upd_air_cond">
									<input type="checkbox" id="upd_air_cond" name="upd_air_cond" :checked="upd_land.AIR_COND == 1" value="1">
									التكييف
									<span class="checkbox"></span>
								</label>
								<div class="d-none err_notification" id="valid_upd_air_cond">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_mortage" class="">هل يوجد الرهن أو القيد الذي يمنع او يحد من التصرف او الانتفاع من العقار؟</label>
								<textarea :readonly="upd_land.ADV_NO.length != 0" type="text" id="upd_mortage" name="upd_mortage" class="form-control" placeholder="لا يوجد">{{upd_land.MORTGAGE}}</textarea>
								<div class="d-none err_notification" id="valid_upd_mortage">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_law" class="">الحقوق والالتزامات على العقار الغير موثقة في وثيقة العقار</label>
								<textarea :readonly="upd_land.ADV_NO.length != 0" type="text" id="upd_law" name="upd_law" class="form-control" placeholder="لا يوجد">{{upd_land.LAW}}</textarea>
								<div class="d-none err_notification" id="valid_upd_law">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_info" class="">المعلومات التي قد تؤثر على العقار سواء في خفض قيمته او التأثير على قرار المستهدف بالإعلان</label>
								<textarea :readonly="upd_land.ADV_NO.length != 0" type="text" id="upd_info" name="upd_info" class="form-control" placeholder="لا يوجد">{{upd_land.INFO}}</textarea>
								<div class="d-none err_notification" id="valid_upd_info">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_dispute" class=""> النزاعات القائمة على العقار</label>
								<textarea :readonly="upd_land.ADV_NO.length != 0" type="text" id="upd_dispute" name="upd_dispute" class="form-control" placeholder="لا يوجد">{{upd_land.DISPUTES}}</textarea>
								<div class="d-none err_notification" id="valid_upd_dispute">this field required</div>
							</div>
						</div>
						<div class="row ">
							<div class="col-sm mb-3">
								حدود واطوال العقار
							</div>
						</div>
						<div class="row">
							<div class="col col-sm-3 mb-3">
								<label for="upd_des_n" class="">الطول من الشمال</label>
								<input type="number" lang="en" id="upd_des_n" name="upd_des_n" class="form-control" :value="upd_land.DES_N" placeholder="الطول من الشمال" step="any" />
								<div class="d-none err_notification" id="valid_upd_des_n">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="upd_des_s" class="">الطول من الجنوب</label>
								<input type="number" lang="en" id="upd_des_s" name="upd_des_s" class="form-control" :value="upd_land.DES_S" placeholder="الطول من الجنوب" step="any" />
								<div class="d-none err_notification" id="valid_upd_des_s">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="upd_des_e" class="">الطول من الشرق</label>
								<input type="number" lang="en" id="upd_des_e" name="upd_des_e" class="form-control" :value="upd_land.DES_E" placeholder="الطول من الشرق" step="any" />
								<div class="d-none err_notification" id="valid_upd_des_e">this field required</div>
							</div>
							<div class="col col-sm-3 mb-3">
								<label for="upd_des_w" class="">الطول من الغرب</label>
								<input type="number" lang="en" id="upd_des_w" name="upd_des_w" class="form-control" :value="upd_land.DES_W" placeholder="الطول من الغرب" step="any" />
								<div class="d-none err_notification" id="valid_upd_des_w">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm  mb-3">
								<label for="upd_file_image" class="label-control">المرفقات</label>
								<input type="file" name="upd_file_image[]" max_size="<?php echo MAX_FILE_SIZE; ?>" class="file-upload multi_image_upload form-control-file" data-id="upd_images_area" multiple />
								<div class="d-none err_notification" id="valid_upd_file_image">this field required</div>
							</div>
						</div>
						<div class="row clear_form_area" id="upd_images_area"></div>
						<div class="row clear_form_area">
							<div class="col-sm mb-3" v-for="(img,img_index) in upd_land.OTHER_IMG">
								<img :src="img.URL" width="100px" height="100px" class="img-thumbnail" alt="..." />
								<div v-if="img.URL != upd_land.IMG">
									<button type="button" class="btn btn-block btn-primary" v-on:click.prevent="del_upd_img(img_index)">حذف الصورة</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm  mb-3">
								<label class="label-control"></label>
								<label class="label-control">قم بإختيار الجهات التي تريد ان يكون الاعلان محدد لها او لا تختار اي جهة ليبقى اعلان عام</label>

							</div>
						</div>
						<div class="row">
							<table class="table table-bordered table-striped table-hover table-head-fixed text-right">
								<thead>
									<tr>
										<th><input type="checkbox" id='msgs' /></th>
										<th>الصورة</th>
										<th>الاسم</th>
										<!--th>الهاتف</th>
										<th>البريد الإلكتروني</th-->
									</tr>
								</thead>
								<tbody>
									<tr v-for="(x ,index) in company">
										<td><input type="checkbox" name="company[]" class="msgs" :value="x.ID" :checked="upd_land.COMPANY.includes(x.ID)" /></td>
										<td><img v-bind:src="x.IMG" class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" /></td>
										<td>{{x.NAME}}</td>
										<!--td>{{x.PHONE}}</td>
										<td>{{x.EMAIL}}</td-->
									</tr>
								</tbody>
							</table>
						</div>
						<div class="form_msg d-none">تم تعديل العقار</div>

					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> تعديل العقار</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For delete land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="del_land">
		<div class="modal-dialog modal-md">
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL ?>my_land/del_land" data-model="del_land" data-type="del_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><i class="fa fa-edit"></i> حذف عقار</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_land.ID" />
						<div class="row">
							<div class="col-sm mb-3">
								<img id="upd_land_img" :src="upd_land.IMG" width="150px" height="150px" class="img-thumbnail mb-1" alt="الصورة">
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								هل أنت متأكد أنك تريد مسح هذا الاعلان
								<br />
								{{upd_land.TYPE_NAME}} {{upd_land.FOR_NAME}} {{upd_land.CIT_NAME}} / {{upd_land.NEI_NAME}} / {{upd_land.BLOCK}}

							</div>
							<div class="col-sm mb-3">
								إذا قمت بمسح الاعلان لن يتم استرجاع بياناته
							</div>

						</div>
						<div class="form_msg d-none">تم مسح العقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> مسح العقار</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For vip land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="vip_land">
		<div class="modal-dialog modal-lg">
			<form class="row g-3" id="vip_land_pay" method="post" action="<?php echo URL ?>my_land/upgrade" data-type="add">
				<div class="modal-content">
					<div class="modal-header">
						<h5 v-if="pay_form == 'VIP_BILL'" class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> ترقية العقار للباقة المميزة</h5>
						<h5 v-if="pay_form == 'LAND_BILL'" class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> دفع عمولة الاعلان</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="vip_land.ID" />
						<div class="row mr-1">
							<h5>{{vip_land.TYPE_NAME}} {{vip_land.FOR_NAME}} {{vip_land.CIT_NAME}} / {{vip_land.NEI_NAME}} / {{vip_land.BLOCK}}</h5>
						</div>
						<input type="hidden" class="" name="type" :value="pay_form" />
						<div class="row mr-1" v-if="vip_land.ID">
							<h5>{{types[vip_land.TYPE].NAME}} {{vip_land.CIT_NAME}} / {{vip_land.NEI_NAME}} / {{vip_land.BLOCK}}</h5>
						</div>
						<div class="row" v-if="pay_form == 'LAND_BILL' && vip_land.ID">
							<div class="col-sm mb-3">
								<label for="vip_price" class="">السعر</label>
								<input v-if="vip_land.FOR == 'SALE'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['SALE']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'INVEST'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['INVESTMENT']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_D'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_DAY']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_M'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_MONTH']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_Y'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_YEAR']" placeholder="السعر" required />
								<input v-else="" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" value="0" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row my-2" v-if="pay_form == 'VIP_BILL'">
							<div class="col-sm mb-3 new_home_area">
								<label for="vip_range" class="">المدة: <span id="vip_range_val">{{VIP_period}}</span></label>
								<input type="range" :min="VIP_period" max="365" :step="VIP_period" data-view="vip_range_val" :value="VIP_period" @change="vip_price_change" id="vip_range" name="vip_range" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_vip_range">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_price" class="">السعر</label>
								<input type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="VIP_price" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="vip_cobon" class="">رمز التخفيض</label>
								<input type="text" lang="en" name="vip_cobon" id="vip_cobon" class="form-control" value="" placeholder="رمز التخفيض" @change="ch_cobon(event)" />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_price" class="">مبلغ / نسبة التخفيض</label>
								<input type="text" id="vip_discount" class="form-control" value="" readonly />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="vip_card" class="">رقم البطاقة</label>
								<input type="text" data-paylib="number" lang="en" dir="ltr" autocomplete="off" size="20" class="form-control" value="" placeholder="رقم البطاقة" />
								<img src="https://iwebkit.net/wp-content/uploads/2021/06/mastercardandvisa.jpg" width="35%">
								<img src="https://play-lh.googleusercontent.com/n8xRJaDMXDSw_103_w3T7sy1NaatwcXzh2h2gfXu7nRolieu2AsnvdEpgWV1aEMrRg" width="9%" class="mx-1">
								<img src="https://cdn6.aptoide.com/imgs/1/c/d/1cd63acc2107c45813ec3bb88180afaa_icon.png" width="20%" class="mx-1">
								<div class="d-none err_notification" id="valid_vip_card">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label>تاريخ انتهاء الصلاحية (YYYY/MM)</label>
								<div class="row">
									<div class="col-sm mb-3 d-flex">
										<input type="text" class="form-control" data-paylib="expmonth" autocomplete="off" size="3" placeholder="الشهر">
										<input type="text" class="form-control" data-paylib="expyear" autocomplete="off" size="5" placeholder="السنة">
									</div>
								</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_pass" class="">رقم CVV</label>
								<input type="text" lang="en" data-paylib="cvv" size="4" class="form-control" value="" placeholder="CVV" />
								<div class="d-none err_notification" lang="en" autocomplete="off" id="valid_vip_pass">this field required</div>
							</div>
						</div>
						<div class="row" id="vip_land_pay_error"></div>
						<div class="form_msg d-none">تمت إضافة العقار للباقة المميزة</div>
					</div>
					<div class="modal-footer">
						<button v-if="pay_form == 'VIP_BILL'" type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> دفع عمولة الترقية</button>
						<button v-if="pay_form == 'LAND_BILL'" type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> دفع العمولة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For price land ---	->
	<div class="modal bd-example-modal-lg modal_with_form" id="land_price" >
		<div class="modal-dialog modal-lg">
			<form class="row g-3" id="land_bill_form" method="post" action="<?php echo URL ?>my_land/land_bill"  data-type="add">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> دفع عمولة الاعلان</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="vip_land.ID" />
						<div class="row">
							<h5>{{vip_land.TYPE_NAME}} {{vip_land.FOR_NAME}} {{vip_land.CIT_NAME}} / {{vip_land.NEI_NAME}} / {{vip_land.BLOCK}}</h5>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="vip_price" class="">السعر</label>
								<input v-if="vip_land.FOR == 'SALE'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['SALE']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'INVEST'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['INVESTMENT']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_D'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_DAY']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_M'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_MONTH']" placeholder="السعر" required />
								<input v-else-if="vip_land.FOR == 'RENT_Y'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price['RENT_YEAR']" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="vip_card" class="">رقم البطاقة</label>
								<input type="text" data-paylib="number" lang="en" dir="ltr" autocomplete="off" size="20" class="form-control" value="" placeholder="رقم البطاقة" />
								<div class="d-none err_notification" id="valid_vip_card">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label>تاريخ انتهاء الصلاحية (YYYY/MM)</label>
                                <div class="row">
									<div class="col-sm mb-3">
										<input type="text" data-paylib="expmonth" autocomplete="off"  size="3" placeholder="الشهر">
										<input type="text" data-paylib="expyear" autocomplete="off"  size="5" placeholder="السنة">
									</div>
								</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_pass" class="">رقم CVV</label>
								<input type="text" lang="en" data-paylib="cvv" size="4" class="form-control" value="" placeholder="CVV" />
								<div class="d-none err_notification" lang="en" autocomplete="off" id="valid_vip_pass">this field required</div>
							</div>
						</div>
						<div class="row" id="land_bill_form_error"></div>
						<div class="form_msg d-none">تم دفع العمولة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> دفع العمولة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div-->

</div>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://maps.googleapis.com/maps/api/js?language=ar&key=AIzaSyC9LAtPj0KJDr5l621IbMZcQinoYO-7-4g&libraries=drawing&v=weekly"></script>

<script src="<?php echo P_JS_FILE; ?>"></script>


<script>
	var js_cities = <?php echo json_encode($this->cities); ?>;
	var js_company = <?php echo json_encode($this->comp_list); ?>;
	var js_types = <?php echo json_encode($this->land_type); ?>;
	var js_statues = <?php echo json_encode(lib::$land_for); ?>;
	var js_adv = <?php echo json_encode(lib::$adv_type); ?>;
	var js_config = <?php echo json_encode($this->conf_list); ?>;
	var js_currency = <?php echo json_encode(lib::$currency); ?>;
	var js_relation = <?php echo json_encode(lib::$land_relation); ?>;
	var js_land = [];
	var js_interface = <?php echo json_encode(lib::$land_interface); ?>;
	var js_VIP_period = <?php echo session::get("VIP_PERIOD"); ?>;
	var js_VIP_price = <?php echo session::get("VIP_PRICE"); ?>;
	var js_price = [];

	js_price["RENT_YEAR"] = <?php echo session::get("RENT_YEAR"); ?>;
	js_price["RENT_DAY"] = <?php echo session::get("RENT_DAY"); ?>;
	js_price["RENT_MONTH"] = <?php echo session::get("RENT_MONTH"); ?>;
	js_price["SALE"] = <?php echo session::get("SALE"); ?>;
	js_price["INVESTMENT"] = <?php echo session::get("INVESTMENT"); ?>;

	var upg_pay = <?php echo (!empty($this->upgrade)) ? json_encode($this->upgrade) : "''"; ?>;
	var JS_KEY = <?php echo "'" . P_JS_KEY . "'"; ?>
</script>