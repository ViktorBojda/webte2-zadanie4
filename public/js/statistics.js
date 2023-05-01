function showLocaleStatsModal(countryCode) {
    $('#modal-title').text(countryCode);
    const tableBody = $('#modal-table-body');
    tableBody.empty();

    $.each(localeData[countryCode], (_, value) => {
        const row = $('<tr>');
        row.append($('<td>').text(value.locale));
        row.append($('<td>').text(value.count));
        tableBody.append(row);
    });

    $('#modal').modal('show');
}

const map = L.map('map');
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const markers = [];
$.each(visitorData, (_, value) => {
    markers.push(L.marker([value.latitude, value.longitude]).addTo(map));
});
const group = L.featureGroup(markers).addTo(map);
map.fitBounds(group.getBounds());