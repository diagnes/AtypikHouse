/**
 * Created by Nefast on 13/07/2018.
 */
function addPrototypeToDate(date) {
    var prototype = $('#calendar').data('prototype');
    var target = '.date-unavailable';

    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, date);
    $(target).append(newForm);
}

function removePrototypeToDate(date) {
    $('#'+date).remove();
}

function initCalendar(event) {
    /* initialize the calendar
     -----------------------------------------------------------------*/
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        dayClick: function (date) {
            var moment = date.toDate();
            MyDateString = moment.getFullYear() + '-'
                + ('0' + (moment.getMonth() +1) ).slice(-2)
                + "-" +('0' + moment.getDate()).slice(-2);
            var evento = $("#calendar").fullCalendar('clientEvents', MyDateString);
            console.log(evento);
            if (evento.length == 0){
                console.log(date);
                $('#calendar').fullCalendar('renderEvent',
                    {
                        id: MyDateString,
                        title: 'Unavailable',
                        start: date,
                        allDay: true
                    }, true );
                addPrototypeToDate(MyDateString);
                $('#housingbundle_housing_undisponibility_'+ MyDateString +'_startDate_day').val(moment.getDate());
                $('#housingbundle_housing_undisponibility_'+ MyDateString +'_startDate_month').val(moment.getMonth() + 1);
                $('#housingbundle_housing_undisponibility_'+ MyDateString +'_startDate_year').val(moment.getFullYear());
            }else {
                $('#calendar').fullCalendar( 'removeEvents', MyDateString);
                removePrototypeToDate(MyDateString);
            }
        },
        eventClick: function(calEvent) {
            $('#calendar').fullCalendar( 'removeEvents', calEvent.id);
            removePrototypeToDate(calEvent.id);
        },
        editable: true,
        droppable: false,
        events: event
    });
}
