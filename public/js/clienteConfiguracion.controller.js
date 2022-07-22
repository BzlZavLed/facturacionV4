/**
 * editar clientes en configuración NOMBRECLIENTE
 */
 $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('.nombreCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'nombreCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    },
    success: function (data) {
        console.log("Success");
        console.log(data);
    },
    validate: function (value) {
        console.log("Validate");
        console.log(value);
    }
});
/**
 * editar clientes en configuración RAZONCLIENTE
 */
$('.razonCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'razonCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    },
    success: function (data) {
        console.log("Success");
        console.log(data);
    },
    validate: function (value) {
        console.log("Validate");
        console.log(value);
    }
});
/**
 * editar clientes en configuración RFCCLIENTE
 */
$('.rfcCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'rfcCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    },
    success: function (data) {
        console.log("Success");
        console.log(data);
    },
    validate: function (value) {
        console.log("Validate");
        console.log(value);
    }
});
/**
 * editar clientes en configuración RFCCLIENTE
 */
 $('.emailCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'emailCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    },
    success : function (data){
        console.log("Success");
        console.log(data);
    }
});
/**
 * editar clientes en configuración RFCCLIENTE
 */
 $('.usoCfdiCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'usoCfdiCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    }
});
/**
 * editar clientes en configuración RFCCLIENTE
 */
 $('.personaFisicaCliente').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'personaFisicaCliente',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    }
});
/**
 * Truncated incorrect INTEGER value: 'data-value' 
 * (SQL: update `clientes` set `DomicilioFiscalReceptor` = 67515,
 *  `clientes`.`updated_at` = 2022-06-23 21:08:27 where `id` = data-value)
 * editar clientes en configuración DomicilioFiscalReceptor
 */
 $('.DomicilioFiscalReceptor').editable({
    mode: 'inline',
    container: 'body',
    url: "/clientesUpdate",
    title: 'DomicilioFiscalReceptor',
    value: {
        action: 'update'
    },
    ajaxOptions: {
        type: 'post',
    },
    error : function (data){
        console.log("Error");
        console.log(data);
    }
});