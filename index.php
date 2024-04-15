<?php  include 'getData.php';?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de votación</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Formulario de votación:</h2>
  
  <form class="post" id="formulario" action="setData.php" method="post" >
    <div class="">
      <label for="nombre">Nombre y Apellido</label>
      <input class="res" type="text" id="nombre" name="nombre" required>
    </div>
    <div class="">
      <label for="alias">Alias</label>
      <input class="res" type="text" id="alias" name="alias" required>
    </div>
    <div class="">
      <label for="rut">RUT</label>
      <input class="res" type="text" id="rut" name="rut" maxlength="10" placeholder="Sin puntos y con guión" required>
    </div>
    <div class="">
      <label for="email">Email</label>
      <input class="res" type="email" id="email" name="email" required>
    </div>
    <div class="">
      <label for="region">Región</label>
      <select class="res" id="region" name="region" required></select>
    </div>
    <div class="">
      <label for="comuna">Comuna</label>
      <select class="res" id="comuna" name="comuna" required></select>
    </div>
    <div class="">
      <label for="candidato">Candidato</label>
      <select class="res" id="candidato" name="candidato" required></select>
    </div>
    <div class="">
      <label for="">Como se enteró de nosotros</label>
      <div class="dif" id="difusiones"></div>
    </div>
    <br>
    <div class="">
      <button type="submit" name="botonVotar">Votar</button>
    </div>
  </form>
</body>

<script>
  function obtenerRegiones() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getData.php?function=Regiones', true);
    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
        var regiones = JSON.parse(xhr.responseText);
        var selectRegion = document.getElementById('region');
        selectRegion.innerHTML = '';

        var optionNull = document.createElement('option');
        optionNull.value = '';
        selectRegion.appendChild(optionNull);

        regiones.forEach(function(region) {
          var option = document.createElement('option');
          option.value = region.id;
          option.textContent = region.nombre;
          selectRegion.appendChild(option);
        });

        selectRegion.addEventListener('change', function() {
          var regionId = selectRegion.value;
          obtenerComunas(regionId); 
        });

        obtenerComunas(selectRegion.value);
      } else {
        console.error('Error al obtener regiones');
      }
    };
    xhr.onerror = function() {
      console.error('Error de conexión');
    };
    xhr.send();
  }

  function obtenerComunas(region) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getData.php?function=Comunas&region=' + region, true);
    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
        var comunas = JSON.parse(xhr.responseText);
        var selectComuna = document.getElementById('comuna');
        selectComuna.innerHTML = '';

        var optionNull = document.createElement('option');
        optionNull.value = '';
        selectComuna.appendChild(optionNull);

        comunas.forEach(function(comuna) {
          var option = document.createElement('option');
          option.value = comuna.id;
          option.textContent = comuna.nombre;
          selectComuna.appendChild(option);
        });
      } else {
        console.error('Error al obtener comunas');
      }
    };
    xhr.onerror = function() {
      console.error('Error de conexión');
    };
    xhr.send();
  }

  function obtenerCandidatos() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getData.php?function=Candidatos', true);
    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
        var candidatos = JSON.parse(xhr.responseText);
        var selectCandidato = document.getElementById('candidato');
        selectCandidato.innerHTML = '';

        var optionNull = document.createElement('option');
        optionNull.value = '';
        selectCandidato.appendChild(optionNull);

        candidatos.forEach(function(candidato) {
          var option = document.createElement('option');
          option.value = candidato.id;
          option.textContent = candidato.nombre;
          selectCandidato.appendChild(option);
        });
      } else {
        console.error('Error al obtener datos de candidatos');
      }
    };
    xhr.onerror = function() {
      console.error('Error de conexión');
    };
    xhr.send();
  }

  function obtenerDifusion() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'getData.php?function=Difusiones', true);
    xhr.onload = function() {
      if (xhr.status >= 200 && xhr.status < 300) {
        var difusiones = JSON.parse(xhr.responseText);
        var checkboxDiv = document.getElementById('difusiones');
        checkboxDiv.innerHTML = ''; 

        difusiones.forEach(function(difusion) {
          var checkbox = document.createElement('input');
          checkbox.type = 'checkbox';
          checkbox.name = 'difusiones[]';
          checkbox.value = difusion.id;
          
          var label = document.createElement('label');
          label.textContent = difusion.nombre;
          label.classList.add('dif');

          checkboxDiv.appendChild(checkbox);
          checkboxDiv.appendChild(label);
        });
      } else {
        console.error('Error al obtener difusiones');
      }
    };
    xhr.onerror = function() {
      console.error('Error de conexión');
    };
    xhr.send();
 }

  window.onload = function() {
    obtenerRegiones();
    obtenerCandidatos();
    obtenerDifusion();
  };

  document.getElementById('formulario').addEventListener('submit', function(event) {
    var inputRut = document.getElementById('rut').value;
    var inputEmail = document.getElementById('email').value;
    var checkboxesDifusion = document.getElementsByName('difusiones[]');
    var inputDifusion = [];

    for (var i = 0; i < checkboxesDifusion.length; i++) {
      if (checkboxesDifusion[i].checked) {
        inputDifusion.push(checkboxesDifusion[i].value);
      }
    }

    var Fn = {
      validaRut: function(inputRut) {
        if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(inputRut)){
          return false; 
        }

        var tmp = inputRut.split('-');
        var digv = tmp[1];
        var rut = tmp[0];
        if (digv == 'K') digv = 'k';

        return (Fn.dv(rut) == digv );
      },
      dv: function(T) {
        var M = 0;
        var S = 1;

        for (; T; T = Math.floor(T / 10)){
          S = (S + T % 10 * (9 - M++ % 6)) % 11;
        }

        return S ? S - 1 : 'k';
      }
    };
 
    if (!Fn.validaRut(inputRut)) {
      event.preventDefault();
      alert("El RUT ingresado no es válido. Por favor, corríjalo.");
    }else if (!/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.(cl|com)$/.test(inputEmail)) {
      event.preventDefault();
      alert("El email ingresado no es válido. Debe terminar en .cl o .com.");
    }else {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'getData.php?function=ValidarDatos&rut=' + inputRut + '&email=' + inputEmail, true);
      xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
          var response = JSON.parse(xhr.responseText);
          if (response.existeR) {
            event.preventDefault();
            alert("El RUT ingresado ya está registrado.");
          }else if (response.existeE) {
            event.preventDefault();
            alert("El email ingresado ya está registrado.");
          }else if (inputDifusion.length === 0) {
            event.preventDefault();
            alert("Por favor, selecciona al menos una opción de difusión.");
          } else {
            document.getElementById('formulario').submit();
          }
        } else {
          console.error('Error al verificar los datos');
        }
      };
      xhr.onerror = function() {
        console.error('Error de conexión');
      };
      xhr.send();
    }
    event.preventDefault();
});

function obtenerMensaje(mensaje) {
  mensaje = mensaje.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
  var aux = new RegExp('[\\?&]' + mensaje + '=([^&#]*)');
  var resultados = aux.exec(location.search);
  return resultados === null ? '' : decodeURIComponent(resultados[1].replace(/\+/g, ' '));
}

var mensaje = obtenerMensaje('mensaje');
if (mensaje !== '') {
    alert(mensaje);
}



</script>