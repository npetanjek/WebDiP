$(document).ready(function () {
    var id_pozicija;
    tablicaSvihVrsti();
    listaVrsti();
    autoComplete();

    function tablicaSvihVrsti() {
        $.ajax({
            method: "POST",
            url: "tablicaVrstaOglasa.php",
            dataType: "JSON",
            success: function (data) {
                alert("Uspjeh");
                var tablica = '<table id="table_vrste_oglasa" border="1"><thead><tr><th>Pozicija</th><th>Lokacija</th><th>Stranica</th><th>Dimenzije</th></tr></thead><tbody>';
                for (var i = 0; i < data.length; i++) {
                    tablica += '<tr><td><span class="prikazVrsti" id="' + data[i]['0'] + '">' + data[i]['0'] + '</span></td><td>' + data[i]['3'] + '</td><td>' + data[i]['2'] + '</td><td>' + data[i]['1'] + '</td></tr>';
                }
                tablica += '</tbody></table><br>';
                $("#tablicaVrstaOglasa").html(tablica);
            }
        });
    }
/*
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
    });*/
});