$(document).ready(function(){
    $("#keyword").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#searchable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
