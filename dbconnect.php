<?php

session_start();
//parametri za povezaivanje s bazom

$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "houseofwine";

//povezaivanje s bazom
$connection = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

//provjera da li postoji veza
if ($connection === false) {
    die("GREŠKA: Spajanje na bazu nije uspjelo!" . mysqli_connect_error());
}
?>