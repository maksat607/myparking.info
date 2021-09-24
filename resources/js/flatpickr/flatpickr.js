let dataDefault = null;
if(typeof dateDataViewRequest !== 'undefined' && dateDataViewRequest) {
    dataDefault = dateDataViewRequest;
} else if(typeof dateDataApplication !== 'undefined' && dateDataApplication) {
    dataDefault = dateDataApplication;
} else if(typeof dateDataIssue !== 'undefined' && dateDataIssue) {
    dataDefault = dateDataIssue;
}

$('.date').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    minDate: (dataDefault) ? dataDefault : "today",
    defaultDate: (dataDefault) ? dataDefault : "today",
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],
});
