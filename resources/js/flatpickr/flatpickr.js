let dataDefault = null;
let dataIssuedDefault = null;
let dataReportRangeDefault = null;

if(typeof dateDataViewRequest !== 'undefined' && dateDataViewRequest) {
    dataDefault = dateDataViewRequest;
} else if(typeof dateDataApplication !== 'undefined' && dateDataApplication) {
    dataDefault = dateDataApplication;
} else if(typeof dateDataIssue !== 'undefined' && dateDataIssue) {
    dataDefault = dateDataIssue;
}

if(typeof dateDataIssuedApplication !== 'undefined' && dateDataIssuedApplication) {
    dataIssuedDefault = dateDataIssuedApplication;
}

if(typeof dateReportRange !== 'undefined' && dateReportRange) {
    dataReportRangeDefault = parseDateRange(dateReportRange);
}

function parseDateRange(dateReportRangeDefault)
{
    return dateReportRangeDefault.split(' — ');
}

function getDate()
{
    if(dataDefault) return dataDefault;

    let today = new Date();

    let curDateDay = today.getDate();
    let dd = String(curDateDay).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0');
    let yyyy = today.getFullYear();

    if(today.getDay() === 0) {
        dd = String(curDateDay + 1).padStart(2, '0');
    } else if(today.getDay() === 6) {
        dd = String(curDateDay + 2).padStart(2, '0');
    }

    let todayFormat = dd + '/' + mm + '/' + yyyy;
    console.log(todayFormat)
    return todayFormat;
}

function getOneMonthDate()
{
    let today = new Date();
/*    let beforeDay = new Date();
    beforeDay.setDate(beforeDay.getDate() -30);*/
    let day = String(today.getDate()).padStart(2, '0');
    // let lastDay = String(today.getDate()).padStart(2, '0');
    let beforeMonth = String(today.getMonth()).padStart(2, '0');
    let month = String(today.getMonth() + 1).padStart(2, '0');
    let year = String(today.getFullYear());
    return [
        `${day}/${beforeMonth}/${year}`,
        `${day}/${month}/${year}`
    ];
}


$('.date').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    // minDate: (dataDefault) ? dataDefault : "today",
    defaultDate: getDate(),
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],

});

console.log('mk')

$('.date-manager').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    defaultDate: getDate(),
});

let dateAdmin = $('.date-admin').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    defaultDate: dataIssuedDefault,
});



$(`#dataClear`).on('click', dateAdmin.clear);

$('.date-range').flatpickr({
    mode: "range",
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    defaultDate: (dataReportRangeDefault) ? dataReportRangeDefault : getOneMonthDate()
});




/////Modal
let startpicker;
let endpicker;

$(function() {
    $("body").delegate("#arriving_at_modal", "focusin", function(){
        let issued_at_modal = $('#issued_at_modal').val();
        startpicker = $(this).flatpickr({
            defaultDate: getDateAr('#arriving_at_div'),
            altInput: true,
            altFormat: "d.m.Y",
            dateFormat: "d.m.Y",
            maxDate:issued_at_modal,
            onClose: function() {
                let arriving_at_modal = $('#arriving_at_modal').val();
                console.log("arriving_at_modal")
                console.log(arriving_at_modal)
                endpicker.set('minDate', arriving_at_modal);
            },
        });
    });
});
$(function() {
    $("body").delegate("#issued_at_modal", "focusin", function(){
        let arriving_at_modal = null;
        arriving_at_modal = $('#arriving_at_modal').val();
        endpicker = $(this).flatpickr({
            defaultDate: getDateAr('#issued_at_div'),
            altInput: true,
            altFormat: "d.m.Y",
            dateFormat: "d.m.Y",
            minDate: arriving_at_modal,
            onClose: function() {
                let issued_at_modal = $('#issued_at_modal').val();
                console.log("issued_at_modal")
                console.log(issued_at_modal)
                startpicker.set('maxDate', issued_at_modal);
            },
        });
    });
});

function getDateAr(id){
    let date = $(id).text();
    if(date){
        return date;
    }
    return getDate();
}
