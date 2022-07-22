/**
     * Seleccionar emisor configuración
     */
$('#tableEmisor').on('click', '#selectEmisor', function () {
     loadForm('emisorForm', $(this),12);
    var $conceptos = $(this).closest("tr"),
        $valores = $conceptos.find("td");
    $("#rfc").val($valores[1].outerText);
    $("#password").val($valores[13].outerText);
});
$("#rfc_emisor").on('keyup',function() {
    $("#rfc").val($("#rfc_emisor").val());
});
