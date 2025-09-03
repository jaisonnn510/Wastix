<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Location Tracking</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        #map-container {
            width: 100%;
            height: 400px;
            margin: 20px auto;
        }
        @media screen and (max-width: 600px) {
            #map-container {
                height: 300px;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Real-Time Location Tracker</h1>
</header>
<div id="map-container"></div>
<div id="city-name"></div>
<div id="address"></div>

<script>
    // Initialize variables for the map and marker
    let map, userMarker, accuracyCircle;

    // Coordinates for Canara Engineering College, Benjanapadav
    const collegeLocation = {
        lat: 12.9265,
        lng: 75.6421,
    };

    // Initialize the map
    function initMap() {
        const mapContainer = document.getElementById("map-container");

        // Initialize the map at the college location
        map = L.map(mapContainer).setView(collegeLocation, 15);

        // Add the OpenStreetMap tile layer
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            maxZoom: 18,
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(map);

        // Add a marker for Canara Engineering College
        L.marker(collegeLocation)
            .addTo(map)
            .bindPopup("<b>Canara Engineering College</b><br>Benjanapadav")
            .openPopup();

        // Check if geolocation is supported
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by your browser.");
            return;
        }

        // Start real-time location tracking
        navigator.geolocation.watchPosition(
            (position) => {
                const userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                // Update marker and map position
                if (!userMarker) {
                    // Create the marker and accuracy circle for the first time
                    userMarker = L.marker(userLocation).addTo(map);
                    accuracyCircle = L.circle(userLocation, {
                        radius: position.coords.accuracy,
                        color: "#3388ff",
                        fillColor: "#3388ff",
                        fillOpacity: 0.2,
                    }).addTo(map);
                } else {
                    // Update the marker and accuracy circle
                    userMarker.setLatLng(userLocation);
                    accuracyCircle.setLatLng(userLocation);
                    accuracyCircle.setRadius(position.coords.accuracy);
                }

                // Center the map on the user's location
                map.setView(userLocation, map.getZoom());

                // Retrieve and display the address using reverse geocoding
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${userLocation.lat}&lon=${userLocation.lng}`;
                fetch(url)
                    .then((response) => response.json())
                    .then((data) => {
                        const cityName = data.address.city || data.address.town || "your location";
                        document.getElementById("city-name").innerHTML = `You are in ${cityName}`;
                        document.getElementById("address").innerHTML = `You are at ${data.display_name}`;
                    })
                    .catch(() => {
                        console.error("Error retrieving location details.");
                    });
            },
            (error) => {
                alert("Unable to retrieve location: " + error.message);
            },
            {
                enableHighAccuracy: true, // Use GPS for better accuracy
                maximumAge: 0, // Prevent cached location
                timeout: 10000, // Timeout after 10 seconds
            }
        );
    }

    // Initialize the map
    initMap();
</script>
</body>
</html>
