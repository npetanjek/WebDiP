$(document).ready(function(){
    load_data();

    function load_data(stranica) {
        $.ajax({
            url: "zahtjeviZaBlokiranjeOglasaStranicenje.php",
            method: "POST",
            data: {
                stranica: stranica
            },
            success: function (data) {
                $('#pagination_data').html(data);
            }
        });
    }

    $(document).on('click', '.pagination_link', function () {
        var stranica = $(this).attr("id");
        load_data(stranica);
    });
});