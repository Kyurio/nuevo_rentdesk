//Funcion CheckRut, utilizada para revisar y dar formato a rut
function checkRut(rut) {

  // Despejar Puntos
  var valor = rut.value.replace(".", "");
  // Despejar Guión
  valor = valor.replace("-", "");

  // Aislar Cuerpo y Dígito Verificador
  cuerpo = valor.slice(0, -1);
  dv = valor.slice(-1).toUpperCase();

  // Formatear RUN
  rut.value = cuerpo + "-" + dv;

  // Si no cumple con el mínimo ej. (n.nnn.nnn)
  if (cuerpo.length < 7) {
    rut.setCustomValidity("RUT Incompleto");
    return false;
  }

  // Calcular Dígito Verificador
  suma = 0;
  multiplo = 2;

  // Para cada dígito del Cuerpo
  for (i = 1; i <= cuerpo.length; i++) {
    // Obtener su Producto con el Múltiplo Correspondiente
    index = multiplo * valor.charAt(cuerpo.length - i);

    // Sumar al Contador General
    suma = suma + index;

    // Consolidar Múltiplo dentro del rango [2,7]
    if (multiplo < 7) {
      multiplo = multiplo + 1;
    } else {
      multiplo = 2;
    }
  }

  // Calcular Dígito Verificador en base al Módulo 11
  dvEsperado = 11 - (suma % 11);

  // Casos Especiales (0 y K)
  dv = dv == "K" ? 10 : dv;
  dv = dv == 0 ? 11 : dv;

  // Convertir dv a número
  dv = parseInt(dv);
  dvEsperado = parseInt(dvEsperado);


  // Validar que el Cuerpo coincide con su Dígito Verificador
  if (dvEsperado != dv) {
   
    rut.setCustomValidity("RUT Inválido");
    return false;

  }

  // Si todo sale bien, eliminar errores (decretar que es válido)
  rut.setCustomValidity("");
}

function checkRutFormat(inputId, estado) {
  if (estado === true) {
    // Obtener el elemento input mediante su ID
    var inputElement = document.getElementById(inputId);

    // Obtener el valor del input
    var valor = inputElement.value;

    // Despejar Puntos
    valor = valor.replace(".", "");
    // Despejar Guión
    valor = valor.replace("-", "");

    // Aislar Cuerpo y Dígito Verificador
    var cuerpo = valor.slice(0, -1);
    var dv = valor.slice(-1).toUpperCase();

    // Formatear RUN
    inputElement.value = cuerpo + "-" + dv;

    // Si no cumple con el mínimo ej. (n.nnn.nnn)
    if (cuerpo.length < 7) {
      inputElement.setCustomValidity("RUT Incompleto");
      return false;
    }

    // Calcular Dígito Verificador
    var suma = 0;
    var multiplo = 2;

    // Para cada dígito del Cuerpo
    for (var i = 1; i <= cuerpo.length; i++) {
      // Obtener su Producto con el Múltiplo Correspondiente
      var index = multiplo * valor.charAt(cuerpo.length - i);

      // Sumar al Contador General
      suma = suma + index;

      // Consolidar Múltiplo dentro del rango [2,7]
      if (multiplo < 7) {
        multiplo = multiplo + 1;
      } else {
        multiplo = 2;
      }
    }

    // Calcular Dígito Verificador en base al Módulo 11
    var dvEsperado = 11 - (suma % 11);

    // Casos Especiales (0 y K)
    dv = dv == "K" ? 10 : dv;
    dv = dv == 0 ? 11 : dv;

    // Validar que el Cuerpo coincide con su Dígito Verificador
    if (dvEsperado != dv) {
      inputElement.setCustomValidity("RUT Inválido");
      return false;
    }

    // Si todo sale bien, eliminar errores (decretar que es válido)
    inputElement.setCustomValidity("");
    return true;
  } else {
  }
}
