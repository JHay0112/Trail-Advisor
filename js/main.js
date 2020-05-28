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

    content = document.getElementById("content");
    content.style.position = "relative";

    if (window.pageYOffset >= navOffset) {
        nav.classList.add("stick")
        if(nav.classList.contains("responsive")) {
            content.style.top = nav.scrollHeight + "px";
        } else {
            content.style.top = "60px";
        }
    } else {
        nav.classList.remove("stick");
        content.style.top = "";
    }
}

// toggleResponsiveNav
//
// Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon
function toggleResponsiveNav() {

    navAnchors = document.querySelectorAll(".anchor");

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
function genTrailMap(zoom = 12, select = false, lat_id = "lat", lng_id = "lng", map_id = "trail-map") {
    
    var lat = document.getElementById(lat_id);
    var lng = document.getElementById(lng_id);

    var tileLayer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> Contributors'
    });

    var map = new L.Map(map_id, {
        'center': [lat.value, lng.value],
        'zoom': zoom,
        'layers': [tileLayer]
    });

    var marker = L.marker([lat.value, lng.value], {
        draggable: select,
        zIndexOffset: 100000 // Had to make this value very high to stop the marker occasionally dissapearing ever
    }).addTo(map);

    if(select) {

        function updateLatLng(reverse = false) {
            if(!reverse) {
                marker.setLatLng([lat.value, lng.value]);
                map.panTo([lat.value, lng.value]);
            } else {
                lat.value = marker.getLatLng().wrap().lat;
                lng.value = marker.getLatLng().wrap().lng;
                map.panTo([marker.getLatLng().lat, marker.getLatLng().lng]);
            }
        }

        marker.on('dragend', function (e) {
            updateLatLng(true);
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateLatLng(true);
        });

        lat.addEventListener("change", updateLatLng);
        lng.addEventListener("change", updateLatLng);
    }

}

// Event listeners

// When the user scrolls the page, execute stickyNav function
window.onscroll = function() {stickyNav()};