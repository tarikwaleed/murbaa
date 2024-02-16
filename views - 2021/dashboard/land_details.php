<div id="vue_area_div" class="container vue_area_div" >
<!-- Property Details Section Begin -->
    <section class="property-details-section mt-5">
        <div class="property-pic-slider owl-carousel">
			<!--h4 class="mb-3">{{lands[0].TYPE_NAME}} {{lands[0].FOR_NAME}} {{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</h4-->
            <div class="ps-item">
                <div class="container-fluid">
                    <div class="row">
						<div class="col-md-6">
							<!--div class="col-sm-6 mb-1" v-for="x in lands[0].OTHER_IMG">
								<img v-bind:src="x.URL" class="img-thumbnail img-fluid d-block w-100 h-100" />
								<!--div class="ps-item-inner large-item set-bg"  v-bind:data-setbg="x.URL"></div>
							</div-->
							
							<div class="mainSlider" v-if="lands[0].OTHER_IMG.length <= 1">
								<div class="sliderInner" v-for="x in lands[0].OTHER_IMG">
									<img v-bind:src="x.URL" class="d-block w-100 h-ne rounded" />
                                    <div class="ad-details">
                                        <p class="card p-3 ad-number">  {{lands[0].ID}}</p>
                                        <h5>{{lands[0].ADV_NAME}} - {{lands[0].TYPE_NAME}}</h5>
                                        <p class=" pt-2 pb-3">{{lands[0].DESC}}</p>
                                    </div>
								</div>
							</div>
							<div v-else="" class="slide-content position-relative">
                                <div  id="carouselExampleIndicators" class="carousel slide"  data-ride="carousel">
								
								<button id="close_slide_full" type="button" class="btn btn-secondary d-none" style="position:fixed;top:0;left:0;zIndex:10000" ><i class="fa fa-times"></i> </button>
								
								<ol class="carousel-indicators">
									<li v-for="(x,index) in lands[0].OTHER_IMG" data-target="#carouselExampleIndicators" :data-slide-to="index" :class="(index==0)? 'active':''"></li>
								</ol>
								<div class="carousel-inner">
									<div v-for="(x,index) in lands[0].OTHER_IMG"  :class="(index==0)? 'carousel-item slide_div_kk active':'carousel-item slide_div_kk'" >
										<img v-bind:src="x.URL" class="d-block w-100 h-ne rounded slide_img_kk" alt="...">
                                        
									</div>
								</div>
								<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="sr-only">Next</span>
								</a>
                                
							</div>
							<div class="mainSlider_s" v-if="lands[0].OTHER_VIDEO.length <= 1">
								<br/><br/>
								<video width="100%" height="100%" controls >
									<source v-for="x in lands[0].OTHER_VIDEO" :src="x.URL" :type="x.FILE_TYPE">
									Not Support Video
								</video> 
							</div>
                            <div class="ad-details">
                                            <p class="card  ad-number">  {{lands[0].ID}}</p>
                                            <h5>{{lands[0].ADV_NAME}} - {{lands[0].TYPE_NAME}}</h5>
                                            <p class=" pt-2 ">{{lands[0].DESC}}</p>
                            </div>
                            </div>
						</div>
						<!--div class="col-sm-4">
							<div class="card bg-light p-3 mb-3"> <h5>{{lands[0].ADV_NAME}} - {{lands[0].FOR_NAME}}</h5></div>
							<h4 class="mb-3">{{lands[0].TYPE_NAME}} {{lands[0].FOR_NAME}} {{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</h4>
							
							<a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
							<h4 class="mb-3"> السعر </h4>
							<div class="pt-price1"><b>{{lands[0].PRICE}} {{lands[0].CURRENCY}}<span v-if="lands[0].FOR == 'RENT'"> / للشهر</span></b></div>
							<p><h5><span class="fa fa-map-marker"></span> {{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</h5></p>
							<p><h5><i class="fa fa-clock-o"></i> {{lands[0].DATE}}</h5></p>
							<p class="card p-3">رقم الإعلان : {{lands[0].ID}}</p>
						</div-->
						<div class="col-md-6">
							
							<a href="#" class="heart-icon"><span class="icon_heart_alt"></span></a>
							<div class="pt-price1"><b>{{lands[0].PRICE}} {{lands[0].CURRENCY}}<span v-if="lands[0].FOR == 'RENT'"> / للشهر</span></b></div>
							<!--h5 class="mb-2">{{lands[0].TYPE_NAME}} {{lands[0].FOR_NAME}} {{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</h5-->
							<p><h5><span class="fa fa-map-marker"></span> {{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</h5></p>
							<p><h5><i class="fa fa-clock-o"></i> {{lands[0].DATE}}</h5></p>
                            <!-- <p class="card p-3">رقم الإعلان : {{lands[0].ID}}</p>  -->
							
							
							<div class="share-ad mb-3 position-relative">
                            <p class="card p-3 m-0" style="padding-right:60px !important;"><?php echo URL."dashboard/land/";?>{{lands[0].ID}}</p>
                            <i class="fa fa-share position-absolute"></i>
                            </div>

                            <span class="qr-code" data-toggle="modal" data-target="#exampleModalCenter">
                                <i class="fa fa-qrcode"></i>
                                <!-- QR Modal -->
                                <div class="modal qr-modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">امسح الكود</h5>
                                        <button type="button" class="close p-0 m-0" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                       <img :src="'<?php echo URL."dashboard/qr/";?>'+lands[0].ID" />
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </span>
							<a class="whats" :href="'whatsapp://send?text=<?php echo URL."dashboard/land/";?>'+lands[0].ID" action="share/whatsapp/share" target="_blank" ><i class="fa fa-whatsapp"></i></a>


                            <div class="pd-title ad-owner">
									<hr />
									<div class="ad-owner-holder d-flex align-items-start">

											<img class="person-pic" :src="lands[0].OW_IMG" alt="">
                                            <div class="name-phone">
                                                <a target="_blank" :href="lands[0].OW_LINK">
                                                    <strong> {{lands[0].OW_NAME}}  </strong>
                                                </a>
                                                <span class="d-block">
										            {{lands[0].OW_EMAIL}} / {{lands[0].OW_PHONE}}
									            </span>
                                            </div>
											<i v-if="lands[0].OW_ACCEPT == 1" class="fa text-success fa-check" title="حساب موثق"></i>
									</div>
									
									<span  class="d-block mt-4" v-if="lands[0].OW_RELATION != 'OWNER' && lands[0].OW_RELATION != 'CO_REL'">
										<span class="d-block alert alert-info font-weight-bold" v-if="lands[0].OW_ACCEPT_REG !== null && lands[0].OW_ACCEPT_REG !== '' ">
											 رقم العمل: {{lands[0].OW_ACCEPT_REG}}
										</span>
										<!--span v-if="lands[0].OW_ACCEPT_NUM !== null && lands[0].OW_ACCEPT_NUM !== '' ">
											 رقم العمل: {{lands[0].OW_ACCEPT_NUM}}
										</span-->
										<span  class="d-block alert alert-info font-weight-bold" v-if="lands[0].OW_ACCEPT_REAL !== null && lands[0].OW_ACCEPT_REAL !== '' ">
											 رقم ترخيص الهيئة العامة للعقار: {{lands[0].OW_ACCEPT_REAL}}
										</span>
										<span  class="d-block alert alert-info font-weight-bold" v-if="lands[0].OW_DELEGATE !== null && lands[0].OW_DELEGATE !== '' ">
											 رقم التفويض: {{lands[0].OW_DELEGATE}}
										</span>
									</span>
								</div>
						</div>
					</div>
                </div>
            </div>
		</div>
        
		<hr/>
		 <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pd-text">
                     <div class="pd-board">
                            <div class="tab-board">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">تفاصيل العقار</a>
                                    </li>
                                    <!--li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">وصف العقار</a>
                                    </li-->
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">المحادثات</a>
                                    </li>
                                </ul><!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                        <div class="tab-details">
                                            <ul class="right-table">
                                                <li>
                                                    <span class="type-name">نوع العقار</span>
                                                    <span class="type-value">{{lands[0].TYPE_NAME}}</span>
                                                </li>
                                                <li>
                                                    <span class="type-name">معلن العقار</span>
                                                    <span class="type-value"><a target="_blank" :href="lands[0].OW_LINK">{{lands[0].OW_NAME}} <i v-if="lands[0].OW_ACCEPT == 1" class="fa  text-success fa-check "></i></a></span>
                                                </li>
												<!--li>
                                                    <span class="type-name">السعر</span>
                                                    <span class="type-value">{{lands[0].PRICE}} {{lands[0].CURRENCY}} <span v-if="lands[0].FOR == 'RENT'"> / للشهر</span></span>
                                                </li-->
                                                <li>
                                                    <span class="type-name">عرض الشارع</span>
                                                    <span class="type-value">{{lands[0].ROAD}}</span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">سنة التشييد</span>
                                                    <span class="type-value">{{lands[0].BULID}}</span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الطابق</span>
                                                    <span class="type-value">{{lands[0].FLOOR}}</span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الغرف</span>
                                                    <span class="type-value">{{lands[0].ROOMS}}</span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الحمامات</span>
                                                    <span class="type-value">{{lands[0].BATHS}}</span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">غرفة الخادم</span>
                                                    <span class="type-value">{{lands[0].SER_ROOM}}</span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">دوبليكس</span>
                                                    <span class="type-value"><span v-if="lands[0].DUPLEX != null" >بدوبليكس</span></span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">ملحق</span>
                                                    <span class="type-value"><span v-if="lands[0].APPEND != null" >بملحق</span></span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">مسبح</span>
                                                    <span class="type-value"><span v-if="lands[0].SWIM != null" >بمسبح</span></span>
                                                </li>
												<li v-if="lands[0].TREES !== null && lands[0].TREES != 0">
                                                    <span class="type-name">عدد النخيل</span>
                                                    <span class="type-value">{{lands[0].TREES}}</span>
                                                </li>
                                                <li v-if="lands[0].MUSHUB !== null && lands[0].MUSHUB != 0">
                                                    <span class="type-name">مشب</span>
                                                    <span class="type-value">{{lands[0].MUSHUB}}</span>
                                                </li>
                                            </ul>
                                            <ul class="left-table">
                                                <li>
                                                    <span class="type-name">منطقة العقار</span>
                                                    <span class="type-value">{{lands[0].CIT_NAME}} / {{lands[0].NEI_NAME}} / {{lands[0].BLOCK}}</span>
                                                </li>
												<li>
                                                    <span class="type-name">رقم العقار</span>
                                                    <span class="type-value">{{lands[0].ID}}</span>
                                                </li>
												<li>
                                                    <span class="type-name">المساحة</span>
                                                    <span class="type-value">{{lands[0].SIZE}}</span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الصالات</span>
                                                    <span class="type-value">{{lands[0].HALLS}}</span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الواجهة</span>
                                                    <span class="type-value"><span v-if="lands[0].INTERFACE != null" >{{interf[lands[0].INTERFACE]}}</span></span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">الزاوية</span>
                                                    <span class="type-value"><span v-if="lands[0].CORNER != null" >بزاوية</span></span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">مواقف السيارات</span>
                                                    <span class="type-value">{{lands[0].CARS}}</span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">غرفة السائق</span>
                                                    <span class="type-value"><span v-if="lands[0].DR_ROOM != null" >غرفة السائق</span></span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">حوش</span>
                                                    <span class="type-value"><span v-if="lands[0].MONSTER != null" >بحوش</span></span>
                                                </li>
                                                <li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">مصعد</span>
                                                    <span class="type-value"><span v-if="lands[0].ELEVATOR != null" >بمصعد</span></span>
                                                </li>
												<li v-if="lands[0].IS_RES == 1">
                                                    <span class="type-name">قبو</span>
                                                    <span class="type-value"><span v-if="lands[0].BASEMENT != null" >بقبو</span></span>
                                                </li>
                                                <li v-if="lands[0].WELL !== null && lands[0].WELL != 0">
                                                    <span class="type-name">عدد الابار</span>
                                                    <span class="type-value">{{lands[0].WELL}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!--div class="tab-pane" id="tabs-2" role="tabpanel">
                                        <div class="tab-desc">
                                            <p>{{lands[0].DESC}}</p>
                                        </div>
                                    </div-->
                                    <div class="tab-pane" id="tabs-3" role="tabpanel">
                                        <div class="tab-details">
											<div class="" v-if="lands[0].CHAT === false">
												<p class="">عليك تسجيل الدخول للبدء في المحادثة</p>
											</div>
											<div class="" v-else-if="lands[0].CHAT.length == 0 && (lands[0].IS_OW || lands[0].IS_ADMIN)">
												<div class="">لا توجد محادثات</div>
											</div>
											<div class="" v-else-if="lands[0].CHAT.length == 0 && !lands[0].IS_OW && !lands[0].IS_ADMIN">
												<button v-on:click.prevent="newChatRoom" class="btn">البدء في المحادثة</button>
												<input type="hidden" class="hid_info" name="csrf" id="csrf" value="<?php echo session::get('csrf'); ?>" />
											</div>
											<div class="tab-board" v-else="">
												<div id="accordion">
													<div class="card" v-for="(ROOM,ROOM_index) in lands[0].CHAT">
														<div class="card-header" :id="'chatRoom_'+ROOM_index">
															<h5 class="mb-0">
																<button class="btn btn-link collapsed" data-toggle="collapse" :data-target="'#chatRoomData_'+ROOM_index" aria-expanded="false" :aria-controls="'chatRoomData_'+ROOM_index">
																	المحادثة رقم {{ROOM.ID}}
																	<span v-if="lands[0].IS_OW || lands[0].IS_ADMIN"> -- العميل: {{ROOM.CUS_NAME}}</span>
																</button>
															</h5>
														</div>
														<div :id="'chatRoomData_'+ROOM_index" :class="(ROOM_index==0)?'collapse show':'collapse'" :aria-labelledby="'chatRoom_'+ROOM_index" data-parent="#accordion">
															<div class="card-body">
																<div v-for="(CHAT,index) in ROOM.CHAT_DATA" :class="'chat book_chat '+CHAT.CLASS " >
																	<div class="chat-user">
																		<a class="avatar m-0">
																			<img v-bind:src="CHAT.FR_IMG" alt="..." class="img-thumbnail rounded-circle" width="50px" height="50px" alt="100x100" />
																			{{CHAT.FR_NAME}}
																		</a>
																		<span class="chat-time mt-1">{{CHAT.DATE}}</span>
																	</div>
																	<div class="chat-detail">
																		<div class="chat-message">
																			<p v-html="CHAT.TEXT"></p>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-sm-12">
																<div class="chat-footer p-3 bg-white">
																	<form class="d-flex align-items-center chat_action" method="post" action="<?php echo URL?>dashboard/addchat" data-type="add">
																		<input type="hidden" class="hid_info" name="csrf" id="csrf" value="<?php echo session::get('csrf'); ?>" />
																		<input type="hidden" class="hid_info" name="chatroom" v-bind:value="ROOM.ID" />
																		<input type="hidden" class="hid_info room_info" v-bind:value="ROOM_index" />
																		<div class="input-group">
																			<input type="text" name="chat_msg" class="form-control mr-3" placeholder="الرسالة">
																			<button type="submit" class="btn btn-primary d-flex align-items-center"><i class="fa fa-paper-plane-o" aria-hidden="true"></i><span class="d-none d-lg-block ml-1">إرسال</span></button>
																		</div>
																	</form>
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
						</div>
                        <div class="row mb-5">
                            <div class="col-lg-12">
								<div v-if="lands[0].LOC_LAT != null && lands[0].LOC_LAT != null" class="mb-3" style="height:400px;width:100%" :data-lat="lands[0].LOC_LAT" :data-lng="lands[0].LOC_LNG" >
									<iframe :src="lands[0].LOCATION" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
								</div>
								<!--div id="land_location" v-if="lands[0].LOC_LAT != null && lands[0].LOC_LAT != null" class="mb-3" style="height:400px;width:100%" :data-lat="lands[0].LOC_LAT" :data-lng="lands[0].LOC_LNG" >
									
								</div-->
                            </div>
                        </div>
                       
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Suggestions -->
	<div v-if="suggest.length >= 1">
		<h5 class="mb-2">عقارات مقترحة</h5>
		<!--div class="row property-item g-0 border bg-hover-light rounded-lg overflow-hidden flex-md-row mb-2 shadow-sm h-md-200 position-relative">
			<div class="col-sm-2" v-for="(x,index) in suggest">
				<a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID" target="_blank">
					<img :src="x.IMG" />
					<div class="">
						<div v-bind:class = "(x.FOR != 'RENT')?'label c-red':'label'"> {{x.TYPE_NAME}} - {{x.FOR_NAME}} - {{x.ADV_NAME}}</div>
						<div>{{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}</div>
					</div>
				</a>
			</div>
		</div-->
		
		<div class="card-deck mb-5">
			<div class="card col-sm-2" v-for="(x,index) in suggest">
				<a v-bind:href="'<?php echo URL?>dashboard/land/'+x.ID" target="_blank">
					<img class="card-img-top" :src="x.IMG">
					<div class="card-body">
						<h5 class="card-title" v-bind:class = "(x.FOR != 'RENT')?'label c-red':'label'">
							{{x.TYPE_NAME}} - {{x.FOR_NAME}} - {{x.ADV_NAME}}
						</h5>
						<p class="card-text">
							<small class="text-muted">
								{{x.CIT_NAME}} / {{x.NEI_NAME}} / {{x.BLOCK}}
							</small>
						</p>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>

<script>
	var js_cities 		= <?php echo json_encode($this->cities); ?>;
	var js_types 		= <?php echo json_encode($this->land_type); ?>;
	var js_statues 		= <?php echo json_encode(lib::$land_for); ?>;
	var js_land 		= <?php echo json_encode($this->land); ?>;
	var js_adv 			= <?php echo json_encode(lib::$adv_type); ?>;
	var js_interface 	= <?php echo json_encode(lib::$land_interface); ?>;
	var js_letters 		= <?php echo json_encode(lib::$letters); ?>;
	var js_suggest 		= <?php echo json_encode($this->suggest); ?>;
</script>
