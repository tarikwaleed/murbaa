<style>
*{
	    scrollbar-width:thin;
 	    scrollbar-color:#d49a38 #f1f1f1;
}
	button.page-link {
		display: inline-block;
	}
::-webkit-scrollbar {
  width: 7px;
  height: 7px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
::-webkit-scrollbar-thumb {
  background: #d49a38; 
}


::-webkit-scrollbar-thumb:hover {
  background: rgb(214, 176, 112); 
}
    .nav-scroller{
        margin-top:20px;
    }
    .nav-scroller .rounded-pill{
        color:#d49a38;
    }
        .nav-scroller {
            -webkit-overflow-scrolling: touch;
	    scrollbar-width:thin;
 	    scrollbar-color:#d49a38 #f1f1f1;
            -ms-overflow-style: -ms-autohiding-scrollbar;
            display: flex;
            overflow: auto;
            position: relative;
        }
        .nav-scroller .rounded-pill { 
            width: 170px;
            padding:10px;
            font-weight:bold;

        }
 
        .custom-control-inline{
            margin:0;
            margin-left:5px
        }

    @media(max-width:1900px){
        #land_search .container-fluid{
            padding: 0 ;
        }
    }
    @media (max-width: 425px){
        .nav-scroller .rounded-pill {
            width: 135px;
            padding: 12px 4px;
            font-weight: bold;
            font-size: 12px;
        }
    }
    @media only screen and (max-width: 767px){
        .property-item .pi-pic {
            height: 100px;
            width: 100px;
        }
    }
@media only screen and (min-width: 1900px){
.center-nav-scroller{
    justify-content: start!important
}
}

    .row.h-md-200{
        height:auto !important;
        padding:8px;
    }
    .row.h-md-200 .col.p-4{
        padding: 0 !important;
    }
    .property-item .pi-pic{
        margin: 0 !important
    }
    .row.h-md-200  .col-auto{
        padding:0;
    }
</style>
<div id="vue_area_div" class="container vue_area_div">
	<form class="filter-form" id="land_search" v-on:submit.prevent="onSubmitSearch" method="POST" action="<?php echo URL?>dashboard/search">
		<input type="hidden" name="paging_curr_no" id="paging_curr_no" value="1" />
		<input type="hidden" name="limit" id="paging_length" value="<?php echo session::get("PAGING") ?>" />
		<input type="hidden" name="csrf" id="csrf" class="hid_info" value="<?php echo lib::get_CSRF(); ?>" />
		<div class="container-fluid">
			<div class="nav-scroller d-flex justify-content-start center-nav-scroller py-2">
				<div v-for="(x,id) in types" class="btn-group-toggle custom-control-inline d-flex mt-2 mb-1">
					<label class="btn btn-light border rounded-pill d-flex align-items-center justify-content-center" style="width:110px">
						<input type="radio" name="types" @change="onSubmitSearch" :value="x.ID"> 
						<span>{{x.NAME}}</span>
					</label>
				</div>
			</div>
		</div>
		<div class="container-fluid mt-2">
			<div class="text-center">
				<div class="rounded-pill p-2 border mx-auto" style="display: table">
					<div v-for="(x,id) in adv" class="form-check form-check-inline m-0">
					<div class="d-flex align-items-center my-auto">
					<input class="form-check-input" type="radio" name="adv" @change="onSubmitSearch" :checked="id == 'ADV'" :id="'ty_'+id" :value="id" style="accent-color:rgb(0, 105, 0)">
					<label class="form-check-label text-dark mr-2" :for="'ty_'+id">{{x}}</label>
					</div>
				</div>
			<?php
				if(session::get('user_id'))
				{
			?>
				<div class="form-check form-check-inline m-0">
					<div class="d-flex align-items-center my-auto">
					<input class="form-check-input" type="checkbox" name="fav" @change="onSubmitSearch" id='fav' value="1" style="accent-color:rgb(0, 105, 0)">
					<label class="form-check-label text-dark mr-2" for="fav">المفضلة</label>
					</div>
				</div>
				<div class="form-check form-check-inline m-0">
					<div class="d-flex align-items-center my-auto">
					<input class="form-check-input" type="checkbox" name="my_adv" @change="onSubmitSearch" id='my_adv' value="1" style="accent-color:rgb(0, 105, 0)">
					<label class="form-check-label text-dark mr-2" for="my_adv">الاعلان الخاص</label>
					</div>
				</div>
			<?php
				}
            ?>
