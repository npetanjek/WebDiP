$(document).ready(function () {
    var id_pozicija;
    tablicaSvihVrsti();
    listaVrsti();
    autoComplete();
    statistikaKlikovaGraf();
    statistikaPlacenihIznosaGraf();
    topListaKorisnikaGraf();
    statistikaOglasaGraf();

    function tablicaSvihVrsti() {
        $.ajax({
            method: "POST",
            url: "tablicaVrstaOglasa.php",
            dataType: "JSON",
            success: function (data) {
                var tablica = '<table id="table_vrste_oglasa" border="1"><thead><tr><th>Pozicija</th><th>Lokacija</th><th>Stranica</th><th>Širina oglasa</th><th>Visina oglasa</th></tr></thead><tbody>';
                for (var i = 0; i < data.length; i++) {
                    tablica += '<tr><td><span class="prikazVrsti" id="' + data[i]['0'] + '">' + data[i]['0'] + '</span></td><td>' + data[i]['4'] + '</td><td>' + data[i]['3'] + '</td><td>' + data[i]['1'] + '</td><td>' + data[i]['2'] + '</td></tr>';
                }
                tablica += '</tbody></table><br>';
                $("#tablicaVrstaOglasa").html(tablica);
            }
        });
    }

    $(document).on('click', '.prikazVrsti', function () {
        id_pozicija = this.id;
        $.ajax({
            method: "POST",
            url: "vrstaZaPoziciju.php",
            dataType: "JSON",
            data: {
                id_pozicija: id_pozicija
            },
            success: function (data) {
                alert(id_pozicija);
                console.log(data);
                var tablica = '<table id="tablica" border="1"><thead><tr><th>Pozicija</th><th>Vrsta oglasa</th></tr></thead><tbody>';
                for (var i = 0; i < data.length; i++) {
                    tablica += '<tr>';
                    tablica += '<td>' + data[i]['1'] + '</td>';
                    tablica += '<td>' + data[i]['0'] + '</td>';
                    tablica += '</tr>';
                }
                tablica += '</tbody></table>';
                $("#tablicaVrsta").html(tablica);
            }
        });

    });




    function autoComplete() {
        $(document).on('click', "#table", function () {
            var table = document.getElementById('table');

            for (var i = 0; i < table.rows.length; i++)
            {
                table.rows[i].onclick = function ()
                {
                    //rIndex = this.rowIndex;
                    document.getElementById("id_lokacija").value = this.cells[0].innerHTML;
                    document.getElementById("naziv_lokacije").value = this.cells[1].innerHTML;
                    document.getElementById("adresa").value = this.cells[2].innerHTML;
                    document.getElementById("broj_telefona").value = this.cells[3].innerHTML;
                    document.getElementById("email").value = this.cells[4].innerHTML;
                    document.getElementById("moderator").value = this.cells[5].innerHTML;

                };
            }
        });

    }

    function listaVrsti() {
        $.ajax({
            url: "vrstaZaPoziciju_lista.php",
            dataType: "JSON",
            success: function (data) {
                var lista = '<label class="label_lijevo" for="lista_vrsti">Vrste: </label>'
                        + '<input class="input_desno" name="lista_vrsti" list="vrste" id="lista_vrsti" placeholder="Odaberite vrstu"><br>';
                lista += '<datalist id="vrste">';
                for (var i = 0; i < data.length; i++) {
                    console.log(data[i]['0']);
                    lista += '<option>' + data[i]['0'] + '</option>';
                }
                lista += '</datalist>';
                $("#listaVrsta").html(lista);
            }
        });
    }

    $("#dodijeli_vrstu").on('click', function () {
        $.ajax({
            method: "POST",
            url: "dodijeliVrstuPoziciji.php",
            data: {
                id_pozicija: id_pozicija,
                id_vrsta: document.getElementById("lista_vrsti").value
            }
        });
    });



    function statistikaKlikovaGraf() {
        $.ajax({
            method: "POST",
            url: "tablicaStatistikaKlikova.php",
            dataType: "JSON",
            success: function (data) {
                var platno = document.getElementById("platno");
                var ctx = platno.getContext("2d");
                ctx.fillStyle = "rgb(0, 0, 0)";
                ctx.strokeRect(40, 0, 320, 400);
                var trenutna_poz_x = 0;
                for (var i = 0; i < data.length; i++) {
                    var d = Math.round(data[i]['0'] * 10);
                    var c = Math.round(Math.random() * 255);
                    var z = Math.round(Math.random() * 255);
                    var p = Math.round(Math.random() * 255);
                    var boja = "rgb(" + c + "," + z + "," + p + ")";

                    ctx.fillStyle = boja;
                    ctx.fillRect(100 + 40 * (i - 1), 400 - d, 38, 400);
                    ctx.save();
                    ctx.translate(100, 300);
                    ctx.rotate(-0.5 * Math.PI);
                    ctx.fillText(data[i]['1'], d - 90, trenutna_poz_x);
                    ctx.restore();
                    trenutna_poz_x = trenutna_poz_x + 38;
                }
            }
        });
    }

    function statistikaPlacenihIznosaGraf() {
        $.ajax({
            method: "POST",
            url: "tablicaPlacenihIznosa.php",
            dataType: "JSON",
            success: function (data) {
                var platno = document.getElementById("platnoPlaceniIznosi");
                var ctx = platno.getContext("2d");
                ctx.fillStyle = "rgb(0, 0, 0)";
                ctx.strokeRect(40, 0, 320, 400);
                var trenutna_poz_x = 0;
                for (var i = 0; i < data.length; i++) {
                    var d = Math.round(data[i]['1'] * 1);
                    var c = Math.round(Math.random() * 255);
                    var z = Math.round(Math.random() * 255);
                    var p = Math.round(Math.random() * 255);
                    var boja = "rgb(" + c + "," + z + "," + p + ")";

                    ctx.fillStyle = boja;
                    ctx.fillRect(100 + 40 * (i - 1), 400 - d, 38, 400);
                    ctx.save();
                    ctx.translate(100, 300);
                    ctx.rotate(-0.5 * Math.PI);
                    ctx.fillText(data[i]['0'] + " => " + data[i]['1'], d - 90, trenutna_poz_x);
                    ctx.restore();
                    trenutna_poz_x = trenutna_poz_x + 38;
                }
            }
        });
    }

    function topListaKorisnikaGraf() {
        $.ajax({
            method: "POST",
            url: "tablicaTopListaKorisnika.php",
            dataType: "JSON",
            success: function (data) {
                var platno = document.getElementById("platnoTopListaKorisnika");
                var ctx = platno.getContext("2d");
                ctx.fillStyle = "rgb(0, 0, 0)";
                ctx.strokeRect(40, 0, 320, 400);
                var trenutna_poz_x = 0;
                for (var i = 0; i < data.length; i++) {
                    var d = Math.round(data[i]['2'] * 1);
                    var c = Math.round(Math.random() * 255);
                    var z = Math.round(Math.random() * 255);
                    var p = Math.round(Math.random() * 255);
                    var boja = "rgb(" + c + "," + z + "," + p + ")";

                    ctx.fillStyle = boja;
                    ctx.fillRect(100 + 40 * (i - 1), 400 - d, 38, 400);
                    ctx.save();
                    ctx.translate(100, 300);
                    ctx.rotate(-0.5 * Math.PI);
                    ctx.fillText(data[i]['0'] + " => " + data[i]['2'], d - 90, trenutna_poz_x);
                    ctx.restore();
                    trenutna_poz_x = trenutna_poz_x + 38;
                }
            }
        });
    }
    
    function statistikaOglasaGraf() {
        $.ajax({
            method: "POST",
            url: "tablicaStatistikaOglasa.php",
            dataType: "JSON",
            success: function (data) {
                var platno = document.getElementById("platnoStatistikaOglasa");
                var ctx = platno.getContext("2d");
                ctx.fillStyle = "rgb(0, 0, 0)";
                ctx.strokeRect(40, 0, 320, 400);
                var trenutna_poz_x = 0;
                for (var i = 0; i < data.length; i++) {
                    var d = Math.round(data[i]['2'] * 5);
                    var c = Math.round(Math.random() * 255);
                    var z = Math.round(Math.random() * 255);
                    var p = Math.round(Math.random() * 255);
                    var boja = "rgb(" + c + "," + z + "," + p + ")";

                    ctx.fillStyle = boja;
                    ctx.fillRect(100 + 40 * (i - 1), 400 - d, 38, 400);
                    ctx.save();
                    ctx.translate(100, 300);
                    ctx.rotate(-0.5 * Math.PI);
                    ctx.fillText(data[i]['0'] + " => " + data[i]['2'], d - 90, trenutna_poz_x);
                    ctx.restore();
                    trenutna_poz_x = trenutna_poz_x + 38;
                }
            }
        });
    }
});