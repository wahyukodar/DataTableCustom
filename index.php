<?php
require_once "db_config.php";
class Index extends DB_Connect{
	function __construct() {
		$this->connect();
	}
	public function index(){
		$q = mysql_query("select * from customer");
		while($q_ = mysql_fetch_assoc($q)){
			if(isset($data)){
				array_push($data,$q_);
			}
			else{
				$data = array($q_);
			}
		}
		return $data;
	}
}

$index = new Index();
$datas = $index->index();
?>
<html>
<head>
<title>Tes</title>
<link rel="stylesheet" href="style.css">
<script src="jquery.js"></script>
<script>
var asc=false; // untuk cek sorting sedang dalam kondisi asc atau desc
var data_sort=[]; // simpan data hasil sorting
var title = new Array(); // untuk menyimpan title title tabelnya
var data = []; // buat menyimpan objek pertama kali
var pagingPer = 5; // tampilkan data perpaging
var temp_banyaknyadatapaging = 1; // defaultnya di halaman 1

$(document).ready(function(){
	// first step is getting title on each th
	$("table").find("th").each(function(index, element) {
		title.push($(this).text());
	});
	// ======= now we have title for each row in array :) ========
	
	// second step is get all data on table, BUT NOT TR TH because its title
	$("table").find("tr:has(td):not(tr:has(th))").each(function(index, element) {
		
		// store to temp_data for each row
		var temp_data = {};
		var i=1; // just count in log
		$(this).find("td").each(function(index2, element2) {
			console.log("Data Kolom "+i+" adalah: "+$(this).text());
			var titles = title[index2];
			var data_temp = $(this).text();
			temp_data[titles] = data_temp; // store data per column
			i++;
		});
		console.log("Temp Data: "+JSON.stringify(temp_data));
		console.log("Break Kolom");
		
		// store to Data
		data.push(temp_data);
	});
	
	console.log("=== DATAS ===: "+JSON.stringify(data));
	
	// sorting jika user mengclick
	$('th').click(function() {
		var diclick = $(this).text();
		console.log("=== AMBIL DATAS ===: "+JSON.stringify(data));
		if(asc){
			data_sort = helper.arr.multisort(data, [diclick], ['DESC']);
			asc=false;
		}
		else{
			data_sort = helper.arr.multisort(data, [diclick], ['ASC']);
			asc=true;
		}
		console.log("Data Sort: "+data_sort[0][diclick]);
		generateTableSort(temp_banyaknyadatapaging);
	});
	
	// selected for deleted
	$('tr:has(td)').live('click', function(){
		var getColorCurrent = $(this).find("td").css("background-color"); 
		var hexaColorCurrent = rgb2hex(getColorCurrent);
		if(hexaColorCurrent!="#ff0000"){
			$(this).find("td").css("background","#ff0000");
			$("#delete").css("display","block");
		}
		else{
			var noneDeleteButton = true;
			$(this).find("td").css("background","#d8d7d8");
			$("tr").find("td").each(function(){
				var getColorCurrent_ = $(this).css("background-color"); 
				var hexaColorCurrent_ = rgb2hex(getColorCurrent_);
				if(hexaColorCurrent_=="#ff0000"){
					noneDeleteButton = false;
				}
			});
			if(noneDeleteButton){$("#delete").css("display","none");}
		}
	});
	
	// sorting jika user mengclick
	$('#select').live('click', function(){
		var getColorCurrent = $("tr").find("td").css("background-color"); 
		var hexaColorCurrent = rgb2hex(getColorCurrent);
		if(hexaColorCurrent!="#ff0000"){
			$("tr").find("td").css("background","#ff0000");
			$("#delete").css("display","block");
		}
		else{
			var noneDeleteButton = true;
			$("tr").find("td").css("background","#d8d7d8");
			$("tr").find("td").each(function(){
				var getColorCurrent_ = $(this).css("background-color"); 
				var hexaColorCurrent_ = rgb2hex(getColorCurrent_);
				if(hexaColorCurrent_=="#ff0000"){
					noneDeleteButton = false;
				}
			});
			if(noneDeleteButton){$("#delete").css("display","none");}
		}
	});
	
	var selectoption = "";
	for(var s=pagingPer;s<=100;s+=5){
		if(s==pagingPer){
			var selectoption_ = "<option selected>"+s+"</option>";	
		}
		else{
			var selectoption_ = "<option>"+s+"</option>";	
		}
		selectoption = selectoption + selectoption_;
	}
	// show entries
	$('<select style="float:left;">'+selectoption+'</select>').insertBefore("table");
	
	$('<button style="float:left;margin-left:10px;" id="select">Select All</button>').insertBefore("table");
	
	$('<button style="float:right;margin-left:10px;display:none;" id="delete">Delete</button>').insertAfter("table");
	
	// paging saat oncahnge show entries
	$('select').on('change', function() {
		pagingPer = $(this).val();
		temp_banyaknyadatapaging = 1; // kita kembalikan ke 1 biar gak bug
		generatePagingKolom();
		generateTableSort(temp_banyaknyadatapaging);
		$("#delete").css("display","none");
	});
	
	// paging :) hahahahaha its to much if do it here? NO :P
	generatePagingKolom();
		
	// defaultnya data yang ada langsung kita paging :) hahaha
	data_sort = data; 
	generateTableSort(temp_banyaknyadatapaging);
});

