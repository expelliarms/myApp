<?php 

session_start(); 
$user_id = $_SESSION["userId"];

?>
<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .elevatewhite
		{
		    background: #FFF; 
		    border-width: 1px;
		    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2), 0 3px 10px 0 rgba(0, 0, 0, 0.19);
		    padding: 20px;
		}
	.right 
	{
		text-align: right;
	}
	body {
		background-color: #F3E2A9;
	}
    </style>
    <script>
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

      var map;
      var infowindow;

      // function initMap() {
        
      // }

      function createMarker(place) {
        var placeLoc = place.geometry.location;
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location
        });

        google.maps.event.addListener(marker, 'click', function() {
          infowindow.setContent(place.name);
          infowindow.open(map, this);
        });
      }
    </script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<img height="100px" src="vodafone-logo.png">
			</div>
			<div class="col-md-8">
				<br>
				<div style="padding-top: 13px">
					<ul class="nav nav-pills">
					  <li class="nav-item">
					    <a style="background-color: red;" class="nav-link active" href="recommendations"><i class="fas fa-lightbulb"></i> Recommended Offers</a>
					  </li>
					  <li class="nav-item">
					    <a style="color: red;" class="nav-link" href="search-offers"><i class="fas fa-search"></i> Search Offers</a>
					  </li>
					  <li class="nav-item">
					    <a style="color: red;" class="nav-link" href="history"><i class="fas fa-history"></i> Offer History</a>
					  </li>
					  <li class="nav-item">
					    <a style="color: red;" class="nav-link" href="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
					  </li>
					</ul>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-4">
				<p id="recommendationResults">
				</p>
			</div>
			<div class="col-md-8">
				<span id="recommendationNearbyLocationsTitle"></span>
				<div class="row">
					<div class="col-md-6">
						<p id="recommendationNearbyLocations">
						</p>
					</div>
					<div class="col-md-6">
						<div id="map" style="width:100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<hr>
	</div>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTijCnxbOhfHX2_8KLR7Maz_zxCoW9mrI&libraries=places"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="app.js"></script>
</html>

<script>

function get_recs() {

	user_id = "<?php echo $user_id; ?>";
 
	$.ajax(
	{
	  url: "get_recs.py",
	  dataType: "json",
	  type: "POST",
	  async: false,
	  data:
	  {
	  	user_id: user_id
	  },
	  success: function(json)
	  {
	   if(json["future"].length > 0) {
	      var recommendationResults = "<h4><i class='fas fa-tags'></i>&nbsp; Recommended Offers</h4><hr>";
	      for(i=0;i<json["future"].length && i < 4;i++)
	      {
	        recommendationResults += "<div class='card elevatewhite'>";
	        recommendationResults += "<i class='fas fa-tag'></i> " +  json["future"][i] + "<button><a style='color: black;' href='redeem.php?offer=" + json["future"][i] + "&user_id=" + user_id + "'>Redeem</a></button></div><br>";
	      }
	      document.getElementById("recommendationResults").innerHTML = recommendationResults;

	      var my_lat = 0;
	      var my_lng = 0;
	      $.ajax(
	      {
	        url: "https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyCTijCnxbOhfHX2_8KLR7Maz_zxCoW9mrI",
	        dataType: "json",
	        type: "POST",
	        async: false,
	        success: function(json)
	        {
	          my_lat = json.location.lat;
	          my_lng = json.location.lng;
	          console.log(json.accuracy);
	        },
	        error : function()
	        {
	          alert("Some error occurred");
	        }
	      });
	      console.log(my_lat);
	      console.log(my_lng);
	      var pyrmont = {lat: my_lat, lng: my_lng};

	      map = new google.maps.Map(document.getElementById('map'), {
	        center: pyrmont,
	        zoom: 12
	      });

	      var mapQuery = "";

	      for(i=0;i<json["future"].length && i<4; i++) {
		      if(json["future"][i].toLowerCase().includes("pizza hut")) {
		      	mapQuery = "pizza hut";
		      }
		      else if(json["future"][i].toLowerCase().includes("faasos")) {
		      	mapQuery = "faasos";
		      }
		      else if(json["future"][i].toLowerCase().includes("vlcc")) {
		      	mapQuery = "vlcc";
		      }
		      else if(json["future"][i].toLowerCase().includes("enrich")) {
		      	mapQuery = "enrich";
		      }
		      else if(json["future"][i].toLowerCase().includes("baskin robbins")) {
                mapQuery = "baskin robbins";
              }
              else if(json["future"][i].toLowerCase().includes("vodafone")) {
                mapQuery = "vodafone";
              }

              if(mapQuery != "") {

			      infowindow = new google.maps.InfoWindow();
			      var service = new google.maps.places.PlacesService(map);
			      service.textSearch({
			        location: pyrmont,
			        radius: 500,
			        query: mapQuery,
			      }, callbackRecommendations);
			  }
		  }
	    }
	    else {
	      var recommendationResults = "No Available Offers";
	      document.getElementById("recommendationResults").innerHTML = recommendationResults;
	    }
	  },
	  error : function()
	  {
	    alert("Some error occurred");
	  }
	});
}

function callbackRecommendations(results, status) {
      	console.log(results);
      	document.getElementById("recommendationNearbyLocationsTitle").innerHTML = "<h4><i class='fas fa-map-marker-alt'></i>&nbsp; Nearby Places to Avail</h4><hr>";
      	var recommendationNearbyLocations = "";
      	recommendationNearbyLocations += "<div class='card elevatewhite'>";
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          for (var i = 0; i < results.length && i < 3; i++) {
            createMarker(results[i]);
            recommendationNearbyLocations += "<i class='fas fa-map-marker-alt'></i> ";
            recommendationNearbyLocations += results[i]["name"] 
            recommendationNearbyLocations += ", " 
            recommendationNearbyLocations += results[i]["formatted_address"] 
            recommendationNearbyLocations += "<br><br>";
          }
          recommendationNearbyLocations += "</div>"
          document.getElementById("recommendationNearbyLocations").innerHTML = recommendationNearbyLocations;
        }
      }

document.addEventListener('DOMContentLoaded', function() {
   get_recs();
}, false);
</script>


