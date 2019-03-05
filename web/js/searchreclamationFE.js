$("document").ready(function () {

    $("#usertoclaimename").keyup(function () {

        $.ajax({
            type: 'get',
            url: 'http://localhost/PIDEV/PIDEV/web/app_dev.php/searchreclamationajax/' + $(this).val(),
            beforeSend: function () {
                console.log('ca change');
            },
            success: function (data) {
                $(".deleteme").remove();
                $.each(data.valeur, function (index, value) {

                    var xx="<li class='list-group-item deleteme'><a href='#'>"+value+"</a></li>"

                    $("#resulthereman").append($(xx));
                    console.log(value);
                });

          },
        });

    });
    $('#resulthereman').on('click', '.list-group-item', function(e) {

        var x=$(this).text();
        console.log($(this).text());
        $("#usertoclaimename").val(x);
        $('.deleteme').remove();
    });

});
