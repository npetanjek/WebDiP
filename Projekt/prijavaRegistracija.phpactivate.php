<?php

require './baza.class.php';
$veza = new Baza();
$veza->spojiDB();
if(!empty($_GET["korime"])){
    $sql = "UPDATE korisnik SET status = 'aktivan' WHERE korisnicko_ime=" . $_GET["korime"] . "'";
    $rs = $veza->updateDB($sql);
    if(!empty($rs)){
        $message = "Vaš korisnički račun je aktiviran.";
    }
    else{
        $message = "Problem kod aktivacije korisničkog računa!";
    }
}

