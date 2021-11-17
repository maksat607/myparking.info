let dataDefault = null;
if(typeof dateDataViewRequest !== 'undefined' && dateDataViewRequest) {
    dataDefault = dateDataViewRequest;
} else if(typeof dateDataApplication !== 'undefined' && dateDataApplication) {
    dataDefault = dateDataApplication;
} else if(typeof dateDataIssue !== 'undefined' && dateDataIssue) {
    dataDefault = dateDataIssue;
}

function getDate(){
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

    return todayFormat;
}

$('.date').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    minDate: (dataDefault) ? dataDefault : "today",
    defaultDate: getDate(),
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],
});

$('.date-manager').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    defaultDate: getDate(),
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],
});
