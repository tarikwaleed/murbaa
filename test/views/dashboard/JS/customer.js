vm_landsearch = new Vue({
	el: '#vue_area_div',
	data: {
		info		:js_info,
		page_number	: 10,
		current_page: 1,
		pages: [],
		statues		: js_statues,
		types		: js_types,
		
    },
	created: function() {
		
		this.page_number = (this.info.LANDS.length / $("#paging_length").val());
		this.setPages();
    },
	computed: {
		displayedPosts () {
			return this.paginate(this.info.LANDS);
		}
	},
	mounted(){
		if(this.info.LANDS.length == 0){
			$("#footer_area").css('position','fixed');
			$("#footer_area").css('bottom',0);
			$("#footer_area").css('left',0);
			$("#footer_area").css('right',0);
		}
	},
	methods: {
		paginate (posts) {
			let page = this.current_page;
			let perPage = $("#paging_length").val();
			let from = (page * perPage) - perPage;
			let to = (page * perPage);
			return  posts.slice(from, to);
		},
		setPages () {
			let perPage = $("#paging_length").val();
			let numberOfPages = Math.ceil(this.info.LANDS.length / perPage);
			for (let index = 1; index <= numberOfPages; index++) {
				this.pages.push(index);
			}
		},
		
		go_page(event)
		{
			alert(event.target.className);
		},
		get_type_color($type)
		{
			var cls = "label ";
			if($type == "SALE")
			{
				cls += " c-red";
			}else if($type == "INVEST")
			{
				cls += " bg-info";
			}
			return cls;
		},
	}
});

//For Paging
//select page
$(document).on('click','.paging_no', function (e) 
{
	$('#paging_curr_no').val($(this).html());
})

//Previous page
$(document).on('click','#paging_prev', function (e) 
{
	$curr = $('#paging .paging_active');
	$next = $('#paging .paging_active').prev();
	
	if($next.html() != $(this).html())
	{
		$curr.removeClass('paging_active');
		$next.addClass('paging_active');
		$("#paging_curr_no").val($next.html());
		//paging_changes();
	}
})

//Next page
$(document).on('click','#paging_next', function (e) 
{
	$curr = $('#paging .paging_active');
	$next = $('#paging .paging_active').next();
	if($next.html() != $(this).html())
	{
		$curr.removeClass('paging_active');
		$next.addClass('paging_active');
		$("#paging_curr_no").val($next.html());
		//paging_changes();
	}	
})

//select page
$(document).on('click','.paging_no', function (e) 
{
	$('#paging .paging_active').removeClass('paging_active');
	$(this).addClass('paging_active');
	$("#paging_curr_no").val($(this).html());
	//paging_changes();
		
});

//add to adv list
$(document).on('click','#add_to_adv', function (e) 
{
	var id = vm_landsearch.info.ID;
	
	$.get(URL+"dashboard/adv_list/"+id, function(data, status){
		alert( data );
	});
});

$qq = new Vue({
	el: "#app",
	data () 
	{
		return {
			posts : [''],
			current_page: 1,
			perPage: 9,
			pages: [],		
		}
	},
	methods:{
		getPosts () 
		{	
			let data = [];
			for(let i = 0; i < 50; i++)
			{
				this.posts.push({first: 'John',last:'Doe', suffix:'#' + i});
			}  
		},
		setPages () {
			let numberOfPages = Math.ceil(this.posts.length / this.perPage);
			for (let index = 1; index <= numberOfPages; index++) {
				this.pages.push(index);
			}
		},
		paginate (posts) 
		{
			let current_page = this.current_page;
			let perPage = this.perPage;
			let from = (current_page * perPage) - perPage;
			let to = (current_page * perPage);
			return  posts.slice(from, to);
		}
	},
	computed: {
		displayedPosts () {
			return this.paginate(this.posts);
		}
	},
	watch: {
		posts () {
			this.setPages();
		}
	},
	created(){
		this.getPosts();
	},
	filters: {
		trimWords(value){
			return value.split(" ").splice(0,20).join(" ") + '...';
		}
	}
})

function modal_element($type,$id)
{
	
}
