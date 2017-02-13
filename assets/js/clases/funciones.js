function Funciones() {
    this.mostrarToast = MostrarToast;
    this.configurarToastCentrado = configurarToastCentrado;
    this.redireccionarAlTerminarToast = redireccionarAlTerminarToast;
}

function MostrarToast(tipoToast, titulo, mensaje) {
    switch (tipoToast) {
        case 1:
            toastr.success(mensaje, titulo);
            break;
        case 2:
            toastr.warning(mensaje, titulo);
            break;
        default :
            toastr.success(mensaje, titulo);
            break;
    }
}

function configurarToastCentrado() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "5000",
        "hideDuration": "5000",
        "timeOut": "5000",
        "extendedTimeOut": "5000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "tapToDismiss": "false"
    }
}

function redireccionarAlTerminarToast(url) {
    toastr.options.onHidden = function () {
        console.log("onHide");
        window.location.href = url;
    };
}

var JsFunciones = new Funciones();
configurarToastCentrado();