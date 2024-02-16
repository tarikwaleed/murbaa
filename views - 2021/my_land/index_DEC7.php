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
			<form class="filter-form" id="land_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>my_land/">
				<input type="hidden" name="paging_curr_no" id="paging_curr_no" value="1" />
				<input type="hidden" name="limit" id="paging_length" value="<?php echo session::get("PAGING") ?>" />
				<div class="">
					<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
					<div class="row">
						<div class="col-sm mb-3">
							<select name="city" id="city" class="form-control" @change="onChangeCity($event)">
								<option value="" selected disabled >إختار المدينة</option>
								<option  v-for="(x,id) in cities" v-bind:data-no="id" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>	
						<div class="col-sm mb-3">
							<select name="neighborhood" id="neighborhood" class="form-control">
								<option  value="" selected>إختار الحى</option>
								<option  v-for="x in neighborhood" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>	
						<div class="col-sm mb-3">
							<input type="number" name="block" class="form-control" placeholder="المربع"/>
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<select name="adv" id="adv" class="form-control">
								<option  value="" selected>إختار النوع</option>
								<option  v-for="(x,id) in adv" v-bind:value="id">{{x}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<select name="types" id="types" class="form-control">
								<option  value="" selected>إختار النوع</option>
								<option  v-for="(x,id) in types" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<select name="land_for" id="land_for" class="form-control">
								<option  value="" selected>إختار الحالة</option>
								<option  v-for="(x,id) in statues" v-bind:value="id">{{x}}</option>
							</select>
						</div>
						<div class="col-sm mb-3">
							<input name="rooms" type="number" class="form-control" placeholder="عدد الغرف"/>
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3 small">
							<span>المساحة الاعلانية المتاحة: <span v-if="(config.AREA - config.ALL_LANDS) > 0">{{config.AREA - config.ALL_LANDS}}</span><span v-else="">0</span> </span> <br/>
							<span>المساحة الاعلانية  VIP المتاحة: <span v-if="(config.VIP - config.CURR_VIP) > 0">{{config.VIP - config.CURR_VIP}}</span><span v-else="">0</span>  </span>
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary">بحـــث <i class="fa fa-search"></i></button>
						</div>
						<div class="col-sm mb-3">
							<button v-if="config.AREA - config.ALL_LANDS > 0" type="button" v-on:click.prevent="new_land" class="btn btn-block btn-success" data-toggle="modal" data-target="#new_land">إضافة عقار <i class="fa fa-plus"></i></button>
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
													<input name="rooms_max" class="form-control" type="number"/>
												</div>
											</div>
											<div class="row mb-3">				
												<label>السعر</label>
												<div class="col">
													<input name="price_min" class="form-control" type="number" /> 
												</div> -
												<div class="col">
													<input name="price_max" class="form-control" type="number"/>
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
													<input name="size_max" class="form-control" type="number"/>
												</div>
											</div>
											<div class="row mb-3">				
												<label>الطابق</label>
												<div class="col">
													<input name="floor_min" class="form-control" type="number"/>
												</div> -
												<div class="col">
													<input name="floor_max" class="form-control" type="number"/>				
												</div>
											</div>
										</div>
										<div class="ml-column last-column">
											<div class="row mb-3">
												<label class="">الحمامات</label>
												<div class="col">
													<input name="baths_min" class="form-control" type="number"/>
												</div> -
												<div class="col">
													<input name="baths_max" class="form-control" type="number"/>
												</div>
											</div>
											<div class="row mb-3">
												<label class="">الشارع</label>
												<div class="col">
													<input name="road_min" class="form-control" type="number"/>
												</div> -
												<div class="col">
													<input name="road_max" class="form-control" type="number"/>
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
	<section class="property-section latest-property-section pt-2 " >
		<div class="container ">
			<div class="row property-filters ">
				<div v-for="(x , land_index) in displayedPosts" class="col-md-12">
					<div class="row property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm h-md-200 position-relative" style="height:auto">
						<div class="col p-4 d-flex flex-column position-static">
							<div class="pi-text">
								<h5><a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID">{{x.TYPE_NAME}} {{x.FOR_NAME}} {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}} </a></h5>
								<div class="pt-price">{{x.PRICE}} {{x.CURRENCY}}<span v-if="x.FOR == 'RENT'"> / للشهر</span></div>
								<!--div>
									<a v-bind:href="x.LOCATION" target="_blank" v-if="x.LOCATION ">
										<span class="fa fa-map-marker"></span> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}
									</a>
								</div>
								<!--div class="pi-date">{{x.ACT_DATE}}</div-->
								<div class="pi-span d-none d-md-block d-lg-block">
									<span><i class="fa fa-object-group"></i> {{x.SIZE}} م²</span> 
									<span v-if="x.IS_RES"><i class="fa fa-bathtub"></i> {{x.BATHS}}</span> 
									<span v-if="x.IS_RES"><i class="fa fa-bed"></i> {{x.ROOMS}}</span> 
									<span v-if="x.IS_RES"><i class="fa fa-automobile"></i> {{x.CARS}}</span> 
								</div>
								<div class="pi-span d-none d-md-block d-lg-block">
									<div class="dec"> {{x.DESC}} </div>
								</div>
								<div class="mt-2">
									<button type="button" v-on:click="update_land(land_index)" class="btn btn-primary mb-3" data-toggle="modal" data-target="#upd_land">تعديل </button>
									<button v-if="x.ACTIVE == 1" v-on:click.prevent="active(land_index)" class='btn  btn-warning mb-3'>تجميد</button>
									<button v-else="" v-on:click.prevent="active(land_index)" class='btn  btn-success mb-3 '>فك تجميد</button>
									<button type="button" v-on:click="update_land(land_index)" class="btn btn-danger mb-3" data-toggle="modal" data-target="#del_land">حذف </button>
									
									<p v-if="x.PACKAGE_START">متميز من {{x.PACKAGE_START}} - {{x.PACKAGE_END}}</p>
									<button v-else-if="config.VIP - config.CURR_VIP > 0" v-on:click.prevent="vip(land_index)" class='btn btn-success mb-3 '>عرض مميز</button>
									<button v-else="" v-on:click.prevent="by_vip(land_index)" class='btn btn-success mb-3' data-toggle="modal" data-target="#vip_land" >شراء عرض مميز</button>
									
									<button v-if="config.PK_PRICE == 1" v-on:click.prevent="by_land(land_index)" class='btn btn-success mb-3' data-toggle="modal" data-target="#land_price" >دفع عمولة الاعلان</button>
								</div>
							</div>
						</div>
						<div class="col-1 text-center d-none d-md-block d-lg-block"><br>
							<h5><i class="fa fa-eye-slash"></i></h5><br>
							<span><h5><i class="fa fa-clock-o"></i></h5> 
							{{x.DATE}}
							</span>
						</div>
						<div class="col-auto">
							<a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID" target="_blank">
								<div class="pi-pic set-bg rounded" v-bind:data-setbg="x.IMG">
									<div v-bind:class = "(x.FOR != 'RENT')?'label c-red':'label'"> {{x.FOR_NAME}} - {{x.ADV_NAME}} </div>
								</div>
							</a>
						</div>	
					</div>
				</div>	
			</div>
			
			<!-- pagination -->
			<nav aria-label="" data-aos="fade-up" id="paging">
				<ul class="pagination justify-content-center mt-3" >
					<li class="page-item">
						<button type="button" class="page-link" v-if="current_page != 1" @click="current_page--"> السابق </button>
					</li>
					<li class="page-item">
						<button type="button" class="page-link" v-for="pageNumber in pages.slice(current_page-1, current_page+5)" @click="current_page = pageNumber"> {{pageNumber}} </button>
					</li>
					<li class="page-item">
						<button type="button" class="page-link" v-if="current_page < pages.length" @click="current_page++" > التالى </button>
					</li>
				</ul>
			</nav>
		</div>
	</section>
	
	<!-- Property Section End -->
	
	<!-- Modal For add new land -->
	<div class="modal bd-example-modal-lg modal_with_form" id="new_land">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="new_staff_form" method="post" action="<?php echo URL?>my_land/new_land" data-model="new_land" data-type="new_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="new_land_title"><i class="fa fa-plus"></i> إضافة عقار</h5>
						<button type="button" class="btn-close bg-white btn p-0" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<div id="map_area" class="map_area"></div>
						<div class="row">
							<!--div class="col-sm mb-3">
								<label for="new_city" class="">المدينة</label>
								<select name="new_city" id="new_city" class="form-control" @change="onChangeCity($event)" required >
									<option value="" selected disabled >إختار المدينة</option>
									<option  v-for="(x,id) in cities" v-bind:data-no="id" v-bind:value="x.ID">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_city">this field required</div>
							</div>	
							<div class="col-sm mb-3">
								<label for="new_neighborhood" class="">الحي</label>
								<select name="new_neighborhood" id="new_neighborhood" class="form-control" required >
									<option  value="" selected>إختار الحى</option>
									<option  v-for="(x,id) in neighborhood" v-bind:data-no="id" v-bind:value="x.ID">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_neighborhood">this field required</div>
							</div-->	
							<div class="col-sm mb-3">
								<label for="new_no" class="">رقم العقار</label>
								<input type="number" lang="en" id="new_no" name="new_no" class="form-control" placeholder="رقم العقار" required />
								<div class="d-none err_notification" id="valid_new_no">this field required</div>
								<div class="err_notification " id="duplicate_new_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_space" class="">المساحة</label>
								<input type="number" lang="en" id="new_space" name="new_space" class="form-control" placeholder="المساحة" required />
								<div class="d-none err_notification" id="valid_new_space">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_block" class="">المربع</label>
								<input type="number" lang="en" name="new_block" id="new_block" class="form-control" placeholder="المربع" required />
								<div class="d-none err_notification" id="valid_new_block">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_lng" class="">خط الطول</label>
								<input type="number" lang="en" step="any" id="new_lng" name="new_lng" class="form-control" placeholder="خط الطول"/>
								<div class="d-none err_notification" id="valid_new_lng">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_lat" class="">خط العرض</label>
								<input type="number" lang="en" id="new_lat" step="any" name="new_lat" class="form-control" placeholder="خط العرض"/>
								<div class="d-none err_notification" id="valid_new_lat">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<img id="new_land_img" src="<?php echo URL;?>public/IMG/land/default.png" width="150px" height="150px" class="img-thumbnail mb-1" alt="الصورة">
							</div>
							<div class="col-sm mb-3">
								<input type="file" name="new_land_img" max_size="<?php echo MAX_FILE_SIZE ;?>" class="file-upload image_upload form-control-file form-control-sm" data-id="new_land_img" id="img" accept="image/*" />
								<div class="d-none err_notification" id="valid_new_land_img">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_desc" class="">وصف العقار</label>
								<textarea type="text" id="new_desc" name="new_desc" class="form-control" placeholder="وصف العقار" required></textarea>
								<div class="d-none err_notification" id="valid_new_desc">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_type" class="">العلاقة بالعقار</label>
								<select name="new_relation" id="new_relation" class="form-control" @change="onChangeRelation($event)" >
									<option v-for="(x,id) in relation" v-bind:value="id" v-bind:data-dele="x.del">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_relation">this field required</div>
							</div>
							<div class="col-sm mb-3" v-if="delegate">
								<label for="new_type" class="">رقم التفويض</label>
								<input type="text" id="new_delegate" name="new_delegate" class="form-control" placeholder="رقم التفويض" required />
								<div class="d-none err_notification" id="valid_new_delegate">this field required</div>
							</div>
							<input v-else="" type="hidden" id="new_delegate" name="new_delegate" class="form-control" value="" />
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_type" class="">النوع الاعلان</label>
								<select name="new_adv" id="new_adv" class="form-control">
									<option v-for="(x,id) in adv" v-bind:value="id">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_adv">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_type" class="">النوع</label>
								<select name="new_type" id="new_type" class="form-control" required @change="onChangeType($event,'new_home_area','new_farm_area')">
									<option value="" selected disabled>إختار النوع</option>
									<option v-for="(x,id) in types" :value="x.ID" :data-id="id">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>	
							<div class="col-sm mb-3">
								<label for="new_status" class="">الحالة</label>
								<select name="new_status" id="new_status" class="form-control" required >
									<option value="" selected disabled>إختار الحالة</option>
									<option v-for="(x,id) in statues" v-bind:value="id">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_status">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="new_price" class="">السعر</label>
								<input type="number" lang="en" id="new_price" name="new_price" class="form-control" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_new_price">this field required</div>
							</div>	
							<div class="col-sm mb-3">
								<label for="new_currency" class="">العملة</label>
								<select name="new_currency" id="new_currency" class="form-control" required >
									<option  v-for="(x,id) in currency" v-bind:value="x">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_currency">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_interface" class="">واجهة</label>
								<select name="new_interface" id="new_interface" class="form-control" >
									<option value="" selected disabled >إختار الواجهة</option>
									<option  v-for="(x,id) in interf" v-bind:value="id">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_new_interface">this field required</div>
							</div>							
						</div>
						<div class="row" >
							<div class="col-sm mb-3 new_farm_area">
								<label for="new_bath" class="">عدد النخيل</label>
								<input type="number" lang="en" id="new_tree" name="new_tree" class="form-control" placeholder="عدد النخيل" />
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
						<div class="row" >
							<div class="col-sm mb-3 new_home_area">
								<label for="new_rooms" class="">عدد الغرف: <span id="new_r_range_val"></span></label>
								<input type="range" min="0" max="40" data-view="new_r_range_val" value="0" id="new_rooms" name="new_rooms" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_type">this field required</div>
							</div>	
							<div class="col-sm mb-3 new_home_area">
								<label for="new_bath" class="">عدد الحمامات: <span id="new_bath_range_val"></span></label>
								<input type="range" min="0" max="40" data-view="new_bath_range_val" value="0" id="new_bath" name="new_bath" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_bath">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_hall" class="">الصالات: <span id="new_hall_range_val"></span></label>
								<input type="range" min="0" max="40" data-view="new_hall_range_val" value="0" id="new_hall" name="new_hall" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_hall">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 new_home_area">
								<label for="new_floor" class="">الطابق: <span id="new_floor_range_val"></span></label>
								<input type="range" min="0" max="50" data-view="new_floor_range_val" value="0" id="new_floor" name="new_floor" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_floor">this field required</div>
							</div>
							<div class="col-sm mb-3 new_home_area">
								<label for="new_year" class="">سنة التشييد: <span id="new_year_range_val"></span></label>
								<input type="range" min="1900" max="2021" data-view="new_year_range_val" id="new_year" name="new_year" class="default_year_place form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_year">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="new_road" class="">عرض الشارع: <span id="new_road_range_val"></span></label>
								<input type="range" min="2" max="100" step="2" data-view="new_road_range_val" name="new_road" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_road">this field required</div>
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
							
						</div>
						<div class="row">
							<div class="col-sm  mb-3">
								<label for="file" class="label-control">المرفقات</label>
								<input type="file" name="new_file_image[]" max_size="<?php echo MAX_FILE_SIZE ;?>" class="file-upload multi_image_upload form-control-file" data-id="new_images_area" multiple />
								<div class="d-none err_notification" id="valid_new_file_image">this field required</div>
							</div>
						</div>
						<div class="row clear_form_area" id="new_images_area"></div>
						<div class="form_msg d-none">تم حفط العقار</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفط العقار</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<!-- Modal For update land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="upd_land">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL?>my_land/upd_land" data-model="upd_land" data-type="upd_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> تعديل عقار</h5>
						<button type="button" class="btn-close btn p-0 bg-white" data-dismiss="modal" aria-hidden="true"><i class="fa fa-close"></i></button>
					</div>
					<div class="container"></div>
					<div class="modal-body">
						<input type="hidden" class="hid_info" name="csrf" value="<?php echo session::get('csrf'); ?>" />
						<input type="hidden" class="" name="id" :value="upd_land.ID" />
						<div id="upd_map_area" class="map_area"></div>
						<div class="row">
							<div class="col-sm mb-3">
								<img id="upd_land_img" :src="upd_land.IMG" width="150px" height="150px" class="img-thumbnail mb-1" alt="الصورة">
							</div>
							<div class="col-sm mb-3">
								<input type="file" name="upd_land_img" max_size="<?php echo MAX_FILE_SIZE ;?>" class="file-upload image_upload form-control-file form-control-sm" data-id="upd_land_img" id="img" accept="image/*" />
								<div class="d-none err_notification" id="valid_new_land_img">this field required</div>
							</div>
						</div>
						<div class="row">
							<!--div class="col-sm mb-3">
								<label for="upd_city" class="">المدينة</label>
								<select id="upd_city" name="upd_city" class="form-control" @change="onChangeCity($event)" required >
									<option value="" selected disabled >إختار المدينة</option>
									<option  v-for="(x,id) in cities" v-bind:data-no="id" v-bind:value="x.ID" :selected="x.ID == upd_land.CIT_ID">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_city">this field required</div>
							</div>	
							<div class="col-sm mb-3">
								<label for="upd_neighborhood" class="">الحي</label>
								<select name="upd_neighborhood" class="form-control" required >
									<option  value="" selected>إختار الحى</option>
									<option  v-for="(x,id) in neighborhood" v-bind:data-no="id" v-bind:value="x.ID" :selected="x.ID == upd_land.NEI_ID">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_neighborhood">this field required</div>
							</div-->	
							<div class="col-sm mb-3">
								<label for="upd_block" class="">المربع</label>
								<input type="number" name="upd_block" class="form-control" placeholder="المربع" required :value="upd_land.BLOCK" />
								<div class="d-none err_notification" id="valid_upd_block">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_no" class="">رقم العقار</label>
								<input type="number" lang="en" name="upd_no" class="form-control" :value="upd_land.NO" placeholder="رقم العقار" required />
								<div class="d-none err_notification" id="valid_upd_no">this field required</div>
							    <div class="err_notification " id="duplicate_upd_no">البيانات المدخلة في هذا الحقل مدخلة من قبل</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
							</div>
							<div class="col-sm mb-3">
								<label for="upd_lng" class="">خط الطول</label>
								<input type="number" lang="en" step="any" id="upd_lng" name="upd_lng" class="form-control" :value="upd_land.LOC_LNG" placeholder="خط الطول"/>
								<div class="d-none err_notification" id="valid_upd_lng">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_lat" class="">خط العرض</label>
								<input type="number" lang="en" id="upd_lat" step="any" name="upd_lat" class="form-control" :value="upd_land.LOC_LAT" placeholder="خط العرض"/>
								<div class="d-none err_notification" id="valid_upd_lat">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_space" class="">المساحة</label>
								<input type="number" lang="en" name="upd_space" class="form-control" :value="upd_land.M_SIZE" placeholder="المساحة" required />
								<div class="d-none err_notification" id="valid_upd_space">this field required</div>
							</div>
						</div><div class="row">
							<div class="col-sm mb-3">
								<label for="upd_relation" class="">العلاقة بالعقار</label>
								<select name="upd_relation" id="upd_relation" class="form-control" @change="onChangeRelation($event)" >
									<option v-for="(x,id) in relation" v-bind:value="id" v-bind:data-dele="x.del" :selected="id == upd_land.RELATION">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_relation">this field required</div>
							</div>
							<div class="col-sm mb-3" v-if="delegate">
								<label for="upd_delegate" class="">رقم التفويض</label>
								<input type="text" id="upd_delegate" name="upd_delegate" class="form-control" :value="upd_land.DELEGATION"  placeholder="رقم التفويض" />
								<div class="d-none err_notification" id="valid_upd_delegate">this field required</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_desc" class="">وصف العقار</label>
								<textarea name="upd_desc" class="form-control" placeholder="وصف العقار" required >{{upd_land.DESC}}</textarea>
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
								<select name="upd_type" class="form-control" required required @change="onChangeType($event,'upd_home_area','upd_farm_area')">
									<option value="" selected disabled>إختار النوع</option>
									<option v-for="(x,id) in types" v-bind:value="x.ID" :selected="x.ID == upd_land.TYPE">{{x.NAME}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_type">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_status" class="">الحالة</label>
								<select name="upd_status" class="form-control" required >
									<option value="" selected disabled>إختار الحالة</option>
									<option v-for="(x,id) in statues" v-bind:value="id" :selected="id == upd_land.FOR">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_status">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="upd_price" class="">السعر</label>
								<input type="number" lang="en" name="upd_price" class="form-control" :value="upd_land.M_PRICE" placeholder="السعر" required />
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>	
							<div class="col-sm mb-3">
								<label for="upd_currency" class="">العملة</label>
								<select name="upd_currency" class="form-control" required >
									<option value="" selected disabled >إختار العملة</option>
									<option  v-for="(x,id) in currency" v-bind:value="x" :selected="id == upd_land.CURRENCY">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_currency">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_interface" class="">واجهة</label>
								<select name="upd_interface" id="upd_interface" class="form-control" >
									<option value="" selected disabled >إختار الواجهة</option>
									<option  v-for="(x,id) in interf" v-bind:value="id" :selected="id == upd_land.INTERFACE">{{x}}</option>
								</select>
								<div class="d-none err_notification" id="valid_upd_interface">this field required</div>
							</div>
						</div>
						<div class="row" >
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
								<input type="range" min="0" max="10" data-view="new_mushub_range_val" :value="(upd_land.MUSHUB == null)?0:upd_land.MUSHUB"  id="new_mushub" name="new_mushub" class="form-control-range range_input" />
								<div class="d-none err_notification" id="valid_new_mushub">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_rooms" class="">عدد الغرف: <span id="upd_r_range_val">{{upd_land.ROOMS}}</span></label>
								<input type="range" min="0" max="50" data-view="upd_r_range_val" :value="(upd_land.ROOMS == null)?0:upd_land.ROOMS" id="upd_rooms" name="upd_rooms" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_rooms">this field required</div>
							</div>	
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_bath" class="">عدد الحمامات: <span id="upd_bath_range_val">{{upd_land.BATHS}}</span></label>
								<input type="range" min="0" max="50" data-view="upd_bath_range_val" :value="(upd_land.BATHS == null)?0:upd_land.BATHS" id="upd_bath" name="upd_bath" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_bath">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_hall" class="">الصالات: <span id="upd_hall_range_val">{{upd_land.HALLS}}</span></label>
								<input type="range" min="0" max="50" data-view="upd_hall_range_val" :value="(upd_land.HALLS == null)?0:upd_land.HALLS" id="upd_hall" name="upd_hall" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_hall">this field required</div>
							</div>							
						</div>
						<div class="row">
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_floor" class="">الطابق: <span id="upd_floor_range_val">{{upd_land.FLOOR}}</span></label>
								<input type="range" min="0" max="50" data-view="upd_floor_range_val" :value="(upd_land.FLOOR == null)?0:upd_land.FLOOR" value="0" id="upd_floor" name="upd_floor" class="form-control range_input" />
								<div class="d-none err_notification" id="valid_upd_floor">this field required</div>
							</div>
							<div class="col-sm mb-3 upd_home_area">
								<label for="upd_year" class="">سنة التشييد: <span id="upd_year_range_val">{{upd_land.BULID}}</span></label>
								<input type="range" min="1900" max="2000" data-view="upd_year_range_val" :value="(upd_land.BULID == null)?0:upd_land.BULID" id="upd_year" name="upd_year" class="form-control default_year_place range_input" />
								<div class="d-none err_notification" id="valid_upd_year">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="upd_road" class="">عرض الشارع: <span id="upd_road_range_val">{{upd_land.ROAD}}</span></label>
								<input type="range" min="2" max="100" step="2" :value="(upd_land.ROAD == null)?0:upd_land.ROAD" data-view="upd_road_range_val" name="upd_road" id="upd_road" class="form-control range_input" />
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
						</div>
						<div class="row">
							<div class="col-sm  mb-3">
								<label for="upd_file_image" class="label-control">المرفقات</label>
								<input type="file" name="upd_file_image[]" max_size="<?php echo MAX_FILE_SIZE ;?>" class="file-upload multi_image_upload form-control-file" data-id="upd_images_area" multiple />
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
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL?>my_land/del_land" data-model="del_land" data-type="del_land">
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
								<br/>
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
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL?>my_land/upgrade" data-model="upd_land" data-type="upd_land">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="upd_land_title"><i class="fa fa-edit"></i> ترقية العقار للباقة المميزة</h5>
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
								<label for="vip_card" class="">رقم البطاقة</label>
								<input type="text" lang="en" name="vip_card" id="vip_card" class="form-control" value="" placeholder="رقم البطاقة" required />
								<div class="d-none err_notification" id="valid_vip_card">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_pass" class="">كلمة المرور</label>
								<input type="password" lang="en" name="vip_pass" id="vip_pass" class="form-control" value="" placeholder="كلمة المرور" required />
								<div class="d-none err_notification" id="valid_vip_pass">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تمت إضافة العقار للباقة المميزة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> ترقية العقار</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
	<!-- Modal For price land	-->
	<div class="modal bd-example-modal-lg modal_with_form" id="land_price">
		<div class="modal-dialog modal-lg">
			<form class="row g-3 model_form" id="upd_land_form" method="post" action="<?php echo URL?>my_land/land_bill" data-model="land_price" data-type="new_land_price">
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
								<input v-if="vip_land.FOR == 'SALE'" type="number" readonly lang="en" name="vip_price" id="vip_price" class="form-control" :value="land_price[vip_land.FOR]" placeholder="السعر" required />
								<select v-else="" name="vip_price" id="vip_price" class="form-control">
									<option :value="land_price['RENT_YEAR']">ايجار طويل المدة: {{land_price['RENT_YEAR']}}</option> 
									<option :value="land_price['RENT_DAY']">ايجار قصير المدة: {{land_price['RENT_DAY']}}</option> 
								</select>
								<div class="d-none err_notification" id="valid_upd_price">this field required</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm mb-3">
								<label for="vip_card" class="">رقم البطاقة</label>
								<input type="text" lang="en" name="vip_card" id="vip_card" class="form-control" value="" placeholder="رقم البطاقة" required />
								<div class="d-none err_notification" id="valid_vip_card">this field required</div>
							</div>
							<div class="col-sm mb-3">
								<label for="vip_pass" class="">كلمة المرور</label>
								<input type="password" lang="en" name="vip_pass" id="vip_pass" class="form-control" value="" placeholder="كلمة المرور" required />
								<div class="d-none err_notification" id="valid_vip_pass">this field required</div>
							</div>
						</div>
						<div class="form_msg d-none">تم دفع العمولة</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary mb-3"><i class="fa fa-save"></i> دفع العمولة</button>
						<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal"><i class="fa fa-times"></i> الغاء</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</div>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC9LAtPj0KJDr5l621IbMZcQinoYO-7-4g&v=weekly"
	></script>

<script>
	var js_cities 		= <?php echo json_encode($this->cities); ?>;
	var js_types 		= <?php echo json_encode($this->land_type); ?>;
	var js_statues 		= <?php echo json_encode(lib::$land_for); ?>;
	var js_adv 			= <?php echo json_encode(lib::$adv_type); ?>;
	var js_config 		= <?php echo json_encode($this->conf_list); ?>;
	var js_currency 	= <?php echo json_encode(lib::$currency); ?>;
	var js_relation 	= <?php echo json_encode(lib::$land_relation); ?>;
	var js_land 		= [];
	var js_interface 	= <?php echo json_encode(lib::$land_interface); ?>;
	var js_VIP_period	= <?php echo session::get("VIP_PERIOD");?>;
	var js_VIP_price	= <?php echo session::get("VIP_PRICE");?>;
	var js_price 		= [];
	js_price["RENT_YEAR"]= <?php echo session::get("RENT_YEAR");?>;
	js_price["RENT_DAY"]= <?php echo session::get("RENT_DAY");?>;
	js_price["SALE"] 	= <?php echo session::get("SALE");?>;
	
</script>
