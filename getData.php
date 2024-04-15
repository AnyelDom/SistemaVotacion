<?php
include 'connection.php';
if(isset($_GET['function'])) {
    $function = $_GET['function'];
    
    if($function === 'Regiones') {
        echo getRegiones();
    }else if($function === 'Comunas'){
        $r = $_GET['region'];
        echo getComunas($r);
    }else if($function === 'Candidatos'){
        echo getCandidatos();
    }else if($function === 'Difusiones'){
        echo getDifusiones();
    }else if($function === 'ValidarDatos'){
        $rut = $_GET['rut'];
        $email = $_GET['email'];
        echo validarDatos($rut,$email);
    }
}

function getRegiones(){
    
    $q= 'SELECT * FROM regiones';

    $r = connect()->query($q);
    $regiones= array();

    if ($r->num_rows > 0) {
        foreach($r as $region) {
            $regiones[]= $region;    
        }
    } else {
        echo "No se encontraron resultados";
    }
    return json_encode($regiones);
}

function getComunas($region){
    $q= "SELECT * FROM comunas WHERE region ='$region'";

    $co = connect()->query($q);
    $comunas= array();

    if ($co->num_rows > 0) {
        foreach($co as $comuna) {
            $comunas[]= $comuna;    
        }
    } else {
        echo "No se encontraron resultados";
    }
    return json_encode($comunas);
}

function getCandidatos(){
    
    $q= 'SELECT * FROM candidatos';

    $ca = connect()->query($q);
    $candidatos= array();

    if ($ca->num_rows > 0) {
        foreach($ca as $candidato) {
            $candidatos[]= $candidato;    
        }
    } else {
        echo "No se encontraron resultados";
    }
    return json_encode($candidatos);
}

function getDifusiones(){
    
    $q= 'SELECT * FROM difusion';

    $d = connect()->query($q);
    $difusiones= array();

    if ($d->num_rows > 0) {
        foreach($d as $difusion) {
            $difusiones[]= $difusion;    
        }
    } else {
        echo "No se encontraron resultados";
    }
    return json_encode($difusiones);
}

function validarDatos($rut, $email){
    
    $qr= "SELECT * FROM votos WHERE rut ='$rut'";
    $qe= "SELECT * FROM votos WHERE email ='$email'";

    $rr = connect()->query($qr);
    $re = connect()->query($qe);

    $existeR = $rr->num_rows > 0; 
    $existeE = $re->num_rows > 0;

    return json_encode(array("existeR" => $existeR, "existeE" => $existeE));
}

?>