</div>
            </div>
		</div>
		<!-- Search Section Begin -->
		<section class="search-section collapse" id="searchco">
			<div class="container bg-white p-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h4>البحث المتقدم</h4>
						</div>
					</div>
				</div>
				<div class="">
					<div class="row">
						<div class="col-sm mb-4">
							<select name="city" id="city" class="form-control" @change="onChangeCity($event)">
								<option value="" >إختار المدينة</option>
								<option  v-for="(x,id) in cities" v-bind:data-no="id" v-bind:value="x.ID">{{x.NAME}}</option>
							</select>
						</div>	
						<div class="col-sm mb-4">
							<select name="neighborhood" id="neighborhood" class="form-control" >
								<option value="" >إختار الحى</option>
								<option v-for="x in neighborhood" v-bind:value="x.ID">{{x.NAME}} -- {{x.LETTER}}</option>
							</select>
						</div>	
						<div class="col-sm mb-4">
							<select name="neighbor_letter" class="form-control" >
								<option value="" >إختار حرف الحى</option>
								<option v-for="x in letters" :value="x" >{{x}}</option>
							</select>
						</div>	
						<div class="col-sm mb-4">
							<input type="number" name="block" class="form-control" placeholder="المربع"/>
						</div>
					</div>
					<div class="row">
						<!--div class="col-sm mb-3">
							<select name="land_for" id="land_for" class="form-control">
								<option  value="" selected>إختار الحالة</option>
								<option  v-for="(x,id) in statues" v-bind:value="id">{{x}}</option>
							</select>
						</div-->
						<div class="col-sm mb-3">
							<input name="rooms" type="number" class="form-control" placeholder="عدد الغرف"/>
						</div>
						<div class="col-sm mb-3">
							<input name="size" type="number" class="form-control" placeholder="المساحة">
						</div>
					</div>
					<div class="row">
						<div class="col-sm mb-3">
							<input name="price" type="number" class="form-control" placeholder="السعر">
						</div>
						<div class="col-sm mb-3">
							<button type="submit" id="search" class="btn btn-block btn-primary"><i class="fa fa-search"></i> بحـــث</button>
						</div>
					</div>
				</div>
				<div class="more-option">
					<div class="accordion" id="accordionExample">
						<div class="card">
							<div class="card-heading active">
								<a data-toggle="collapse" data-target="#collapseOne">
									خيارات البحث الاضافية
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
			
		</div>
	</section>
	<!-- Search Section End -->
	</form>
	<!-- Property Section Begin -->
	<section class="property-section latest-property-section pt-2 " >
		<div class="container ">
			<div class="row property-filters ">
				<div v-for="x in displayedPosts" class="col-md-12">
					<div class="row property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm h-md-200 position-relative">
						<div class="col p-4 d-flex flex-column position-static">
							<div class="pi-text">
								<a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
								<h5>
                                    <a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID">{{types[x.TYPE].NAME}} {{statues[x.FOR].NAME}}<span v-if="x.NEI_NAME"> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}} </span> </a>
									<span><i v-for="index in x.LAND_STARS" :key="index" class="fa fa-star" style="color:gold"></i></span>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <span class="d-flex" v-if="x.VISIT != 0"><h5><i class="fa fa-eye ml-2"></i></h5>
                                        {{x.VISIT}}<br/>
                                    </span>
							        <span v-else=""><h5><i class="fa fa-eye-slash"></i></h5><br/></span>
							        <span v-if="x.LIKES != null && x.LIKES != 0" class="d-flex ml-2"><h5>&nbsp; &nbsp;<i class="fa fa-thumbs-up ml-2"></i></h5>{{x.LIKES}}</span>
							        <span class="d-flex ml-2"><h5>&nbsp; &nbsp;<i class="fa fa-clock-o ml-2"></i></h5> 
                                    {{x.DATE}}
                                    </span>
						        </div>
								<div class="pt-price">{{x.PRICE}} {{x.CURRENCY}}<span v-if="x.FOR == 'RENT'"> / للشهر</span></div>
								<!--div>
									<a v-bind:href="x.LOCATION" target="_blank" v-if="x.LOCATION ">
										<span class="fa fa-map-marker"></span> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}
									</a>
								</div>
								<!--div class="pi-date">{{x.ACT_DATE}}</div-->
								<div class="pi-span">
									<span><i class="fa fa-object-group"></i> {{x.SIZE}} م²</span> 
									<span v-if="types[x.TYPE].BUILD"><i class="fa fa-bathtub"></i> {{x.BATHS}}</span> 
									<span v-if="types[x.TYPE].BUILD"><i class="fa fa-bed"></i> {{x.ROOMS}}</span> 
									<span v-if="types[x.TYPE].BUILD"><i class="fa fa-automobile"></i> {{x.CARS}}</span> 
								</div>
								<div class="pi-span d-none d-md-block d-lg-block">
									<div class="dec"> {{x.DESC}} </div>
								</div>
								<div class="pi-agent d-none d-md-block d-lg-block">
									<div class="pa-item">
										<span class="pa-info">
											<img :src="x.OW_IMG" alt="">
											<h6>{{x.OW_NAME}} / <br/> 
												<span><i v-for="index in x.STARS" :key="index" class="fa fa-star" style="color:gold"></i></span>
                                            </h6>
										</span>
										<span class="pa-text">
											{{x.OW_PHONE}}
											<span v-if="x.OW_ACCEPT == 1">
												<i aria-hidden="true" class="text-success fa fa-check-square-o" title="حساب موثق"></i>
												- رقم المعلن: {{x.OW_ACCEPT_REG}}
											</span>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-auto">
							<a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID" target="_blank">
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
</div>



<script>
	var js_cities 		= <?php echo json_encode($this->cities); ?>;
	var js_types 		= <?php echo json_encode($this->land_type); ?>;
	var js_statues 		= <?php echo json_encode(lib::$land_for); ?>;
	var js_adv 			= <?php echo json_encode(lib::$adv_type); ?>;
	var js_land 		= [];
	var js_interface 	= <?php echo json_encode(lib::$land_interface); ?>;
	var js_letters 		= <?php echo json_encode(lib::$letters); ?>;
	var js_suggest 		= [];
</script>
