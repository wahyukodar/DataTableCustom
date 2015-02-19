<html>
<head>
<title>Tes</title>
<style>
body{
	color:#fff;
	background:#433443;
}
.content{
	height:300px;
	background:#455476;
	border-bottom:1px solid #9898a8;
}
</style>
<script src="jquery.js"></script>
<script src="pagingscroll.js"></script>
</head>
<body>
	<div id="container">
		<section id="wrap">
			<article>
				<input type="text" id="input" />
			</article>
			<article>
				<div class="content">
					Konten 1
				</div>
				
				<div class="content">
					Konten 2
				</div>
				
				<div class="content">
					Konten 3
				</div>
				
				<div class="content">
					Konten 4
				</div>
			</article>
		</section>
	</div>
<script>
$("document").ready(function(){
	$("#input").keyup(function(){
		var $clone = $(".content:eq(0)").clone();
		$(".content").remove();
			
		$('#container').scrollPagination({
			nop     : 7, // The number of posts per scroll to be loaded
			offset  : 4, // Initial offset, begins at 0 in this case
			error   : 'No More Posts!', // When the user reaches the end this is the message that is
							// displayed. You can change this if you want.
			delay   : 500, // When you scroll down the posts will load after a delayed amount of time.
			   // This is mainly for usability concerns. You can alter this as you see fit
			scroll  : true, // The main bit, if set to false posts will not load as the user scrolls. 
			   // but will still load if the user clicks.
			clone	: $clone
		});
	});
	
	$(".content").live("click",function(){
		console.log("Clicked");
	});
	
	
});
</script>
</body>
</html>