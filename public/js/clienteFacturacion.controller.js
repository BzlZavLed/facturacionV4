/**
 * Global variables
 */
var descuentosAmount = 0;
var subtotal = 0;
var iva = 0;
var totalFactura = 0;
var counterRow = 0;
var conceptosArray = [];
var impuestosArray = [];
/**
 *DOM Manipulation Emisor section in Facturacion View
 */
$("#razonSocialEmisor").on("change", function () {
    var fields = ["rfc_emisor", "regimen_emisor", "c_postal"];
    for (var i = 0; i <= fields.length - 1; i++) {
        var datavalue = $(this)
            .find('option[value="' + $(this).val() + '"]')
            .attr(fields[i]);
        $("#" + fields[i]).val(datavalue);
    }
});
/**
 * DOM Manipulation events in concept table
 */
$("#objImp").on("change", function () {
    var selected = $(this).val();
    if (selected == "002") {
        document.getElementById("impuestos").style.display = "block";
        document.getElementById("impuestosTable").style.display = "block";
    } else {
        document.getElementById("impuestosTable").style.display = "none";
        document.getElementById("impuestos").style.display = "none";
    }
});

$("#claveProductoServicio").on("input", function () {
    var datavalue = $("#claveProductoServicioList")
        .find('option[value="' + $(this).val() + '"]')
        .attr("label");
    $("#selectedclaveProductoServicio").text(datavalue);
});

$("#claveUnidadFacturacion").on("input", function () {
    var datavalue = $("#claveUnidadFacturacionList")
        .find('option[value="' + $(this).val() + '"]')
        .attr("label");
    $("#selectedclaveUnidadFacturacion").text(datavalue);
});

$("#valorUnitario").on("input", function () {
    var amount = $(this).val();
    var qty = $("#cantidadConcepto").val();
    $("#importeConcepto").val(amount * qty);
});
/**
 * Adding concepts to table row to pass to PHP script
 */
$("#addConcept").on("click", function (e) {
    e.preventDefault();
    counterRow++;
    var err = "";
    if ($("#objImp").val() == "002") {
        var impuestosData = $("#desgloseImpuestos").serializeArray();
        impuestosData.unshift({name:"id",value:counterRow});
        impuestosArray.push(impuestosData);
        var respImpuesto = generateImpuestoIntoTable(impuestosArray,0,0);
        var rowConcepto = respImpuesto["row"];
        err = respImpuesto["error"];
        $("#impuestosTabla > tbody").html("");
        if (err == "" || err == null) {
            $("#impuestosTabla").append(rowConcepto);
        }
       
    }
    var data = $("#conceptosFactura").serializeArray();
    data.unshift({name:"id",value:counterRow});
    conceptosArray.push(data);
    var dataForTable = generateConceptoIntoTable(conceptosArray,0,0);
    var row = dataForTable['row'];
    err = dataForTable['error'];
    if (err == "" || err == null) {
        rowConcepto += "</tr>";
        $("#conceptosFactura").trigger("reset");
        $("#desgloseImpuestos").trigger("reset");
        $("#selectedclaveProductoServicio").text("");
        $("#selectedclaveUnidadFacturacion").text("");
        $("#conceptosTable").append(row);
        document.getElementById("impuestos").style.display = "none";
    } else {
        err = "<ul>" + err + "</ul>";
        Swal.fire({
            title: "Error",
            html: err,
            icon: "warning",
        });
    }
});
/**
 * Editar concepto ya agregado a tabla
 */
