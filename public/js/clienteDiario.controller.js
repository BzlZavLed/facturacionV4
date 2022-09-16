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
