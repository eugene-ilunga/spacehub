"use strict";
var globalCombinedData = [];
$(document).ready(function () {
    var spaces = featuredSpace.concat(space_listings.data).filter(space =>
        space.latitude != null && space.longitude != null && !isNaN(space.latitude) && !isNaN(space.longitude)
    );
    
    // Initialize map for main-map (if it exists)
    if ($('#main-map').length) {
        pushtoMap(globalCombinedData.length ? globalCombinedData : spaces, 'main-map');
    }

    $('#mapModal').on('shown.bs.modal', function () {
        pushtoMap(globalCombinedData.length ? globalCombinedData : spaces, 'modal-main-map');
    });
});

function pushtoMap(spaces, mapContainerId) {
    var jpopup_customOptions = {
        'maxWidth': 'initial',
        'width': 'initial',
        'className': 'popupCustom'
    };

    // Remove existing map if it exists for the container
    if (window.activeMaps && window.activeMaps[mapContainerId]) {
        window.activeMaps[mapContainerId].remove();
    }

    var markers = [];
    var defaultCoordinates = [51.505, -0.09];
    var defaultZoom = 3;

    if ($(`#${mapContainerId}`).length) {
        var map = L.map(mapContainerId, {
            scrollWheelZoom: true,
            tap: !L.Browser.mobile,
            zoom: 13,
            maxZoom: 16,
            fullscreenControl: true,
        });

        // Add Google Maps tile layer
        var tileLayer = L.tileLayer('//{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        // Add custom fullscreen button
        var fullscreenButton = L.control({ position: 'topright' });
        fullscreenButton.onAdd = function () {
            var div = L.DomUtil.create('div', 'leaflet-control-fullscreen leaflet-bar leaflet-control');
            div.innerHTML = '<a class="leaflet-control-fullscreen-button" href="#" title="Toggle Fullscreen"><i class="fas fa-expand"></i></a>';
            L.DomEvent.on(div, 'click', function (e) {
                e.preventDefault();
                var mapContainer = document.getElementById(mapContainerId);
                if (!document.fullscreenElement) {
                    mapContainer.requestFullscreen().catch(err => console.error('Fullscreen request failed:', err));
                } else {
                    document.exitFullscreen();
                }
            });
            return div;
        };
        fullscreenButton.addTo(map);

        var clusters = L.markerClusterGroup();

        if (spaces.length > 0) {
            spaces.forEach(space => {
                // Validate latitude and longitude
                if (space.latitude == null || space.longitude == null || isNaN(space.latitude) || isNaN(space.longitude)) {
                    return;
                }

                var s = '<div class="marker-container"><div class="marker-card"><div class="front face"> <i class="fal fa-' + (space.category_icon || 'map-marker') + '"></i> </div><div class="marker-arrow"></div></div></div>',
                    a = L.marker([space.latitude, space.longitude], {
                        icon: L.divIcon({
                            html: s,
                            className: 'open_steet_map_marker google_marker',
                            iconSize: [40, 46],
                            popupAnchor: [1, -35],
                            iconAnchor: [20, 46],
                        })
                    });

                // Generate the proper URL using the route
                var spaceDetailsLink = spaceDetailsRoute
                    .replace(':slug', space.slug || '')
                    .replace(':id', space.space_id || '');

                var locationString = '';
                if (space.city_name !== null) {
                    locationString += space.city_name;
                }
                if (space.state_name !== null) {
                    if (locationString !== '') {
                        locationString += ', ';
                    }
                    locationString += space.state_name;
                }
                if (space.country_name !== null) {
                    if (locationString !== '') {
                        locationString += ', ';
                    }
                    locationString += space.country_name;
                }

                a.bindPopup('<div class="product-default"> ' +
                    '<figure class="product_img"> <a href="' + spaceDetailsLink + '" class="lazy-container ratio ratio-2-3"> <img class="lazyload" data-src="assets/img/spaces/thumbnail-images/' + (space.image || '') + '" alt="' + (space.title || 'Space') + '"> </a></figure><div class="product_details p-10"><h6 class="product-title lc-2"><a href="' + spaceDetailsLink + '">' + (space.title || 'Untitled Space') + '</a></h6><span class="product-location icon-start"><i class="fal fa-map-marker-alt"></i> ' + locationString + '</span></div></div>', jpopup_customOptions);

                clusters.addLayer(a);
                markers.push(a);
            });

            if (markers.length) {
                map.addLayer(clusters);
                var validLatLngs = markers
                    .filter(marker => marker.getLatLng() && !isNaN(marker.getLatLng().lat) && !isNaN(marker.getLatLng().lng))
                    .map(marker => marker.getLatLng());

                if (validLatLngs.length > 0) {
                    var bounds = L.latLngBounds(validLatLngs);
                    map.fitBounds(bounds);
                } else {
                    console.warn('No valid markers to display, setting default view');
                    map.setView(defaultCoordinates, defaultZoom);
                }
            } else {
                console.warn('No markers to display, setting default view');
                map.setView(defaultCoordinates, defaultZoom);
            }
        } else {
            console.warn('No spaces provided, setting default view');
            map.setView(defaultCoordinates, defaultZoom);
        }

        // Store the map instance
        if (!window.activeMaps) {
            window.activeMaps = {};
        }
        window.activeMaps[mapContainerId] = map;

        // Invalidate map size to ensure proper rendering in modal
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    }
}




