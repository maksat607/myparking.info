$('.date').flatpickr({
    altInput: true,
    altFormat: "d/m/Y",
    dateFormat: "d-m-Y",
    minDate: (typeof dateDataApplication !== 'undefined' && dateDataApplication) ? dateDataApplication : "today",
    defaultDate: (typeof dateDataApplication !== 'undefined' && dateDataApplication) ? dateDataApplication : "today",
    disable: [
        function(date) {
            return (date.getDay() === 0 || date.getDay() === 6);
        }
    ],
});
