/**
 * Created by Nefast on 13/07/2018.
 */
$('form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

$('.ionrange-input').each(function (idx, elt) {
    var options = $(elt).data();
    $(elt).ionRangeSlider(options);
});

$('.chosen-select').each(function (idx, elt) {
    var options = $(elt).data();
    $(elt).chosen(options);
});

$('.date-picker').each(function (idx, elt) {
    var options = {
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        format: 'dd/mm/yyyy',
        autoclose: true
    };
    $(elt).datepicker(options);
});

function getTodayDateRange() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
    if(dd<10){ dd='0'+dd }
    if(mm<10){ mm='0'+mm }
    return dd+'/'+mm+'/'+yyyy;
}
function formatDate(dateString) {
    var dateParts = dateString.split("/");
    return new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
}

function isValidReservation(elt, unavailable, sDate, eDate) {

    $($(elt).data("error")).fadeOut();
    var undis;
    var startDate = formatDate(sDate);
    var endDate = formatDate(eDate);
    var valid = true;

    $(unavailable).each(function (idx, date) {
        undis = formatDate(date);
        if((undis.getTime() >= startDate.getTime()) && (undis.getTime() <= endDate.getTime())) {
            $($(elt).data("error")).html("House unavailble " + date +" can't have undisponibility during reservation stay");
            $($(elt).data("error")).fadeIn();
            valid = false;
            return false;
        }
    });

    return valid;
}

$('.date-range-picker').each(function (idx, elt) {
    var unavailableHouse = $(elt).data('unavailablehouse');
    var response = $.getJSON(
        Routing.generate('atypikhouse_reservation_all_undisponibility', { slug: unavailableHouse})
    ).done(function(data){
        var unavailable = data;
        var today = getTodayDateRange();
        var startDate = $('.' + $(elt).data('startdate'));
        var endDate = $('.' + $(elt).data('enddate'));

        $(elt).daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            drops: "up",
            startDate: $(startDate).val(),
            endDate: $(endDate).val(),
            minDate: today,
            isInvalidDate: function(date){
                var formatted = date.format('DD/MM/YYYY');
                return unavailable.indexOf(formatted) > -1;
            }
        });

        $(elt).on('apply.daterangepicker', function(ev, picker) {
            if (isValidReservation(elt, unavailable, picker.startDate.format('DD/MM/YYYY'), picker.endDate.format('DD/MM/YYYY'))) {
                $(startDate).val(picker.startDate.format('DD/MM/YYYY'));
                $(endDate).val(picker.endDate.format('DD/MM/YYYY'));
            }
        });
    });
});

/* On click */
$(document).ready(function () {
    $('#page-wrapper')
        .on('click', '.add-prototype', function () {
            var prototype = $(this).data('prototype');
            var target = $(this).data('target');
            var index = $(this).data('index');

            var newForm = prototype;
            newForm = newForm.replace(/__name__/g, index);
            $(this).data('index', index + 1);
            $('.' + target).append(newForm);
        })
        .on('click', '.show-address-block', function () {
            var target = '.' + $(this).data('target');
            if ($(target).hasClass('open')){
                $(target).slideUp();
                $(target).removeClass('open');
            }else {
                $(target).slideDown();
                $(target).addClass('open');
            }
        })

    $('.main')
        .on('click', '.show-address-block', function () {
            var target = '.' + $(this).data('target');
            if ($(target).hasClass('open')){
                $(target).slideUp();
                $(target).removeClass('open');
            }else {
                $(target).slideDown();
                $(target).addClass('open');
            }
        })


});

