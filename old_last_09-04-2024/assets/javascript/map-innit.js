try {
	var millIcon = L.icon({
    iconUrl: 'assets/images/hot-logo-pin.png',
        iconSize:     [75, 89],
        shadowSize:   [0, 0],
        iconAnchor:   [67, 74],
        shadowAnchor: [0, 0],
        popupAnchor:  [-0, -6]
});

// create a map in the "map" div, set the view to a given place and zoom
var map = L.map('map').setView([52.1984856,0.1198099], 14);

// add an OpenStreetMap tile layer
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    maxZoom: 18,
    color: '#222',
    fillOpacity: '0.2',
    id: 'mapbox.light',
    accessToken: 'pk.eyJ1IjoiZ2Vvcmdlc3ltYmlhbiIsImEiOiJjampvZzlpanYyOG5pM3ZuenVkbnN1dnZ5In0.M_7ySs2e9JBzm-rLunXl3Q'
}).addTo(map);

// add a marker in the given location, attach some popup content to it and open the popup
L.marker([52.1984856,0.1198099], {icon: millIcon}).addTo(map);



// create a map in the "map" div, set the view to a given place and zoom
var map2 = L.map('map2').setView([52.200058,0.1362663], 14);

// add an OpenStreetMap tile layer
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    maxZoom: 18,
    color: '#222',
    fillOpacity: '0.2',
    id: 'mapbox.light',
    accessToken: 'pk.eyJ1IjoiZ2Vvcmdlc3ltYmlhbiIsImEiOiJjampvZzlpanYyOG5pM3ZuenVkbnN1dnZ5In0.M_7ySs2e9JBzm-rLunXl3Q'
}).addTo(map2);

L.marker([52.200058,0.1362663], {icon: millIcon}).addTo(map2);


// create a map in the "map" div, set the view to a given place and zoom
var map3 = L.map('map3').setView([52.09970456,0.0370568], 14);

// add an OpenStreetMap tile layer
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    maxZoom: 18,
    color: '#222',
    fillOpacity: '0.2',
    id: 'mapbox.light',
    accessToken: 'pk.eyJ1IjoiZ2Vvcmdlc3ltYmlhbiIsImEiOiJjampvZzlpanYyOG5pM3ZuenVkbnN1dnZ5In0.M_7ySs2e9JBzm-rLunXl3Q'
}).addTo(map3);

L.marker([52.09970456,0.0370568], {icon: millIcon}).addTo(map3);
} catch(error) {
	console.warn(error);
}
