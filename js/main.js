/*

    js/main.js

    Author: Jordan Hay
    Version: 1.0

    Description: 

        JS to be used across the site.

    Other Notes:

        N/a


*/

// Globals
var nav = document.getElementById("nav"); // Nav global
var navOffset = nav.offsetTop; // Get the offset position of the nav

// confirmAction
//
// Confirms the user wants to go through with the action they are taking
//
// confirmMessage - str - Message to be displayed to user
// redirect - str - URL of page to be redirected to, normally requires a token value as well
//
function confirmAction(confirmMessage, redirect) {
    // If user confirms
    if(confirm(confirmMessage)) {
        // Redirect to specified page
        window.location.assign(redirect);
    }
}

// stickyNav
//
// Add the sticky class to the nav when you reach its scroll position. Remove "sticky" when you leave the scroll position
//
function stickyNav() {

    var content = document.getElementById("content");
    content.style.position = "relative";

    if (window.pageYOffset >= navOffset) {
        nav.classList.add("stick")
        if(nav.classList.contains("responsive")) {
            content.style.top = nav.scrollHeight + "px";
        } else {
            content.style.top = "60px";
            content.style.paddingBottom = "";
        }
    } else {
        nav.classList.remove("stick");
        content.style.top = "";
        content.style.paddingBottom = "60px";
    }
}

// toggleResponsiveNav
//
// Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon
function toggleResponsiveNav() {

    var navAnchors = document.querySelectorAll(".anchor");

    if (!nav.classList.contains("responsive")) {
        nav.classList.add("responsive");
        nav.style.height = nav.scrollHeight + "px";
    } else {
        nav.classList.remove("responsive");
        nav.style.height = "60px";
    }

    // Call sticky nav function
    stickyNav();
}

// Leaflet.js
// Script for leaflet map to update from coords and vice versa on createtrail.php
// Adapted from: https://gist.github.com/answerquest/03ade545b071b3e5ea4e
function genTrailMap(zoom = 12, select = false, additional_markers = [], lat_id = "lat", lng_id = "lng", map_id = "trail-map") {
    
    // Update map from form lat/lng
    function updateFromLatLng() {
        // This bounds the lng value to somewhere within -180 to +180 degrees
        lng.value = L.latLng(lat.value, lng.value).wrap().lng;
        
        // Check if the lat value is within the bounds of -90 to +90
        if(lat.value < -90) {
            // If less than -90 then set to -90
            lat.value = -90;
        } else if(lat.value > 90) {
            // If greater than 90 then set to 90
            lat.value = 90;
        }
        
        // Set marker position
        marker.setLatLng([lat.value, lng.value]);
        // Pan to it
        map.panTo([lat.value, lng.value]);
    }
    
    // Find lat and long elements
    var lat = document.getElementById(lat_id);
    var lng = document.getElementById(lng_id);
    
    // Attributes of location icons
    const locationIcon = L.divIcon({
        className: "",
        iconAnchor: [0, 24],
        labelAnchor: [-6, 0],
        popupAnchor: [0, -36],
        html: "<span class='location-icon'></span>"
    });
    
    // Attributes of trail icons
    const trailIcon = L.divIcon({
        className: "",
        iconAnchor: [0, 24],
        labelAnchor: [-6, 0],
        popupAnchor: [0, -36],
        html: "<span class='trail-icon'></span>"
    });

    // Tile layer, supplied by OSM
    var tileLayer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors',
        noWrap: true
    });

    // Initialize maps
    var map = new L.Map(map_id, {
        'center': [lat.value, lng.value], // Center on values of lat and long elements
        'zoom': zoom, // Define zoom as per defined attributes 
        'layers': [tileLayer] // Add tile layer
    });

    var marker = L.marker([lat.value, lng.value], {
        draggable: select, // Allow marker to be dragable if it must be
        icon: locationIcon, // Get location icon attributes
        title: "Location",
        zIndexOffset: 100000 // Had to make this value very high to stop the marker occasionally dissapearing ever
    }).addTo(map);
    
    // Generate new markers as per additional markers dictionary
    for(var i = 0; i < additional_markers.length; i++) {
        var newmarker = additional_markers[i];
        
        // Insert marker
        L.marker([newmarker[1], newmarker[2]], {
            icon: trailIcon, // Get trail icon attributes
            zIndexOffset: 100000,
            title: newmarker[0] // Set title
        }).addTo(map);
    }

    // If trail must be selectable then we have to add some more things
    if(select) {

        // Update lat long when marker is dragged
        marker.on('dragend', function (e) {
            // Update map from marker lat/lng
            lat.value = marker.getLatLng().wrap().lat;
            lng.value = marker.getLatLng().wrap().lng;
            marker.setLatLng([lat.value, lng.value]); // This moves the marker back to the normal map
            map.panTo([lat.value, lng.value]);
        });

        // Update lat long and marker when map is clicked
        map.on('click', function (e) {
            // Update map from marker lat/lng
            marker.setLatLng(e.latlng);
            lat.value = marker.getLatLng().wrap().lat;
            lng.value = marker.getLatLng().wrap().lng;
            marker.setLatLng([lat.value, lng.value]); // This moves the marker back to the normal map
            map.panTo([lat.value, lng.value]);
        });

        // Listen to form elements for changes
        lat.addEventListener("change", updateFromLatLng);
        lng.addEventListener("change", updateFromLatLng);
    }

}

// Event listeners

// When the user scrolls the page, execute stickyNav function
window.onscroll = function() {stickyNav()};