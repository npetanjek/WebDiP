var interval_time = 5000; // 3 seconds between changes
var item_count;
var item_interval;
var old_item = 0;
var current_item = 0;

$(document).ready(function () {
    item_count = $("div.slideshow-item").size();

    $("div.slideshow-item").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item:eq(" + current_item + ")").fadeIn("slow");

    item_interval = setInterval(item_rotate, interval_time); // time in milliseconds

});

function item_rotate() {
    current_item = (old_item + 1) % item_count;
    $("div.slideshow-item:eq(" + old_item + ")").fadeOut("slow", function () {
        $("div.slideshow-item:eq(" + current_item + ")").fadeIn("slow");
    });
    old_item = current_item;
}

var interval_time2 = 7000; // 3 seconds between changes
var item_count2;
var item_interval2;
var old_item2 = 0;
var current_item2 = 0;

$(document).ready(function () {
    item_count2 = $("div.slideshow-item2").size();

    $("div.slideshow-item2").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item2:eq(" + current_item2 + ")").fadeIn("slow");

    item_interval2 = setInterval(item_rotate2, interval_time2); // time in milliseconds

});

function item_rotate2() {
    current_item2 = (old_item2 + 1) % item_count2;
    $("div.slideshow-item2:eq(" + old_item2 + ")").fadeOut("slow", function () {
        $("div.slideshow-item2:eq(" + current_item2 + ")").fadeIn("slow");
    });
    old_item2 = current_item2;
}

var interval_time3 = 6000; // 3 seconds between changes
var item_count3;
var item_interval3;
var old_item3 = 0;
var current_item3 = 0;

$(document).ready(function () {
    item_count3 = $("div.slideshow-item3").size();

    $("div.slideshow-item3").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item3:eq(" + current_item3 + ")").fadeIn("slow");

    item_interval3 = setInterval(item_rotate3, interval_time3); // time in milliseconds

});

function item_rotate3() {
    current_item3 = (old_item3 + 1) % item_count3;
    $("div.slideshow-item3:eq(" + old_item3 + ")").fadeOut("slow", function () {
        $("div.slideshow-item3:eq(" + current_item3 + ")").fadeIn("slow");
    });
    old_item3 = current_item3;
}

/*$(document).ready(function () {
 
 var timer;
 $("#korime").keyup(function (event) {
 clearTimeout(timer);
 var korime = $(this).val();
 timer = setTimeout(function () {
 provjeri_korime(korime);
 }, 1000);
 });
 
 function provjeri_korime(korime) {
 $("#kor_rezultat").html("Provjera");
 $.post('provjeraKorisnika.php', {'username': korime}, function (data) {
 $("#kor_rezultat").html(data);
 });
 }
 });*/



