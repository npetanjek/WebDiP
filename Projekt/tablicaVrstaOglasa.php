<?php

require './sesija.class.php';
Sesija::kreirajSesiju();
require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
$sql = "SELECT po.id_pozicija, po.sirina_oglasa, po.visina_oglasa, s.url, l.naziv FROM pozicija_oglasa po JOIN lokacija l ON po.id_lokacija=l.id_lokacija JOIN stranica s ON po.id_stranica=s.id_stranica WHERE po.id_moderator=" . $_SESSION["id"];
$rs = $veza->selectDB($sql);
$vrste = array();
while ($red = mysqli_fetch_array($rs)) {
    $vrste[] = $red;
}
$veza->zatvoriDB();
echo json_encode($vrste);

