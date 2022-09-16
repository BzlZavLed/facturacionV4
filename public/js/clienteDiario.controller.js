$("#loading").hide();
function loadData(){
    var fechaIni = $("#dateStart").val();
    var fechaFin = $("#dateEnd").val();
    var fondoString = $("#fondo").val();
    fondoString = fondoString.split("-");
    var fondo = fondoString[0];
    var bunit = fondoString[1];
    document.getElementById("loading").style.display = "block";
    var arrdata = [];
                
    //$("#loading").show();
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: "http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php",
        type: "GET",
        dataType: "json",
        data: {
            bunit: bunit,
            fondo: fondo,
            start: fechaIni,
            end: fechaFin,
            id_proc: "getDiarios"
        },
        success: function(response) {
            $("#loading").hide();
            var tBody = "";
            response.forEach(element => {
                var date = JSON.parse(element.TRANS_DATETIME);
                let elementArr = [
                    element.JRNAL_TYPE.replace(/["']/g, ""),
                    element.JRNAL_NO,
                    element.JRNAL_LINE,
                    element.JRNAL_SRCE.replace(/["']/g, ""),
                    date.date.substring(0, 10),
                    element.PERIOD,
                    element.ACCNT_CODE.replace(/["']/g, ""),
                    element.AMOUNT.replace(/["']/g, ""),
                    element.ANAL_T0.replace(/["']/g, ""),
                    element.ANAL_T1.replace(/["']/g, ""),
                    element.ANAL_T2.replace(/["']/g, ""),
                    element.ANAL_T3.replace(/["']/g, ""),
                    element.ANAL_T4.replace(/["']/g, ""),
                    element.ANAL_T5.replace(/["']/g, ""),
                    element.ANAL_T6.replace(/["']/g, ""),
                    element.ANAL_T7.replace(/["']/g, ""),
                    element.ANAL_T8.replace(/["']/g, ""),
                    element.ANAL_T9.replace(/["']/g, ""),
                    element.TREFERENCE,
                    element.DESCRIPTN,
                    '<button class = "btn btn-success" id = "facturarMovimiento">Facturar</button>'
                ]
                arrdata.push(elementArr);
            });
             if ($.fn.DataTable.isDataTable("#diariostable")) {
                $("#diariostable").DataTable().destroy();
              }
    
            $("#diariostable tbody").empty();
            $('#diariostable').dataTable( {
                "paging": true,
                data:arrdata,
                "autoWidth": true,
			    dom: 'Bfrtip',
                columns: [
                    { title: "JRNAL_TYPE" },
                    { title: "JRNAL_NO" },
                    { title: "JRNAL_LINE" },
                    { title: "JRNAL_SRCE" },
                    { title: "TRANS_DATETIME" },
                    { title: "PERIOD" },
                    { title: "ACCNT_CODE" },
                    { title: "AMOUNT" },
                    { title: "ANAL_T0" },
                    { title: "ANAL_T1" },
                    { title: "ANAL_T2" },
                    { title: "ANAL_T3" },
                    { title: "ANAL_T4" },
                    { title: "ANAL_T5" },
                    { title: "ANAL_T6" },
                    { title: "ANAL_T7" },
                    { title: "ANAL_T8" },
                    { title: "ANAL_T9" },
                    { title: "TREFERENCE" },
                    { title: "DESCRIPTN" },
                    { title: "ACCIÓN" },
                  ],
            } );
            
        },
        error: function(error) {
            $("#loading").hide();
            console.log(error);
        }
    });
}
$("#submit").on('click', function() {
    loadData();
})
$("#diariostable").on("click", "#facturarMovimiento", function () {
    var $conceptos = $(this).closest("tr"),
    $valores = $conceptos.find("td");
    //console.log($valores);
    //console.log($valores[0]);
    console.log($valores[0].innerText);
    //console.log($valores[0].innerHTML);
    let factura = {
        "JRNAL_TYPE" : $valores[0].innerText,
        "JRNAL_NO" : $valores[1].innerText,
        "JRNAL_LINE" : $valores[2].innerText ,
        "JRNAL_SRCE" : $valores[3].innerText,
        "TRANS_DATETIME" : $valores[4].innerText,
        "PERIOD" : $valores[5].innerText,
        "ACCNT_CODE" :$valores[6].innerText,
        "AMOUNT" : $valores[7].innerText,
        "ANAL_T0" : $valores[8].innerText,
        "ANAL_T1" : $valores[9].innerText,
        "ANAL_T2" : $valores[10].innerText,
        "ANAL_T3" : $valores[11].innerText,
        "ANAL_T4" : $valores[12].innerText,
        "ANAL_T5" : $valores[13].innerText,
        "ANAL_T6" : $valores[14].innerText,
        "ANAL_T7" : $valores[15].innerText,
        "ANAL_T8" : $valores[16].innerText,
        "ANAL_T9" : $valores[17].innerText,
        "TREFERENCE" : $valores[18].innerText,
        "DESCRIPTN" : $valores[19].innerText
    }
    setCookie("facturaDiario",factura,1,true);
})