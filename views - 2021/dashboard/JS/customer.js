vm_landsearch = new Vue({
	el: '#vue_area_div',
	data: {
		info		:js_info,
		page_number	: 10,
		current_page: 1,
		pages: [],
		
		
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

