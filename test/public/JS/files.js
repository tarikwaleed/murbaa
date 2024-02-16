
//For Uploading Files
$(document).ready(function(){
	
})

//for open div info
function uploadInfo(id) 
{
	var x = document.getElementById(id);
	if (x.className.indexOf("w3-show") == -1) {
		x.className += " w3-show";
	} else { 
		x.className = x.className.replace(" w3-show", "");
	}
}

//Upload File
var accept_ext = ["csv","xls","xlsx"];

$(".display_file").change(function(e) 
{
	var input_file	= $(this); 
	var target_id	= $(this).attr('data-location');
	var table_id	= $(this).attr('data-table');
	var regex 		= /^([a-zA-Z0-9\u0600-\u06FF\s_\\.\-:])+(.xlsx|.xls|.csv)$/; 
	
	var table 		= $('<table>').addClass('w3-table w3-centered w3-bordered w3-border w3-border-top w3-hoverable w3-centered');
	table.attr('id','upl_list');
	var t_header 	= $('<thead>').addClass('w3-theme');
	var t_body 		= $('<tbody>');
	var t_row 		= $('<tr/>');
	var t_d 		= $('<td class="w3-bordered w3-centered" data-col="" data-row=""/>');
	
	if (typeof (FileReader) == "undefined") 
	{
		alert("Sorry! Your browser does not support HTML5!");
		return false;
	}
	
	if (!regex.test(input_file.val().toLowerCase())) 
	{
		alert('Upload CSV,XLS,XLSX \n');
		return false;
	}
	
	if (e.target.files == undefined) 
	{
		alert('Please check the file');
		return false;
	}
	
	$("#"+target_id).html("");
	
	var reader = new FileReader();
	reader.onload = function(e)
	{
		var data = e.target.result; 
		var html_table;
		
		if(input_file.val().toLowerCase().indexOf(".csv") > 0)
		{
			html_table = set_csv_date(data , table , t_header,t_body, t_row ,t_d);
		}else
		{
			var xlsx = input_file.val().toLowerCase().indexOf(".xlsx") > 0
			html_table = set_xls_date(data , table , t_header,t_body,t_row ,t_d,xlsx)
		}
		
		
		$("#"+target_id).html("");
		$("#"+target_id).append(table);
	
	};
	if(input_file.val().toLowerCase().indexOf(".csv") > 0)
	{
		reader.readAsText(e.target.files.item(0));
	}else if(input_file.val().toLowerCase().indexOf(".xlsx") > 0)
	{
		reader.readAsArrayBuffer(e.target.files.item(0));
	}else
	{
		reader.readAsBinaryString(e.target.files.item(0));
	}
	
	return false;
});


function set_csv_date(data , table , t_header, t_body, t_row ,t_d)
{
	var content = OnFileLoad_valid(data);
	if (content == "")
	{
		alert("هنالك خطأ فى ملف الاكسل - الرجاء مراجعته");
		return "";
	}
	var csvval = content.split("\n");
			
	for(var i = 0;i< csvval.length;i++)
	{
		csvval[i] = $.trim(csvval[i]);
	}
	
	var header = csvval[0].split(",");
	
	var curr_r = t_row.clone();
	var curr_d;
	
	for(var i = 0; i< header.length; i++)
	{
		header[i] = header[i].trim();
		
		curr_d = t_d.clone();
		
		curr_d.attr('data-col',i);
		curr_d.html(header[i]);
		curr_r.append(curr_d);
	}
	
	//add header
	t_header.append(curr_r);
	table.append(t_header);
	
	
	//add data
	
	var row_line, k;
	
	for(i = 1; i< csvval.length; i++)
	{
		curr_r = t_row.clone();
		row_line = csvval[i].split(",");
		
		for(k = 0; k< row_line.length; k++)
		{
			row_line[k] = row_line[k].trim();
			
			curr_d = t_d.clone();
			curr_d.attr('data-col',k);
			curr_d.attr('data-row',i);
			curr_d.html(row_line[k]);
			
			curr_r.append(curr_d)
		}
		t_body.append(curr_r)
	}
	table.append(t_body);
	return table;
}


////////////FOR EXCEL Files
function set_xls_date(data , table , t_header, t_body, t_row ,t_d, xlsxflag) 
{  
	//Converts the excel data in to object 
	if (xlsxflag) {  
		var workbook = XLSX.read(data, { type: 'binary' });  
	}  
	else {  
		var workbook = XLS.read(data, { type: 'binary' });  
	}  
	
	//Gets all the sheetnames of excel in to a variable 
	var sheet_name_list = workbook.SheetNames;  
					
	var cnt = 0; //This is used for restricting the script to consider only first sheet of excel 
	sheet_name_list.forEach(function (y) 
	{ 
		//Iterate through all sheets  
		//Convert the cell value to Json
		if (xlsxflag) {  
			var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
		}  
		else {  
			var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
		}  
		if (exceljson.length > 0 && cnt == 0) 
		{  
			table = BindTable(exceljson , table , t_header, t_body, t_row, t_d);  
			cnt++;  
		}  
	});
	return table;
}  

function BindTable(jsondata , table , t_header, t_body, t_row,t_d) 
{
	//Function used to convert the JSON array to Html Table
		
	var tx = BindTableHeader(jsondata,  table , t_header,t_row,t_d); //Gets all the column headings of Excel
	var columns =  tx.no;
	table = tx.table;
	var curr_r,curr_d;
	for (var i = 0; i < jsondata.length; i++) 
	{  
		curr_r = t_row.clone(); 
		for (var colIndex = 0; colIndex < columns.length; colIndex++) 
		{  
			var cellValue = jsondata[i][columns[colIndex]];  
			if (cellValue == null)  
				cellValue = "";  
			
			curr_d = t_d.clone();
			
			curr_d.attr('data-row',i);
			curr_d.attr('data-col',colIndex);
			curr_d.html(cellValue);
			curr_r.append(curr_d);
			 
		}  
		t_body.append(curr_r);  
	}
	table.append(t_body);
	
	return table;
}  
	
function BindTableHeader(jsondata, table , t_header,t_row,t_d) 
{
	//Function used to get all column names from JSON and bind the html table header  
	var columnSet = [];  
	var curr_r = t_row.clone();
	for (var i = 0; i < jsondata.length; i++) 
	{  
		var rowHash = jsondata[i];  
		for (var key in rowHash) 
		{  
			if (rowHash.hasOwnProperty(key)) 
			{  
				if ($.inArray(key, columnSet) == -1) 
				{
					//Adding each unique column names to a variable array  
					columnSet.push(key);
					curr_d = t_d.clone();
					curr_d.html(key);
					
					curr_r.append(curr_d);  
				}  
			}  
		}
		t_header.append(curr_r); 
	}
	
	table.append(t_header);  
	return {'no':columnSet,'table':table} ;  
}


function OnFileLoad_valid(file_content)
{
	var cont_error = ["script","function","alert","<?"];
	var x = false;
	var ret = file_content;
	$.each(cont_error, function( index, value ) {
		x = file_content.includes(value);
		if(x)
		{
			ret = "";
		}
	});
	
	return ret;
}

