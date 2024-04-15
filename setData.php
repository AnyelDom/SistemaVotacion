<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST["nombre"];
    $alias = $_POST["alias"];
    $rut = $_POST["rut"];
    $email = $_POST["email"];
    $region = $_POST["region"];
    $comuna = $_POST["comuna"];
    $candidato = $_POST["candidato"];
    $difusiones = isset($_POST["difusiones"]) ? $_POST["difusiones"] : array();
}

$difusion = json_encode($difusiones);

$q= "INSERT INTO votos (id, nombre, alias, rut, email, region ,comuna ,candidato ,difusion)
VALUES (NULL, '$nombre', '$alias', '$rut', '$email', '$region', '$comuna', '$candidato', '$difusion')";

$r = connect()->query($q);
    
if($r){
    header("Location:index.php?mensaje= Voto registrado correctamente");
    exit();
}else{
    header("Location:index.php?mensaje= Error al registrar el voto");
    exit();
}
   
