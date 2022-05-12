$(document).ready(function(){
    $("#keyword").on("keyup", function(e) {
        $(".table-dd").addClass('active')
        $(".table-dd").css("display", "row");
        if (e.which == 8) {
            console.log('back')
            $(".table-dd").css("display", "none");
            $(".table-dd-show").parent().parent().removeAttr("style");
        }
        var value = $(this).val().toLowerCase();
        console.log('lenght' + value.length)

        if(value.length==1&&e.which != 8){
            $(".table-dd").addClass('active')
            $(".table-dd").css("display", "row");
        }

        $("#searchable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        if(value.length==0||value==''){
            $(".table-dd").css("display", "none");
            $(".table-dd").removeClass('active');
            $(".table-dd-show").parent().parent().removeAttr("style");
        }


    });
});
