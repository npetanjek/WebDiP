<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "INSERT INTO `vrste_na_pozicijama`(`id_vrsta`, `id_pozicija`) VALUES ({$_POST["id_vrsta"]}, {$_POST["id_pozicija"]})";
$veza->updateDB($sql);
$date = date('Y-m-d H:i:s');
$sql_dnevnik = "INSERT INTO dnevnik_rada VALUES ('" . $_COOKIE["user"] . "', 'Dodijeljena vrsta oglasa " . $_POST["id_vrsta"] . " poziciji " . $_POST["id_pozicija"] . ".', '" . $date . "')";
$veza->updateDB($sql_dnevnik);
$veza->zatvoriDB();

