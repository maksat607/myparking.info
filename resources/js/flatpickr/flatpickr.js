$('.date').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    minDate: "today",
    defaultDate: "today",
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],
});
