
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

      function callback(results, status) {
      	console.log(results);
      	document.getElementById("nearbyLocationsTitle").innerHTML = "<h4><i class='fas fa-map-marker-alt'></i>&nbsp; Nearby Places to Avail</h4><hr>";
      	var nearbyLocations = "";
      	nearbyLocations += "<div class='card elevatewhite'>";
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          for (var i = 0; i < results.length && i < 3; i++) {
            createMarker(results[i]);
            nearbyLocations += "<i class='fas fa-map-marker-alt'></i> ";
            nearbyLocations += results[i]["name"] 
            nearbyLocations += ", " 
            nearbyLocations += results[i]["formatted_address"] 
            nearbyLocations += "<br><br>";
          }
          nearbyLocations += "</div>"
          document.getElementById("nearbyLocations").innerHTML = nearbyLocations;
        }
      }

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
					    <a style="color: red;" class="nav-link" href="recommendations"><i class="fas fa-lightbulb"></i> Recommended Offers</a>
					  </li>
					  <li class="nav-item">
					    <a style="background-color: red;" class="nav-link active" href="search-offers"><i class="fas fa-search"></i> Search Offers</a>
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
		<input style="padding:3px;" id="note-textarea" placeholder="Say something..."></input>
		<button id="start-record-btn" title="Start Recording">Start Mic</button>
		<button id="pause-record-btn" title="Pause Recording">Stop Mic</button>
		<br><br><br>
		<div class="row">
			<div class="col-md-4">
				<p id="searchResults">
				</p>
			</div>
			<div class="col-md-8">
				<span id="nearbyLocationsTitle"></span>
				<div class="row">
					<div class="col-md-6">
						<p id="nearbyLocations">
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


