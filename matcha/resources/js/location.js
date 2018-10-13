$(document).ready(function() {
	if(navigator.geolocation)
	{
		var id_user = encodeURIComponent( $('#my_user_id').val() );
		var b = false;
		$.get("app/model/API/location.php?type=get&id_user=" + id_user, function(location){
			if (location == "null")
				v = true;
			else
			{
				var location = JSON.parse(location);
				var bits = location.last_date.split(/\D/);
				var last_date = new Date(bits[0], --bits[1], bits[2], bits[3], bits[4]);
				var today = new Date($.now());
				if (diff_minutes(last_date, today) >= 30)
					b = true;
			}

			if (b == true || v == true)
				navigator.geolocation.getCurrentPosition(take_location, error_loc);
		});
	}
	else
	{
		/*$.getJSON('http://ipinfo.io', function(data){
    		console.log(data);
		});*/
	}
});

function error_loc(error)
{
	var id_user = encodeURIComponent( $('#my_user_id').val() );
	$.getJSON('http://ip-api.com/json', function(data){
    	$.get("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + data.lat + ","  + data.lon + "&key=AIzaSyDufjdyG9sCYyDe8WqP5RwufFKv_GZm4Mc", function(data_loc){  
        	$.get("app/model/API/location.php?type=set&id_user=" + id_user + "&location=" + data_loc.results[0].formatted_address, function(data_set){  
        	}); 
    	});
    });
}

function diff_minutes(dt2, dt1) 
{

  var diff =(dt2.getTime() - dt1.getTime()) / 1000;
  diff /= 60;
  return Math.abs(Math.round(diff));
  
}

function take_location(position)
{
	var id_user = encodeURIComponent( $('#my_user_id').val() );
	var lat = position.coords.latitude;
	var lng = position.coords.longitude;
	 $.get("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lng + "&key=AIzaSyDufjdyG9sCYyDe8WqP5RwufFKv_GZm4Mc", function(data_loc){
      
        $.get("app/model/API/location.php?type=set&id_user=" + id_user + "&location=" + data_loc.results[0].formatted_address, function(data_set){
        }); 
    });
}