$("document").ready(function () {

    $("#searchwishlistinput").keyup(function () {
        $.ajax({
            type: 'get',
            url: 'http://localhost/PIDEV/PIDEV/web/app_dev.php/searchwishlistajax/' + $(this).val(),
            beforeSend: function () {
                console.log('ca change');
            },
            success: function (data) {

                $("tr").remove();
                var xx="<tr class='table-head'>" +
                    "<th class='column-1'></th> " +
                    "<th class='column-2'>Product</th> " +
                    "<th class='column-2'>Seller</th> " +
                    "<th class='column-3'  style='text-align: center'>Action</th> " +
                    "</tr>"

                $("#resultwishlisttable").append($(xx));

                $.each(data.valeur, function (index, value) {

                    var x="<tr class='table-row'>" +
                                "<td class='column-1'>" +
                        "               <div class='cart-img-product b-rad-4 o-f-hidden'>" +
                        "                         <img src='' alt='IMG-PRODUCT'>" +
                        "               </div>" +
                        "        </td>" +
                        "        <td class='column-2'>" +
                        "               <a href='#'>"+value.Product+"</a>" +
                        "        </td> " +
                        "        <td class='column-2'>"+value.Seller+"</td> " +
                        "        <td class='column-4'>" +
                        "                   <a href='localhost/PIDEV/PIDEV/web/app_dev.php/AddToWishList/"+value.idp+"'>" +
                    "                           <button class='flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4'> Add To wish list</button>" +
                    "                       </a></td> </tr>";
                    $("#resultwishlisttable").append($(x));
                    console.log(value);
                });
          },


        });

    });
    


});
