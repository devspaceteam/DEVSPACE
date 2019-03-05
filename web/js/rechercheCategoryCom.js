$("document").ready(function () {

    $("#inputvalue").keyup(function () {

        $.ajax({
            type: 'get',
            url: 'http://localhost/test/web/app_dev.php/new_blogPost_empty/' + $(this).val(),
            beforeSend: function () {
                console.log('ca change');
            },
            success: function (data) {
                $(".deleteme").remove();
                $.each(data.valeur, function (index, value) {

                    var xx="<li class='list-group-item deleteme d-flex align-items-right'><a href='#'>"+value+"</a></li>"

                    $("#resultat").append($(xx));
                    console.log(value);
                });

            },
        });

    });
    $('#resultat').on('click', '.list-group-item', function(e) {

        var x=$(this).text();
        console.log($(this).text());
        $("#inputvalue").val(x);
        $('.deleteme').remove();
    });

});
