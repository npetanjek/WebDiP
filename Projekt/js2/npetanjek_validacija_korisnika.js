$(document).ready(function () {

    neispunjenaPolja();
    podudaranjeLozinki();
    korimeBrojZnakova();
    lozinkaBrojZnakova();
    emailFormat();
    nedozvoljeniZnakovi();

    function neispunjenaPolja() {
        $("#registracija").click(function (event) {
            $("input").each(function () {
                var elem = $(this);
                if (elem.val() == "") {
                    $(this).attr("style", "border:maroon 2px solid");
                    event.preventDefault();
                } else {
                    $(this).removeAttr("style");
                }
            });
        });
    }

    function nedozvoljeniZnakovi() {
        $("#registracija").click(function (event) {
            var re = /[#?!']/;
            $("input").each(function () {
                var elem = $(this).val();
                var nedozvoljen = re.test(elem);
                if (nedozvoljen) {
                    alert("Nedopušten znak! (#?!')");
                    event.preventDefault();
                }
            });
        });
    }

    function korimeBrojZnakova() {
        $("#registracija").click(function (event) {
            if ($("#korime").val().length < 3) {
                alert("Korisničko ime mora imati barem 3 slova!");
                event.preventDefault();
            }
        });
    }

    function lozinkaBrojZnakova() {
        $("#registracija").click(function (event) {
            if ($("#korime").val().length < 6) {
                alert("Lozinka mora imati barem 6 znakova!");
                event.preventDefault();
            }
        });
    }

    function podudaranjeLozinki() {
        $("#registracija").click(function (event) {
            if ($("#lozinka1").val().length > 0 && $("#lozinka2").val().length > 0) {
                if ($("#lozinka1").val() !== $("#lozinka2").val()) {
                    alert("Lozinke nisu jednake.");
                    event.preventDefault();
                }
            } else {
                event.preventDefault();
            }
        });
    }

    function emailFormat() {
        $("#mail").keyup(function (event) {
            var email = $("#mail").val();
            var re = /^[a-z\d]+\.{0,}[a-z\d]+@[a-z\d]+\.[a-z\d]{2,}$/;
            var ok = re.test(email);
            if (!ok) {
                $("#mail").attr("style", "border:2px solid maroon");
                event.preventDefault();
            } else {
                if (email.length < 10 || email.length > 30) {
                    $("#mail").attr("style", "border:2px solid maroon");
                    event.preventDefault();
                } else {
                    $("#mail").attr("style", "border:2px solid green");
                }
            }

        });
    }
});