$("#conceptosTable").on("click", "#editarConcepto", function () {
    var conceptosForma = document.forms["conceptosFactura"];
    var index = 0;
    var $conceptos = $(this).closest("tr"),
        $valores = $conceptos.find("td");
    $.each($valores, function () {
        cellValue = $(this).text();
        var elementNameForm = conceptosForma.elements[index].name;
        if (index == 5 || index == 6 || index == 7) {
            cellValue = cellValue.replace("$", "");
            cellValue = cellValue.replace(",", "");
            cellValue = parseFloat(cellValue);
        }

        if (
            elementNameForm == "claveProductoServicio" ||
            elementNameForm == "claveUnidadFacturacion"
        ) {
            var datavalue = $("#" + elementNameForm + "List")
                .find('option[value="' + cellValue + '"]')
                .attr("label");
            $("#selected" + elementNameForm).text(datavalue);
        }

        if (index == 6) {
            subtotal -= cellValue;
            updateSubtotales("subtotalFactura", subtotal); //render subtotal in DOM
        }
        conceptosForma.elements[index].value = cellValue;
        index++;
    });

    $(this).closest("tr").remove();
});
/**Actualizar tabla de totales cuando se elimina linea y actualizar ID de filas */
$("#conceptosTable").on("click", "#borrarConcepto", function () {
    var $conceptos = $(this).closest("tr"),
        $valores = $conceptos.find("td");
    let id = $valores[0].outerText;
    var resultConcepto = conceptosArray.filter(obj => {
        if(obj[0].name == "id" && obj[0].value == id){
            return obj;
        }
      })
    var impuestosConcepto = impuestosArray.filter(obj => {
        if(obj[0].name == "id" && obj[0].value == id){
            return obj;
        }
      })
    counterRow--;
    descuentosAmount -= parseFloat(resultConcepto[0][8].value);
    subtotal -= parseFloat(resultConcepto[0][7].value);
    
    if(impuestosConcepto.length > 0){
        var indexImpuesto = impuestosArray.findIndex(object => {
            return object[0].value === impuestosConcepto[0][0].value;
          });
        console.info("iva1: ",iva);
        iva -= parseFloat(impuestosConcepto[0][5].value);
        console.info("iva2: ",iva);
        updateSubtotales("iva", iva); //render IVA in DOM
        impuestosArray.splice(indexImpuesto, 1);
    }
    totalFactura = subtotal - descuentosAmount + iva;
    updateSubtotales("descuentosTotal", descuentosAmount);
    updateSubtotales("subtotalFactura", subtotal); //render subtotal in DOM
    updateSubtotales("totalFactura", totalFactura); //render total in DOM
     
    
    var indexConcepto = conceptosArray.findIndex(object => {
        return object[0].value === resultConcepto[0][0].value;
      });

    
    conceptosArray.splice(indexConcepto, 1);
    
    $("#conceptosTable > tbody").html("");
    $("#impuestosTabla > tbody").html("");
    
    var dataForTable = generateConceptoIntoTable(conceptosArray,1,1);
    var respImpuesto = (impuestosArray.length > 0) ? generateImpuestoIntoTable(impuestosArray,1,1):0;
    var rowConceptoNew = dataForTable["row"];
    var rowImpuestoNew = respImpuesto["row"];
    
   

    $("#impuestosTabla").append(rowImpuestoNew); 
    $("#conceptosTable").append(rowConceptoNew);

  
});
/**
 * Submit create client
 */
$("#createClienteForm").on("submit", function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/guardarCliente",
        type: "POST",
        data: $("#createClienteForm").serialize(),
        success: function (response) {
            setLabel("success-message", response.message);
        },
    });
});

/**
 * Get json from SEAWEBSERVICE for colegiaturas addendas
 */
$("#tipoAddenda").on("click", function (event) {
    event.preventDefault();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "https://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php?fondo=14&bunit=FES&alumnosbd=AlumnosP&financierobd=FinancieroP2021&facturacionbd=FacturacionP",
        type: "GET",
        success: function (response) {
            console.log(response);
        },
    });
});

$("#btnGenerarXml").on("click", function () {
    var cuerpo = {}; //Cuerpo del xml
    var conceptos = {}; //Conceptos del xml
    var forms = [
        "createClienteForm",
        "emisorForm",
        "datosGenerales",
        "cfdiRelacionado",
    ];
    var conceptDesct = [
        "Id",
        "ClaveProdServ",
        "NoIdentificacion",
        "Cantidad",
        "ClaveUnidad",
        "Descripcion",
        "ValorUnitario",
        "Importe",
        "Descuento",
        "ObjetoImpuestos",
    ];

    var table = document.getElementById("conceptosTable"); //Obtener tabla de conceptos
    var conceptosTable = [...table.rows].map((r) =>
        [...r.querySelectorAll("td ")].map((td) => td.textContent)
    ); //Obtener conceptos de tabla

    forms.forEach(function (form) {
        var formData = $("#" + form).serializeArray();
        formData.forEach(function (data) {
            cuerpo[data.name] = data.value;
        });
    });
    var subtotal = $("#subtotalFactura").text();
    subtotal = subtotal.replace("$", "");
    subtotal = subtotal.replace(",", ".");
    subtotal = parseFloat(subtotal);
    cuerpo["subtotal"] = subtotal.toFixed(2);
    cuerpo["fechaTimbre"] = timestampCFDI($("#rfc_emisor").val());

    conceptosTable.forEach((list, idx) => {
        //crear objeto de conceptos eliminando header
        var concepto = {};
        if (idx > 0) {
            list.forEach((data, idx) => {
                if (
                    conceptDesct[idx] == "Importe" ||
                    conceptDesct[idx] == "Descuento"
                ) {
                    data = data.replace("$", "");
                    data = data.replace(",", ".");
                    data = parseFloat(data);
                    data = data.toFixed(2);
                }
                concepto[conceptDesct[idx]] = data;
            });
            conceptos[idx] = concepto;
        }
    });
    cuerpo["conceptos"] = conceptos;
    console.log(cuerpo);
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "/timbrarFactura",
        type: "POST",
        data: { cuerpo },
        success: function (response) {
            console.log(response);
        },
        error: function (response) {
            console.log(response);
        },
    });
});
