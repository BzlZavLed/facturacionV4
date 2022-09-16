/**GLOBAL VARS */
var row = "<tr>";
var err = "";
var response = [];
var rowConcepto = "<tr>";

/**
 * Funciones de trabajo
 */
function loopImpuestos(impuestosData, key, updateSubtotalesFlag) {
    for (
        var elements = 0;
        elements <= impuestosData[key].length - 1;
        elements++
    ) {
        var validateData = validateStringNotEmpty(
            impuestosData[key][elements].name,
            impuestosData[key][elements].value
        );

        if (validateData == 1) {
            if (impuestosData[key][elements].name == "Importe") {
                var number = parseFloat(impuestosData[key][elements].value);
                var amount =
                    "$" + number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                rowConcepto += "<td>" + amount + "</td>";
            } else {
                rowConcepto +=
                    "<td>" + impuestosData[key][elements].value + "</td>";
            }
            if (updateSubtotalesFlag != 1) {
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
function generateImpuestoIntoTable(impuestosData, type, updateSubtotalesFlag) {
    response = [];
    if (type != 1) {
        var key = impuestosData.length - 1;
        var linea = loopImpuestos(impuestosData, key, updateSubtotalesFlag);
    } else {
        var linea = "";
        rowConcepto = "<tr>";
        for (var key = 0; key <= impuestosData.length - 1; key++) {
            linea += loopImpuestos(impuestosData, key, updateSubtotalesFlag);
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
function loopConceptos(data, key, updateSubtotalesFlag) {
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
                    "$" + number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                row += "<td>" + amount + "</td>";
            } else {
                row += "<td>" + data[key][element].value + "</td>";
            }
            if (updateSubtotalesFlag != 1) {
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
function generateConceptoIntoTable(data, type, updateSubtotalesFlag) {
    response = [];
    if (type != 1) {
        var key = data.length - 1;
        var linea = loopConceptos(data, key, updateSubtotalesFlag);
    } else {
        var key = 0;
        var linea = "";
        for (key = 0; key <= data.length - 1; key++) {
            linea += loopConceptos(data, key, updateSubtotalesFlag);
        }
    }
    response["row"] = linea;
    response["error"] = err;
    return response;
}
/**
 * Validate string not empty
 * */
function validateStringNotEmpty(titulo, cadena) {
    if (cadena != "") {
        return true;
    } else {
        return titulo + " vacio";
    }
}
/**
 * Subtotal en view facturacionController
 * */
function updateSubtotales(cellid, cellvalue) {
    $("#" + cellid).html(
        "$" + cellvalue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,")
    ); //render a vista de subtotal
}
/**
 * Set label of given element
 * */
function setLabel(label, text) {
    document.getElementById(label).text = text;
    $("#" + label).text(text);
}
/**
 * Load concept form on facturacion when editing previously loaded concept
 * */
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
/**
 * create custom date format for facturacion \
 * */
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
/**
 * Get students for given RFC
 */
function getStudentForGivenRFC(rfc, bunit, fondo) {
    var response = [];
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php",
        type: "POST",
        data: {
            rfc: rfc,
            id_proc: "alumnoRfc",
        },
        dataType: "json",
        success: function (data) {
            if (data.length == 0) {
                Swal.fire({
                    title: "Error",
                    html: "No se encontraron alumnos con el RFC: " + rfc,
                    icon: "warning",
                });
                getAllStudentsRFCbyFondo(bunit, fondo);
            } else {
                data.forEach((element) => {
                    if (element.Nombre != false) {
                        $("#listAddendas").append(
                            "<option value='" +
                                element.Nombre.replace(/['"]+/g, "") +
                                "' idElement='" +
                                element.RFC.replace(/['"]+/g, "") +
                                "' nivel='" +
                                element.Nivel.replace(/['"]+/g, "") +
                                "' rvoe='" +
                                element.rvoe.replace(/['"]+/g, "") +
                                "' label='" +
                                element.RFC.replace(/['"]+/g, "") +
                                " - " +
                                element.Curp.replace(/['"]+/g, "") +
                                "' ></option>"
                        );
                    }
                });
            }
            return response;
        },
        error: function (data) {
            console.log(data);
        },
    });
}
/**
 * Get all students RFC by fondo
 */
function getAllStudentsRFCbyFondo(bunit, fondo) {
    var response = [];
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php",
        type: "POST",
        data: {
            bunit: bunit,
            fondo: fondo,
            id_proc: "allAlumnosByBunit",
        },
        dataType: "json",
        success: function (data) {
            //console.log(data);
            data.forEach((element) => {
                //console.log(element);
                if (element.Nombre != false) {
                    $("#listAddendas").append(
                        "<option value='" +
                            element.Nombre.replace(/['"]+/g, "") +
                            "' idElement='" +
                            element.RFC.replace(/['"]+/g, "") +
                            "' nivel='" +
                            element.Nivel.replace(/['"]+/g, "") +
                            "' rvoe='" +
                            element.rvoe.replace(/['"]+/g, "") +
                            "' label='" +
                            element.RFC.replace(/['"]+/g, "") +
                            " - " +
                            element.Curp.replace(/['"]+/g, "") +
                            "' ></option>"
                    );
                }
            });
            return response;
        },
        error: function (data) {
            console.log(data);
        },
    });
}
/**
 * Clear form
 */
function clearForm(idForm) {
    var conceptosForma = document.forms[idForm];
    var index = 0;
    $.each(conceptosForma, function () {
        conceptosForma.elements[index].value = "";
        index++;
    });
}
/**
 * Get bunit and fondo
 */
function getBunitAndFondo(selector) {
    var fondoData = $("#" + selector).val();
    console.log(fondoData);
    if (fondoData != null) {
        var data = fondoData.split("-");
        return {
            bunit: data[1],
            fondo: data[0],
        };
    } else {
        return {
            bunit: getCookie("bunit"),
            fondo: 10,
        };
    }
}
/**
 * parse Money to Float
 */
function parseMoneyToFloat(money) {
    var money = money.replace(/[^0-9\.]/g, "");
    money = parseFloat(money);
    return money.toFixed(2);
}
/**
 * Get cookies
 */
function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(";");
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == " ") {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
/**
 *Set cookies
 */
function setCookie(cname, cvalue, exdays,stringify) {
    if(stringify){
        cvalue = JSON.stringify(cvalue);
    }
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
/**
 * Encoding PDF
 */
function fromBinary(encoded) {
    const binary = atob(encoded);
    const bytes = new Uint8Array(binary.length);
    for (let i = 0; i < bytes.length; i++) {
      bytes[i] = binary.charCodeAt(i);
    }
    return String.fromCharCode(...new Uint16Array(bytes.buffer));
  }