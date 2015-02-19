(function($) {

	$.fn.scrollPagination = function(options) {
		
		var settings = { // default setting 
			nop     : 2, 
			offset  : 0, 
			error   : 'No More Posts!',
			delay   : 500,
			clone	: ''
		}
		
		if(options) {
			$.extend(settings, options);
		}
		
		// For each so that we keep chainability.
		return this.each(function() {		
			
			// Some variables 
			$this = $(this);
			$settings = settings;
			var offset = $settings.offset+$settings.nop;
			var start = $settings.nop;
			var busy = false; 
			var finish = false;
			var $clone = $settings.clone;
			
			function getData() {
				
				$.ajax({
					url:"index3.php",
					success:function(result){
						
						var data = JSON.parse(result);
						if(offset>data.length){
							console.log("Impossible Load, because data is empty");
							console.log("Offset is:"+offset+" and Data size is:"+data.length);
							offset = offset - $settings.nop; // return to current offset
							var gapSize = data.length - offset; // get gap of size
							offset = offset + gapSize;
							console.log("Now Offset is:"+offset);
							finish = true;
						}	
						for(var i=start;i<offset;i++){ 
							$clone.text(data[i].nama);
							$clone.appendTo("article:eq(1)");
							console.log(data[i].nama);
							$clone = $clone.clone();
						}
						
						start = start + $settings.offset;
						offset = offset + $settings.offset; 
						busy = false;
						
					},
					error:function(result){
						console.log("Err: "+result);
					}
				});
					
			}	
			
			getData(); // Run function initially
			
			
			$(window).scroll(function() {
				if($(window).scrollTop() + $(window).height() > $this.height() && !busy) {
					busy = true;
					setTimeout(function() {
						if(!finish){getData();}
					}, $settings.delay);
						
				}	
			});
			
			
		});
	}

})(jQuery);
