document.addEventListener('DOMContentLoaded', function() {
    let map;
    let marker;

    const mapPopup = document.getElementById('map-popup');
    const openMapBtn = document.getElementById('open-map-btn');
    const closeMapBtn = document.getElementById('close-map');
    const confirmBtn = document.getElementById('confirm-location');

    if (openMapBtn) {
        openMapBtn.addEventListener('click', function() {
            mapPopup.style.display = 'flex';

            if (!map) {
                map = L.map('map').setView([-34.6612, -58.5673], 11);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;

                    if (marker) {
                        marker.setLatLng(e.latlng);
                    } else {
                        marker = L.marker(e.latlng).addTo(map);
                    }

                    document.getElementById('latitud').value = lat;
                    document.getElementById('longitud').value = lng;

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.address) {
                                const pais = data.address.country || "";

                                const ciudad = data.address.city || data.address.town || data.address.suburb || data.address.village || "";

                                if (pais) {
                                    document.querySelector("input[name='pais']").value = pais;
                                }
                                if (ciudad) {
                                    document.querySelector("input[name='ciudad']").value = ciudad;
                                }
                            }
                        })
                        .catch(error => console.error("Error al obtener la ubicación:", error));
                });
            }

            setTimeout(function() {
                map.invalidateSize();
            }, 200);
        });
    }

    if (closeMapBtn) {
        closeMapBtn.addEventListener('click', function() {
            mapPopup.style.display = 'none';
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const lat = document.getElementById('latitud').value;
            if (!lat) {
                alert("Por favor, seleccioná un punto en el mapa antes de confirmar.");
            } else {
                mapPopup.style.display = 'none';
            }
        });
    }
});