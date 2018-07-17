/**
 * Created by Nefast on 13/07/2018.
 */

$('.ionrange-input').each(function (idx, elt) {
    var options = $(elt).data();
    $(elt).ionRangeSlider(options);
});

$('.chosen-select').each(function (idx, elt) {
    var options = $(elt).data();
    $(elt).chosen(options);
});

/* On click */
$(document).ready(function () {
    $('#page-wrapper').on('click', '.add-prototype', function () {
        var prototype = $(this).data('prototype');
        var target = $(this).data('target');
        var index = $(this).data('index');

        var newForm = prototype;
        newForm = newForm.replace(/__name__/g, index);
        $(this).data('index', index + 1);
        $('.' + target).append(newForm);
    })

});

