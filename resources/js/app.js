import * as bootstrap from 'bootstrap';
import L from 'leaflet';
import { Html5Qrcode } from 'html5-qrcode';

window.bootstrap = bootstrap;

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('../../node_modules/leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
    iconUrl: new URL('../../node_modules/leaflet/dist/images/marker-icon.png', import.meta.url).href,
    shadowUrl: new URL('../../node_modules/leaflet/dist/images/marker-shadow.png', import.meta.url).href,
});

const safeJsonParse = (value, fallback = []) => {
    try {
        return value ? JSON.parse(value) : fallback;
    } catch {
        return fallback;
    }
};

const routeSummaryText = (fromLabel, target) =>
    `Route from ${fromLabel} to Stall ${target.stall_number} in ${target.zone_section}, Floor ${target.floor_level}.`;

const drawRoute = (map, origin, target, summaryElement, fromLabel) => {
    L.polyline(
        [
            [origin.latitude, origin.longitude],
            [target.latitude, target.longitude],
        ],
        {
            color: '#2e7d32',
            weight: 5,
            dashArray: '8 8',
        },
    ).addTo(map);

    if (summaryElement) {
        summaryElement.textContent = routeSummaryText(fromLabel, target);
    }
};

const initLeafletMaps = () => {
    document.querySelectorAll('[data-map="leaflet"]').forEach((element) => {
        const markers = safeJsonParse(element.dataset.markers);

        if (!markers.length) {
            return;
        }

        const selectedCode = element.dataset.targetCode || '';
        const summaryElement = element.parentElement?.querySelector('[data-route-summary]') || null;
        const entryLat = Number(element.dataset.entryLat || markers[0].latitude);
        const entryLng = Number(element.dataset.entryLng || markers[0].longitude);
        const useGeolocation = element.dataset.useGeolocation === 'true';

        const map = L.map(element).setView([markers[0].latitude, markers[0].longitude], 18);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const bounds = L.latLngBounds(markers.map((marker) => [marker.latitude, marker.longitude]));
        map.fitBounds(bounds.pad(0.12));

        let selectedMarker = null;
        let selectedPayload = null;

        markers.forEach((marker) => {
            const popupContent = `
                <div class="small">
                    <strong>Stall ${marker.stall_number}</strong><br>
                    ${marker.vendor_name ?? 'Open stall location'}<br>
                    ${marker.zone_section}, Floor ${marker.floor_level}
                </div>
            `;

            const leafletMarker = L.marker([marker.latitude, marker.longitude]).addTo(map).bindPopup(popupContent);

            if (marker.qr_location_code === selectedCode) {
                selectedMarker = leafletMarker;
                selectedPayload = marker;
            }
        });

        if (selectedMarker && selectedPayload) {
            selectedMarker.openPopup();

            if (useGeolocation && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const origin = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                        };

                        L.circleMarker([origin.latitude, origin.longitude], {
                            radius: 8,
                            color: '#1b5e20',
                            fillColor: '#81c784',
                            fillOpacity: 1,
                        }).addTo(map).bindPopup('Your current location');

                        drawRoute(map, origin, selectedPayload, summaryElement, 'your current location');
                    },
                    () => {
                        drawRoute(
                            map,
                            { latitude: entryLat, longitude: entryLng },
                            selectedPayload,
                            summaryElement,
                            'the market entrance',
                        );
                    },
                    { enableHighAccuracy: true, timeout: 5000 },
                );
            } else {
                drawRoute(
                    map,
                    { latitude: entryLat, longitude: entryLng },
                    selectedPayload,
                    summaryElement,
                    'the market entrance',
                );
            }
        }
    });
};

const initQrScanners = () => {
    document.querySelectorAll('[data-qr-scanner]').forEach(async (element, index) => {
        if (!element.id) {
            element.id = `qr-scanner-${index + 1}`;
        }

        const resolveEndpoint = element.dataset.resolveEndpoint;
        const resultTarget = document.querySelector(element.dataset.resultTarget || '');

        if (!resolveEndpoint) {
            return;
        }

        try {
            const cameras = await Html5Qrcode.getCameras();

            if (!cameras.length) {
                if (resultTarget) {
                    resultTarget.textContent = 'No camera detected on this device.';
                }
                return;
            }

            const scanner = new Html5Qrcode(element.id);

            await scanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 220, height: 220 } },
                async (decodedText) => {
                    await scanner.stop();

                    if (resultTarget) {
                        resultTarget.textContent = `Scanned: ${decodedText}`;
                    }

                    try {
                        const maybeUrl = new URL(decodedText);
                        window.location.href = maybeUrl.toString();
                        return;
                    } catch {
                        // Resolve through the API when the payload is not a direct URL.
                    }

                    const response = await fetch(resolveEndpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                        },
                        body: JSON.stringify({ code: decodedText }),
                    });

                    const payload = await response.json();

                    if (payload.redirect_url) {
                        window.location.href = payload.redirect_url;
                        return;
                    }

                    if (resultTarget) {
                        resultTarget.textContent = payload.message || 'Unable to resolve QR code.';
                    }
                },
                () => {},
            );
        } catch (error) {
            if (resultTarget) {
                resultTarget.textContent = 'Camera access is unavailable in this browser session.';
            }
            console.error(error);
        }
    });
};

const initDashboardSidebar = () => {
    const layout = document.querySelector('[data-dashboard-layout]');

    if (!layout) {
        return;
    }

    const toggleButtons = document.querySelectorAll('[data-dashboard-sidebar-toggle]');
    const closeButtons = document.querySelectorAll('[data-dashboard-sidebar-close]');
    const desktopQuery = window.matchMedia('(min-width: 992px)');

    const setMobileSidebarState = (isOpen) => {
        document.body.classList.toggle('sidebar-open', isOpen);
        toggleButtons.forEach((button) => {
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    };

    toggleButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (desktopQuery.matches) {
                document.body.classList.toggle('sidebar-collapsed');
                button.setAttribute('aria-expanded', document.body.classList.contains('sidebar-collapsed') ? 'false' : 'true');
                return;
            }

            setMobileSidebarState(!document.body.classList.contains('sidebar-open'));
        });
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => setMobileSidebarState(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setMobileSidebarState(false);
        }
    });

    desktopQuery.addEventListener('change', (event) => {
        if (event.matches) {
            setMobileSidebarState(false);
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initDashboardSidebar();
    initLeafletMaps();
    initQrScanners();
});
