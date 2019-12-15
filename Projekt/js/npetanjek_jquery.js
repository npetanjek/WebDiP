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

var interval_time5 = 7000; // 3 seconds between changes
var item_count5;
var item_interval5;
var old_item5 = 0;
var current_item5 = 0;

$(document).ready(function () {
    item_count5 = $("div.slideshow-item5").size();

    $("div.slideshow-item5").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item5:eq(" + current_item5 + ")").fadeIn("slow");

    item_interval5 = setInterval(item_rotate5, interval_time5); // time in milliseconds

});

function item_rotate5() {
    current_item5 = (old_item5 + 1) % item_count5;
    $("div.slideshow-item5:eq(" + old_item5 + ")").fadeOut("slow", function () {
        $("div.slideshow-item5:eq(" + current_item5 + ")").fadeIn("slow");
    });
    old_item5 = current_item5;
}

var interval_time10 = 10000; // 3 seconds between changes
var item_count10;
var item_interval10;
var old_item10 = 0;
var current_item10 = 0;

$(document).ready(function () {
    item_count10 = $("div.slideshow-item10").size();

    $("div.slideshow-item10").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item10:eq(" + current_item10 + ")").fadeIn("slow");

    item_interval10 = setInterval(item_rotate10, interval_time10); // time in milliseconds

});

function item_rotate10() {
    current_item10 = (old_item10 + 1) % item_count10;
    $("div.slideshow-item10:eq(" + old_item10 + ")").fadeOut("slow", function () {
        $("div.slideshow-item10:eq(" + current_item10 + ")").fadeIn("slow");
    });
    old_item10 = current_item10;
}

var interval_time12 = 6500; // 3 seconds between changes
var item_count12;
var item_interval12;
var old_item12 = 0;
var current_item12 = 0;

$(document).ready(function () {
    item_count12 = $("div.slideshow-item12").size();

    $("div.slideshow-item12").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item12:eq(" + current_item12 + ")").fadeIn("slow");

    item_interval12 = setInterval(item_rotate12, interval_time12); // time in milliseconds

});

function item_rotate12() {
    current_item12 = (old_item12 + 1) % item_count12;
    $("div.slideshow-item12:eq(" + old_item12 + ")").fadeOut("slow", function () {
        $("div.slideshow-item12:eq(" + current_item12 + ")").fadeIn("slow");
    });
    old_item12 = current_item12;
}

var interval_time9 = 8000; // 3 seconds between changes
var item_count9;
var item_interval9;
var old_item9 = 0;
var current_item9 = 0;

$(document).ready(function () {
    item_count9 = $("div.slideshow-item9").size();

    $("div.slideshow-item9").each(function (i) {
        $(this).hide();
    });

    $("div.slideshow-item9:eq(" + current_item9 + ")").fadeIn("slow");

    item_interval9 = setInterval(item_rotate9, interval_time9); // time in milliseconds

});

function item_rotate9() {
    current_item9 = (old_item9 + 1) % item_count9;
    $("div.slideshow-item9:eq(" + old_item9 + ")").fadeOut("slow", function () {
        $("div.slideshow-item9:eq(" + current_item9 + ")").fadeIn("slow");
    });
    old_item9 = current_item9;
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



