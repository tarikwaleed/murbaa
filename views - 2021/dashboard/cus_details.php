<style>
	button.page-link {
		display: inline-block;
	}
</style>
<div id="vue_area_div" class="container vue_area_div">
	<input type="hidden" name="limit" id="paging_length" value="<?php echo PAGING ?>" />
	<div class="avatar m-0">
		<span>
			<img :src="info.IMG" class="img-thumbnail rounded-circle" width="100px" height="100px" alt="">
			{{info.NAME}} 
		</span>
		<i v-if="info.ACCEPT == 1" class="fa text-success fa-check" title="حساب موثق"></i>
	</div>
	<div class="avatar m-0">
		<span>
			 {{info.PHONE}}
		</span>
		<span>
			 / {{info.EMAIL}}
		</span>
	</div>
	<div v-if="info.REG_NO !== null" class="avatar m-0">
		<span>
			رقم المعلن: {{info.REG_NO}}
		</span>
	</div>
	<div v-if="info.REG_REAL_NO !== null" class="avatar m-0">
		<span>
			رقم ترخيص الهيئة العامة للعقار: {{info.REG_REAL_NO}}
		</span>
	</div>
	<!--div v-if="info.REG_CO_NO !== null" class="avatar m-0">
		<span>
			رقم العمل: {{info.REG_CO_NO}}
		</span>
	</div-->
	<div class="avatar m-0">
		<span>
			{{info.ADDRESS}} 
		</span>
	</div>
	<div class="avatar m-0">
		<span>
			{{info.DESC}} 
		</span>
	</div>
	<!-- Property Section Begin -->
	<section class="property-section latest-property-section pt-2 " >
		<div class="container ">
			<div class="row property-filters ">
			
				<div v-for="x in displayedPosts" class="col-md-12">
					<div class="row property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm h-md-200 position-relative">
						<div class="col p-4 d-flex flex-column position-static">
							<div class="pi-text">
								<a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
								<h5><a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID">{{x.TYPE_NAME}} {{x.FOR_NAME}} {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}} </a></h5>
								<div class="pt-price">{{x.PRICE}} {{x.CURRENCY}}<span v-if="x.FOR == 'RENT'"> / للشهر</span></div>
								<!--div>
									<a v-bind:href="x.LOCATION" target="_blank" v-if="x.LOCATION ">
										<span class="fa fa-map-marker"></span> {{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}
									</a>
								</div>
								<!--div class="pi-date">{{x.ACT_DATE}}</div-->
								<div class="pi-span">
									<span><i class="fa fa-object-group"></i> {{x.SIZE}} م²</span> 
									<span v-if="x.IS_RES"><i class="fa fa-bathtub"></i> {{x.BATHS}}</span> 
									<span v-if="x.IS_RES"><i class="fa fa-bed"></i> {{x.ROOMS}}</span> 
									<span v-if="x.IS_RES"><i class="fa fa-automobile"></i> {{x.CARS}}</span> 
								</div>
								<div class="pi-span d-none d-md-block d-lg-block">
									<div class="dec"> {{x.DESC}} </div>
								</div>
							</div>
						</div>
						<div class="col-1 text-center"><br>
							<h5><i class="fa fa-eye-slash"></i></h5><br>
							<span><h5><i class="fa fa-clock-o"></i></h5> 
							{{x.DATE}}
							</span>
						</div>
						<div class="col-auto">
							<a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID" target="_blank">
								<div class="pi-pic set-bg rounded" v-bind:data-setbg="x.IMG">
									<div v-bind:class = "(x.FOR != 'RENT')?'label c-red':'label'"> {{x.FOR_NAME}} </div>
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
	var js_info 		= <?php echo json_encode($this->customer); ?>;
</script>