function generatePagingKolom(){
	// reset
	$("#paging").remove();
	
	var kolomangkapaging = parseInt(data.length/pagingPer);
	var ceksisisabagi = data.length%pagingPer; // cek ada sisa baginya gak
		if(ceksisisabagi != 0){kolomangkapaging++;} // kalo ada sisa pembagiannya maka ditambah 1
	var b_paging = "";
		for(var p=1;p<=kolomangkapaging;p++){
			var b_ = '<span>'+p+'</span>';
			b_paging = b_paging + b_;
		}
	var z_paging = '<div id="paging"  style="float:left;">' + b_paging + '</div>';
	var paging_contain = $(z_paging);
	paging_contain.insertAfter("table");
	
	// paging jika user mengclick
	$('#paging > span').click(function() {
		generateTableSort($(this).text());
		temp_banyaknyadatapaging = $(this).text(); // update value
		$("#delete").css("display","none");
	});
}

function generateTableSort(banyaknyadata){
	// remove table cointain
	$("table").find("tr:has(td):not(tr:has(th))").remove(); // delete tr yang berisi td, jangan delete tr yg berisi th
	// looping untuk membuat elementnya
	var sampaiArrayke = banyaknyadata*pagingPer;
	if(sampaiArrayke>data_sort.length){sampaiArrayke=data_sort.length;}
	for (var i=((banyaknyadata*pagingPer)-pagingPer);i<sampaiArrayke;i++){
		// debug log
		console.log("Create Element: "+i+" of:"+ banyaknyadata*pagingPer);
		// adding to each tr
		var b = "";
		for(var j=0;j<title.length;j++){
			var titlenya = title[j];
			var b_ = "<td>"+data_sort[i][titlenya]+"</td>";
			b = b+b_;
		}
		var z = "<tr>"+b+"</tr>";
		console.log("HASIL=============================: "+z);
		$("table").append(z);
	}
	// making highlight background span paging that is active
	$("span").css("background","#a8a7a8");
	var to = banyaknyadata-1;
	$("span:eq("+to+")").css("background","#FF0000");
	console.log("ENNNNNNDDDD");
}

if( typeof helper == 'undefined' ) {
	console.log("create helper");
	var helper = { } ;
}

helper.arr = {
    multisort: function(arr, columns, order_by) {
        if(typeof columns == 'undefined') {
            columns = []
            for(x=0;x<arr[0].length;x++) {
                columns.push(x);
				console.log("Memindahkan ke array column: "+x);
            }
        }

        if(typeof order_by == 'undefined') {
            order_by = []
            for(x=0;x<arr[0].length;x++) {
                order_by.push('ASC');
				console.log("Memindahkan ke array orderby default ASC: "+x);
            }
        }

        function multisort_recursive(a,b,columns,order_by,index) {  
            var direction = order_by[index] == 'DESC' ? 1 : 0;

			console.log("arr sort function with a:"+a[columns[0]]+" === and b:"+b[columns[0]]+" === columns is:"+columns);
			
            var is_numeric = !isNaN(+a[columns[index]] - +b[columns[index]]);
			
			console.log("is numeric: "+is_numeric);
			
            var x = is_numeric ? +a[columns[index]] : a[columns[index]].toLowerCase();
            var y = is_numeric ? +b[columns[index]] : b[columns[index]].toLowerCase();

            if(x < y) {
                    return direction == 0 ? -1 : 1;
            }

            if(x == y)  {               
                return columns.length-1 > index ? multisort_recursive(a,b,columns,order_by,index+1) : 0;
            }

            return direction == 0 ? 1 : -1;
        }

        return arr.sort(function (a,b) { // PERTAMA LAKUKAN SORT DENGAN FUNGSI A DAN B
            return multisort_recursive(a,b,columns,order_by,0); // KEMUDIAN PROSES KE MULTISORT
        });
    }
};


var hexDigits = new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

//Function to convert hex format to a rgb color
function rgb2hex(rgb) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
	return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}
</script>
</head>
<body>
<div id="container">
<table>
<tr>
<th>Name</th><th>Email</th><th>Phone Number</th><th>Address</th><th class="action">Action</th>
</tr>
<?php foreach ($datas as $key=>$row){ ?>
<tr>
<td><?php echo $datas[$key]['name'] ?></td>
<td><?php echo $datas[$key]['email'] ?></td>
<td><?php echo $datas[$key]['phone'] ?></td>
<td><?php echo $datas[$key]['address'] ?></td>
<td>Update</td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>