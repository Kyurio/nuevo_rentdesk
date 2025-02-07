/*********************************  Historial   *************************************/

function obtenerValoresFormulario(idFormulario) {
  // Crear un objeto para almacenar los datos del formulario
  var formData = {};

  // Obtener todos los campos de entrada del formulario y agregar sus valores al objeto formData
  $("#" + idFormulario + " input").each(function () {
    // Verificar si el input no tiene el atributo form o si tiene el atributo form y es igual a idFormulario
    if (!$(this).attr("form") || $(this).attr("form") === idFormulario) {
      var nombre = $(this).attr("name");
      var valor = $(this).val();
      formData[nombre] = valor;
    }
  });

  // Obtener los valores de los elementos select y agregarlos al objeto formData
  $("#" + idFormulario + " select").each(function () {
    // Verificar si el select no tiene el atributo form o si tiene el atributo form y es igual a idFormulario
    if (!$(this).attr("form") || $(this).attr("form") === idFormulario) {
      var nombre = $(this).attr("name");
      // Obtener el texto de la opción seleccionada y asignarlo al objeto formData
      var valor = $(this).find("option:selected").text();
      formData[nombre] = valor;
    }
  });

  // Convertir el objeto formData a una cadena JSON
  var jsonString = JSON.stringify(formData);

  // Devolver la cadena JSON
  return jsonString;
}

function capturarInformacionAntigua() {
  var jsonStringRecuperada = sessionStorage.getItem("informacionAntigua");
  sessionStorage.clear();
  return jsonStringRecuperada;
}

function registroHistorial(
  tipoRegistro,
  dataAntigua,
  dataNueva,
  item,
  id_recurso,
  id_item
) {
  var url = window.location.href;
  var parametros = new URL(url).searchParams;
  var component = parametros.get("component");
  var view = parametros.get("view");
  var token = parametros.get("token");
  console.log("componente: " + component);
  console.log("vista: " + view);
  console.log(token);
  if (tipoRegistro == "Crear") {
    var dataNueva = JSON.parse(dataNueva);
    var diferenciasTexto = $.map(dataNueva, function (value, key) {
      return key + ": " + value;
    }).join(", ");
  } else if (tipoRegistro == "Modificar") {
    console.log(tipoRegistro);
    //transformo ajson la data recibida
    var dataAntigua = JSON.parse(dataAntigua);
    var dataNueva = JSON.parse(dataNueva);

    // Array para almacenar las diferencias como texto
    var diferenciasTexto = [];
    //transformo ajson la data recibida
    for (var clave in dataAntigua) {
      if (dataAntigua.hasOwnProperty(clave)) {
        if (dataAntigua[clave] !== dataNueva[clave]) {
          // Almacena la diferencia encontrada como texto en el array
          var textoDiferencia =
            "Se cambió " +
            clave +
            ": de " +
            dataAntigua[clave] +
            " a " +
            dataNueva[clave];
          diferenciasTexto.push(textoDiferencia);
        }
      }
    }

    var diferenciasTexto = diferenciasTexto.join(", ");
    console.log(diferenciasTexto);
  } else if (tipoRegistro == "Eliminar") {
    var diferenciasTexto = dataNueva;
  }

  $.ajax({
    url: "includes/insert_historial.php",
    type: "POST",
    dataType: "text", // Corregido aquí
    data: {
      textoDiferencia: diferenciasTexto,
      tipoRegistro: tipoRegistro,
      component: component,
      view: view,
      token: token,
      item: item,
      id_recurso: id_recurso,
      id_item: id_item,
    },
    cache: false,
    success: function (response) {
      console.log(response);
    },
    error: function (xhr, status, error) {
      // Manejar errores aquí
    },
  });
}
