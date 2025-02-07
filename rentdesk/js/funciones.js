function toggleFlecha(capa) {
  $(".capaBloque" + capa).toggle(500);

  if ($(".chevron-flecha" + capa).is(".fa-chevron-down")) {
    $(".chevron-flecha" + capa).removeClass("fa-chevron-down");
    $(".chevron-flecha" + capa).addClass("fa-chevron-up");
  } else {
    $(".chevron-flecha" + capa).removeClass("fa-chevron-up");
    $(".chevron-flecha" + capa).addClass("fa-chevron-down");
  }
} // function mostrarPregunta(capa)

function formSeleccionarEmpresa(opcion) {
  //opcion = opcion.replace("hgibsduutask6udkyuas", "'");
  //opcion = opcion.replace("uusdnlsduhfushdfsdfh", '"');

  opcion = opcion.replace(new RegExp("hgibsduutask6udkyuas", "g"), "'");
  opcion = opcion.replace(new RegExp("uusdnlsduhfushdfsdfh", "g"), '"');

  $.showModal({
    title: "Selecci&oacute;n de Empresa",
    body:
      '<form name="formempresa" id="formempresa">' +
      '<div class="form-group row">' +
      '<div style="padding:15px;">Seleccione la Empresa con la cual trabajar&aacute;.</div>' +
      "</div>" +
      '<div class="row">' +
      '<div class="col-3"><label for="text" class="col-form-label">Empresa</label></div>' +
      '<div class="col-9">' +
      opcion +
      "</div>" +
      '<div class="form-group row">' +
      "</div>" +
      "</form>",
    footer:
      '<button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Aceptar</button>',
    onCreate: function (modal) {
      // create event handler for form submit and handle values
      $(modal.element).on("click", "button[type='submit']", function (event) {
        event.preventDefault();
        var $form = $(modal.element).find("form");

        var empresa = $form.find("#empresa").val();
        var sucursal = "";

        if (empresa == "") {
          $.showAlert({ title: "Atenci�n", body: "Debe seleccionar una empresa" });
        } else {
          seteaEmpresa(empresa, sucursal);
        }
      });
    },
  });
} //function formOlvido

function seteaEmpresa(empresa, sucursal) {
  var url = "includes/seteaEmpresa.php";
  $.ajax({
    type: "POST",
    url: url,
    data: "&empresa=" + empresa + "&sucursal=" + sucursal,
    success: function (resp) {
      var retorno = resp.split(",xxx,");
      var resultado = retorno[1];

      if (resultado == "ok") {
        document.location.href = "index.php?component=dashboard&view=dashboard";
      } else {
        $.showAlert({
          title: "Atención",
          body: "Error al setear empresa, por favor intentelo nuevamente.",
        });
        return;
      }
    },
  });
} //seteaEmpresa

$(document).ready(function () {
  $(".nav li ").hover(
    function () {
      $(this).find(".fa").css("color", "#ffffff !important");
      // console.log("Mouse sobre el elemento de navegación.");
    },
    function () {
      $(this).find(".fa").css("color", ""); // Restablecer el color al salir del hover
      //   console.log("Mouse fuera del elemento de navegación.");
    }
  );
});

function clickTab(nombreTab) {
  const activeItemClass = "active-item";
  // Remove the "active" class from all anchor tags with href="#"
  var allAnchors = document.querySelectorAll('a[href="#"]');
  allAnchors.forEach(function (anchor) {
    anchor.classList.remove(activeItemClass);
  });

  // Add the "active" class to the clicked anchor
  document.getElementById(nombreTab).classList.add(activeItemClass);

  $("#" + nombreTab + "-tab").trigger("click");
}

function actualizaSubsidiaria(baseUrl, subsidiaria) {
  console.log("baseUrl: ", baseUrl);
  console.log("SUBSIDIARIA DESDE actualizaSubsidiaria: ", subsidiaria);
  $.ajax({
    type: "POST",
    url: baseUrl + "includes/select-subsidiaria-procesa.php",
    data: "subsidiaria=" + subsidiaria,
    success: function (res) {
      document.location.reload();
    },
  });
}

function actualizaSucursal(baseUrl, sucursal) {



  $.ajax({
    type: "POST",
    url: baseUrl + "includes/select-sucursal-procesa.php",
    data: "sucursal=" + sucursal,
    success: function (res) {
      document.location.reload();
    },
  });
}


	// Función para validar una dirección de correo electrónico
	function validarEmail(email) {
	  var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expresión regular para validar email
	  return re.test(email);
	}


	// Función para validar un RUT chileno
	 function validarRutChile(a) {
	  let a1 = a.replace("-", "");
	  console.log("a1: ", a1);
	  console.log("a1 length: ", a1.length);

	  if (a1.length !== 8 && a1.length !== 9) return false; // El RUT debe tener 8 o 9 caracteres

	  if (!/^[0-9]+-[0-9kK]{1}$/.test(a))
		  return !1;
	  var b = a.split("-")
		, c = b[1]
		, d = b[0];
	  return "K" == c && (c = "k"),
	  validaDv(d) == c
	}
	 function validaDv(a) {
	  for (var b = 0, c = 1; a; a = Math.floor(a / 10))
		  c = (c + a % 10 * (9 - b++ % 6)) % 11;
	  return c ? c - 1 : "k"
	}

	function formatRutChile(rut) {
	  // Remove any dots and hyphens
	  rut = rut.replace(/\./g, '').replace(/-/g, '');

	  // Split the RUT into body and verifier
	  let body = rut.slice(0, -1);
	  let verifier = rut.slice(-1);

	  // Add thousands separator
	  body = body.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

	  // Concatenate body and verifier with a hyphen
	  return `${body}-${verifier}`;
	}

