<?php
require './baza.class.php';
if(isset($_POST["korime"])){
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
      die();  
    }
    $db = new Baza();
    $db->spojiDB();
    if($db->pogreskaDB()){
        exit();
    }
    $korime = filter_var($_POST["korime"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $upit = "SELECT korisnicko_ime FROM korisnik WHERE korisnicko_ime=?";
    
    $rez = $db->selectDB($upit);
    if($rez->fetch()){
        die("Postoji");
    }
    else{
        die("Dostupno");
    }
}

