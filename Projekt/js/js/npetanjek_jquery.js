$(document).ready(function(){
    var timer;
    $("#korime").keyup(function (event){
        clearTimeout(timer);
        var korime = $(this).val();
        timer = setTimeout(function(){
            provjeri_korime(korime);
        }, 1000);
    });
    
    function provjeri_korime(korime){
        $("#kor_rezultat").html("Provjera");
        $.post('provjeraKorisnika.php', {'username':korime}, function(data){
            $("#kor_rezultat").html(data);
        });
    }
});