// function actualizaTipoPersona(tipoPersona) {
//   // console.log("baseUrl: ", baseUrl);
//   console.log("tipoPersona DESDE actualizaTipoPersona: ", tipoPersona);

//   // Select the div element by its ID or class
//   var divInfoPersona = $("#infoPersona");
//   var divTipo1 = $("#tipoPersona1"); // Change 'yourDivId' to the actual ID of your div element
//   var divTipo2 = $("#tipoPersona2"); // Change 'yourDivId' to the actual ID of your div element

//   divInfoPersona.removeClass("d-none");

//   // Check the value of tipoPersona and change the class accordingly
//   if (tipoPersona == 1) {
//     // Add a class to the div
//     divTipo1.toggle("d-none");
//   }

//   if (tipoPersona == 2) {
//     divTipo1.toggle("d-none");
//   }
// }

function conteoInput(idInput, idSpanContador) {
  var currentLength = $("#" + idInput).val().length;
  var maxLength = $("#" + idInput).attr("maxlength");
  $("#" + idSpanContador).text(currentLength + "/" + maxLength);
} //function conteoInput()


//Método que formatea como Cuenta Contable: xx-xx-xx-xx-xx
function formatNroCtaContable(number) {
  // Convert the number to a string to manipulate it
  let numStr = number.toString();
  let formattedStr = "";

  // Reverse the string to start grouping from the end
  numStr = numStr.split("").reverse().join("");

  // Iterate through the string and add dashes after every two digits
  for (let i = 0; i < numStr.length; i++) {
    if (i > 0 && i % 2 === 0) {
      formattedStr += "-";
    }
    formattedStr += numStr[i];
  }

  // Reverse the formatted string to restore the original order
  formattedStr = formattedStr.split("").reverse().join("");

  return formattedStr;
}

function validarNumero(input) {
    // Obtener el valor actual del campo
    var valor = input.value;
    
    // Eliminar guiones y caracteres no numéricos
    var valorNumerico = valor.replace(/[^0-9-]/g, '');
    
    // Eliminar guiones adicionales (por ejemplo, si el usuario ingresó "--")
    valorNumerico = valorNumerico.replace(/-{2,}/g, '-');
    
    valorNumerico = valorNumerico.replace(/-/g, '');
    
    // Si el valor ha cambiado, actualizar el campo
    if (valor !== valorNumerico) {
        input.value = valorNumerico;
    }
}

function formateoNulos(text) {
  return !text || text === "" ? "-" : text;
}

function formateoDivisa(value, locale = "es-CL", currency = "CLP") {
  // Format the value using Intl.NumberFormat
  let formattedValue = new Intl.NumberFormat(locale, {
    style: "currency",
    currency,
  }).format(Math.abs(value));

  // If the value is negative, prepend the negative sign
  if (value < 0) {
    formattedValue = "-" + formattedValue;
  }

  return formattedValue;
}


function generarCodigoAutorizacionUsuario({
  length = 8,
  useUpperCase = true,
  useLowerCase = true,
  useNumbers = true,
  useSymbols = true,
}) {
  const DEFAULT_SYMBOLS = "!@#$%^&*()_+~\\`|}{[]:;?><,./-=";
  let KEYS = {
    upperCase: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
    lowerCase: "abcdefghijklmnopqrstuvwxyz",
    number: "0123456789",
    symbol: DEFAULT_SYMBOLS,
  };

  let availableCharacters = "";

  if (useUpperCase) {
    availableCharacters += KEYS.upperCase;
  }
  if (useLowerCase) {
    availableCharacters += KEYS.lowerCase;
  }
  if (useNumbers) {
    availableCharacters += KEYS.number;
  }
  if (useSymbols) {
    availableCharacters += KEYS.symbol;
  }

  if (availableCharacters.length === 0) {
    throw new Error("At least one character type should be selected to generate a password.");
  }

  let password = "";
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * availableCharacters.length);
    password += availableCharacters[randomIndex];
  }

  return password;
}




$(document).ready(function() {
  
  $('input[type="number"]').on('input', function() {
  
    // Obtener el valor del input
      var valor = $(this).val();
      
      // Eliminar cualquier carácter que no sea un número, un punto o una coma
      var valorFiltrado = valor.replace(/[^0-9.,]/g, '');
      
      // Asegurar que solo hay una coma decimal
      var partes = valorFiltrado.split(',');
      if (partes.length > 2) {
          valorFiltrado = partes[0] + ',' + partes.slice(1).join('');
      }
      
      // Asegurar que solo hay un punto de separación de miles por grupo de tres dígitos
      valorFiltrado = valorFiltrado.replace(/(\.\d{3})+\./g, '.');

      // Si el valor filtrado es diferente al original, actualizar el valor del input
      if (valor !== valorFiltrado) {
          $(this).val(valorFiltrado);
      }
  });
});