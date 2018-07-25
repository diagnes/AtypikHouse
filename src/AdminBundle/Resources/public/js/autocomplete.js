/**
 * Created by Nefast on 17/07/2018.
 */

var input = document.getElementById('google-autocomplete');

var placeSearch, autocomplete;

var componentForm = {
    streetNumber: 'short_name',
    address: 'long_name',
    city: 'long_name',
    postalcode: 'short_name',
    country: 'long_name',
};

function initAutocomplete() {

    $('.google-autocomplete').each(function (idx, elt) {
        var options = {
            types: $(elt).data("types")
        };
        autocomplete = new google.maps.places.Autocomplete(
            elt,
            options
        );
        autocomplete.addListener('place_changed', function () {
            fillInAddress(elt);
        });
    });
}

function fillInAddress(target) {
    var place = autocomplete.getPlace();
    var targetBlock = '.'+ $(target).data('target');

    if (undefined != $(target).data('target')){
        $(targetBlock + ' [data-entry="lat"]').val(place.geometry.location.lat());
        $(targetBlock + ' [data-entry="lng"]').val(place.geometry.location.lng());
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];

            var cible = targetBlock + ' [data-entry="' + addressType + '"]';
            if ($(cible).length){
                $(cible).val(place.address_components[i].long_name)
            }
        }
    }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}