

$(document).ready(function(){
    $("body").on('keyup',"#searchAdminKeywoard",function(){
        search_table($(this).val());
    });
    function search_table(value){
        $('.adminsEmail tr.tr').each(function(){
            var found = 'false';
            $(this).each(function(){
                if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)
                {
                    found = 'true';
                }
            });
            if(found == 'true')
            {
                $(this).show();
            }
            else
            {
                $(this).hide();
            }
        });
    }
});

$('.inner-page-search').on('keyup', function() {
    if ($(this).val() != '') {


        var fd = new FormData();
        fd.append('vin', $(this).val());
        let r = searchvin(fd);

        // console.log(res)

        r.done(function(data){
            console.log(data.length)
            $('.d-dowen-select').empty();
            let route = $('.d-dowen-select').data('url');
            for (let i = 0; i < data.length; i++) {
                let anchor = `<a href="${route}/partner/add/${data[i]['id']}">`;
                if($('#superadminid').length){
                    anchor = `<a href="${route}/partners/${data[i]['id']}/edit">`;
                }
                let res =  `${anchor}
                                <li>
                                    <span>${data[i]['name']}</span>
                                    <div>
                                        <span>ИНН: ${data[i]['inn']}</span>
                                        <span>КПП: ${data[i]['kpp']}</span>
                                    </div>
                                </li>
                            </a>`
                $('.d-dowen-select').append(res);

            }

        });
        $(this).parent().next('.d-dowen-body').addClass('active');
    } else {
        $(this).parent().next('.d-dowen-body').removeClass('active');
    }
});
$(`body`).on('click', `.d-dowen-select li`, function (e) {
// $('.d-dowen-select li').on('click', function() {
    $(this).parent().find('li').removeClass('active');
    $(this).addClass('active');
})



function  searchvin(form){
    const result =  $.ajax({
        url: `/partner/search-vin`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'post',
        data: form,
        contentType: false,
        processData: false
    });
    return result;
}
