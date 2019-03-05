$("document").ready(function () {

    $("#imputhereuers").keyup(function () {
        $.ajax({
            type: 'get',
            url: 'http://localhost/PIDEV/PIDEV/web/app_dev.php/BO/searchajax/' + $(this).val(),
            beforeSend: function () {
                console.log('ca change');
            },
            success: function (data) {

                $("tr").remove();
                var xx="<tr> <th>ID</th> <th>Nom</th> <th>Derniere Accès </th> <th>Status</th> <th>Email</th> </tr>"
                $("#susulthereusers").append($(xx));
                var noban="<td><span class='label label-success'>0 ban</span></td>";
                var oneban="<td><span class='label label-warning'>1 ban</span></td>";
                var towban="<td><span class='label label-danger'>2 ban</span></td>";
                var bannee="<td><span class='label label-primary'>Bannée</span></td>";
                $.each(data.valeur, function (index, value) {

                    if(value.status == 0) {var stat=noban;}
                    else if (value.status == 1) {var stat=oneban;}
                    else if (value.status == 2) {var stat=towban;}
                    else {var stat=bannee;}
                    var x="<tr >" +
                                    "<td>"+value.id+"</td>" +
                                    "<td>"+value.username+"</td>" +
                                    "<td>"+value.date.date+"</td>" +
                                    stat +
                                    "<td>"+value.email+"</td>" +
                                    "<td><a href='http://localhost/PIDEV/PIDEV/web/app_dev.php/BO/ProfileReclamation/"+value.username+"'>Open</a></td>"+
                        "</tr>";
                    $("#susulthereusers").append($(x));
                    console.log(value);
                });
          },


        });

    });
    


});
