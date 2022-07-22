/**GLOBAL VARS */
var row = "<tr>";
var err = "";
var response = [];
var rowConcepto = "<tr>";
    
/**
 * Funciones de trabajo
 */
function loopImpuestos(impuestosData,key,updateSubtotalesFlag){
    for (var elements = 0; elements <= impuestosData[key].length - 1; elements++) {
         var validateData = validateStringNotEmpty(
            impuestosData[key][elements].name,
            impuestosData[key][elements].value
        );
        
        if (validateData == 1) {
            if (impuestosData[key][elements].name == "Importe") {
                var number = parseFloat(impuestosData[key][elements].value);
                var amount =
                    "$" +
                    number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                rowConcepto += "<td>" + amount + "</td>";
            } else {
                rowConcepto +=
                    "<td>" + impuestosData[key][elements].value + "</td>";
            }
            if(updateSubtotalesFlag != 1){
                impuestosData[key][elements].name == "Importe"
                ? (iva += parseFloat(impuestosData[key][elements].value))
                : (iva = iva); //Calcular Impuestos de factura
                updateSubtotales("iva", iva); //render IVA in DOM
            }
           
          
        } else {
            err += "<li>" + validateData + "|Impuestos</li>";
        }
       
    }
    rowConcepto += "</tr>";
    return rowConcepto;
}
/**Function load impuesto into table */
function generateImpuestoIntoTable(impuestosData,type,updateSubtotalesFlag) {
    response = [];
    if(type != 1){
        var key = impuestosData.length-1;
        var linea = loopImpuestos(impuestosData,key,updateSubtotalesFlag);
    }else{
        var linea = "";
        rowConcepto = "<tr>";
        for (var key = 0; key <= impuestosData.length - 1; key++) {
            linea += loopImpuestos(impuestosData,key,updateSubtotalesFlag);
            console.log(linea);
        }
    }

    response["row"] = linea;
    response["error"] = err;
    return response;
}
/**
 * Loop to handle delete in table conceptos
 * */
function loopConceptos (data,key,updateSubtotalesFlag){
    const amountHeaders = ["valorUnitario", "importeConcepto", "descuento"];
    row = "<tr>";
    for (var element = 0; element <= data[key].length - 1; element++) {
        var validateData = validateStringNotEmpty(
            data[key][element].name,
            data[key][element].value
        );
        if (validateData == 1) {
            if (amountHeaders.includes(data[key][element].name)) {
                var number = parseFloat(data[key][element].value);
                var amount =
                    "$" +
                    number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                row += "<td>" + amount + "</td>";
            } else {
                row += "<td>" + data[key][element].value + "</td>";
            }
            if(updateSubtotalesFlag != 1){
                data[key][element].name == "importeConcepto"
                ? (subtotal += parseFloat(data[key][element].value))
                : (subtotal = subtotal); //Calcular subtotal de factura
            data[key][element].name == "descuento"
                ? (descuentosAmount += parseFloat(data[key][element].value))
                : (descuentosAmount = descuentosAmount); //Calcular subtotal de factura
            totalFactura = subtotal - descuentosAmount + iva;
            updateSubtotales("descuentosTotal", descuentosAmount);
            updateSubtotales("subtotalFactura", subtotal); //render subtotal in DOM
            updateSubtotales("totalFactura", totalFactura); //render total in DOM
            }
            
        } else {
            err += "<li>" + validateData + "</li>";
        }
    }
    row +=
    '<td><button class="btn btn-danger" id="borrarConcepto">Borrar</button>' +
    '<button class="btn btn-primary" id="editarConcepto">Editar</button></td>';
    row += "</tr>";
    return row;
}
/**
 * Function load concepto into table facturacion 
 * */
function generateConceptoIntoTable(data,type,updateSubtotalesFlag) {
    response = [];
    if(type != 1){
        var key = data.length-1;
        var linea = loopConceptos(data,key,updateSubtotalesFlag);
    }else{
        var key = 0;
        var linea = "";
        for (key = 0; key <= data.length - 1; key++) {
            linea += loopConceptos(data,key,updateSubtotalesFlag);
        }
        
    }
    response["row"] = linea;
    response["error"] = err;
    return response;
}
/**Validate string not empty */
function validateStringNotEmpty(titulo, cadena) {
    if (cadena != "") {
        return true;
    } else {
        return titulo + " vacio";
    }
}
/**Subtotal en view facturacionController */
function updateSubtotales(cellid, cellvalue) {
    $("#" + cellid).html(
        "$" + cellvalue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,")
    ); //render a vista de subtotal
}
function setLabel(label, text) {
    document.getElementById(label).text = text;
    $("#" + label).text(text);
}
function loadForm(nombreForma, tableRow, max) {
    var conceptosForma = document.forms[nombreForma];
    var $conceptos = tableRow.closest("tr"),
        $valores = $conceptos.find("td");
    $.each($valores, function (ind, value) {
        if (ind + 1 <= max) {
            conceptosForma.elements[ind + 1].value = $(this).text();
        }
    });
}
function timestampCFDI(rfcEmisor) {
    var pad = function (amount, width) {
        var padding = "";
        while (
            padding.length < width - 1 &&
            amount < Math.pow(10, width - padding.length - 1)
        )
            padding += "0";
        return padding + amount.toString();
    };
    var date = new Date();
    date = date ? date : new Date();
    var offset = date.getTimezoneOffset();
    if (rfcEmisor == "FEB000705CA2") {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours() - 2, 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    } else if (rfcEmisor == "FES020502DB1") {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours() - 1, 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    } else if (rfcEmisor == "FEO000726P45") {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours() - 1, 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    } else if (rfcEmisor == "FEN000707QDA") {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours() - 1, 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    } else if (rfcEmisor == "FEN000814AC9") {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours() - 2, 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    } else {
        return (
            pad(date.getFullYear(), 4) +
            "-" +
            pad(date.getMonth() + 1, 2) +
            "-" +
            pad(date.getDate(), 2) +
            "T" +
            pad(date.getHours(), 2) +
            ":" +
            pad(date.getMinutes(), 2) +
            ":" +
            pad(date.getSeconds(), 2)
        );
    }
}
