/**
 * Created by PGARZAG on 29/08/2016.
 */
/*|||||||||||||||||||||||||||||||||||Load Form and forms|||||||||||||||||||||||||||||||||||||*/
var carouselProof;
var carouselRequest;
var carouselAnnouncement;
var carouselIdentification;
var carouselDebtor;
var carouselAgreement;

var base_url= "http://siscomcmg.com/uploads/";


function loadForm(idForm, type, idUsuario) {
    $('#titleFormsDetails').html('');
    $('#formsDetailsBody').html('');
    $('#titleFormsDetails').append('Detalle del Formulario' + '<input type="text" id="idReporte" value="' + idForm + '" hidden>'
    );

    if (type === undefined || type === null || type === 0) {
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadForm.php",
            data: {form: idForm, type: "Venta", idUsuario: idUsuario},
            dataType: "JSON",
            success: function (data) {
                console.log(data);
                var imgs = [];
                var imgsName = [];

                var fromId;
                var comments;
                var consecutive;
                var financialService;
                var lastName;
                var lastNameOp;
                var meeting;
                var name;
                var owner;
                var payment;
                var prospect;
                var requestNumber;

                for (var element in data) {

                    fromId = data[element].id;
                    comments = data[element].comments;
                    financialService = data[element].financialService;
                    consecutive = data[element].consecutive;

                    if (data[element].content === null && data[element].content === undefined) {
                        imgs.push("");
                        imgsName.push("");
                    } else {
                        imgs.push(data[element].content);
                        imgsName.push(data[element].nameImg);
                    }
                    //imgs.push(data[element].content);
                    //console.log(data[element].fileContent);
                    //imgsName.push(data[element].name);
                   
                    lastName = data[element].lastName;
                    lastNameOp = data[element].lastNameOp;
                    meeting = data[element].meeting;
                    name = data[element].name;
                    owner = data[element].owner;
                    payment = data[element].payment;
                    prospect = data[element].prospect;
                    requestNumber = data[element].requestNumber;
                }

                var nombreFinanciera = "";
                nombreFinanciera = (financialService == 1) ? "AYOPSA" : "MEXICANA DE GAS";
            /*
                if (financialService == 1) {
                    nombreFinanciera = "AYOPSA";
                } else {
                    nombreFinanciera = "MEXICANA DE GAS";
                }
            */

                var formaDePago = "";
                formaDePago = (payment == 1) ? "FINANCIADO" : "CONTADO";
            /*
                if (payment == 1) {
                    formaDePago = "FINANCIADO";
                } else {
                    formaDePago = "CONTADO";
                }
            */

                var consecutivo = "";
                consecutivo = (consecutive === null) ? "-" : "";
            /*
                if (consecutive === null) {
                    consecutivo = "-";
                } else {
                }
            */

                //var proofs = [];
                //var imgsProof;
                var elementsProof = "";
                var itemsProof = "";
                /*---------------------------*/
                var elementsProofOP = "";
                var itemsProofOP = "";
                //var identifications = [];
                //var imgsIdentification;
                var elementsIdentification = "";
                var itemsIdentification = "";
                /*---------------------------*/
                var elementsIdentificationOP = "";
                var itemsIdentificationOP = "";
                //var requests = [];
                //var imgsRequest;
                var elementsRequest = "";
                var itemsRequest = "";
                /*---------------------------*/
                var elementsRequestOP = "";
                var itemsRequestOP = "";
                //var debtors = [];
                //var imgsDebtor;
                var elementsDebtor = "";
                var itemsDebtor = "";
                /*---------------------------*/
                var elementsDebtorOP = "";
                var itemsDebtorOP = "";
                //var announcements = [];
                //var imgsAnnouncement;
                var elementsAnnouncement = "";
                var itemsAnnouncement = "";
                /*---------------------------*/
                var elementsAnnouncementOP = "";
                var itemsAnnouncementOP = "";
                //var agreements = [];
                //var imgsAgreement;
                var elementsAgreement = "";
                var itemsAgreement = "";
                /*---------------------------*/
                var elementsAgreementOP = "";
                var itemsAgreementOP = "";

                var zero = true;
                var first = true;
                var second = true;
                var third = true;
                var fourth = true;
                var fifth = true;
                var numElem = 1;

                for (var photo in imgs) {
                    //console.log(photo);
                    //console.log(imgs[photo]);
                    $content1 = imgs[photo];
                    //console.log(photo);
                    //console.log("Contenido de la venta");
                    //var photoType = imgs[photo].substr(0, imgs[photo].indexOf('_'));

                    /*if (imgs[photo].substr(0, imgs[photo].indexOf('_')) === null || imgs[photo].substr(0, imgs[photo].indexOf('_')) === undefined) {
                     photoType = "";
                     } else {
                     photoType = imgs[photo].substr(0, imgs[photo].indexOf('_'));
                     }*/
                    var photoType = "";
                    if (imgs[photo].lenght !== 0) {
                        var photoType = imgs[photo].substr(0, imgs[photo].indexOf('_'));
                    } else {
                        var photoType = imgs[photo].substr(0, imgs[photo].indexOf('_'));
                    }


                    //console.log (photoType);
                    var fileName = photoType.substr(photoType.indexOf('/') + 4);
                    //console.log (fileName);
                    var FileType = fileName.substr(fileName.indexOf('/') + 1);
                    //console.log (FileType);
                    var urlFinal = imgs[photo].substr(photoType.indexOf('/') + 4);
                    //console.log(urlFinal);

                    //console.log(photo);

                    //if (FileType == 'foto_solicitud') {
                    if (FileType == 'solicitud') {
                        /*if (photo == 0) {
                         elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '" class="active"></li>';

                         itemsRequest += '<div class="item active">';
                         itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" />';
                         itemsRequest += '</div>';
                         } else if (photo > 0) {
                         elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '"></li>';

                         itemsRequest += '<div class="item">';
                         itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" />';
                         itemsRequest += '</div>';
                         }*/
                        if (zero) {
                            elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '" class="active"></li>';

                            itemsRequest += '<div class="item active">';
                            itemsRequest += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                            itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" />';
                            itemsRequest += '</a>';
                            itemsRequest += '</div>';

                            elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '" class="active"></li>';

                            itemsRequestOP += '<div class="item active">';
                            itemsRequestOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                            itemsRequestOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="solicitud" />';
                            itemsRequestOP += '</a>';
                            itemsRequestOP += '</div>';

                            zero = false;
                        } else {
                            elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '"></li>';

                            itemsRequest += '<div class="item">';
                            itemsRequest += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                            itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px"/>';
                            itemsRequest += '</a>';
                            itemsRequest += '</div>';

                            elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '"></li>';

                            itemsRequestOP += '<div class="item">';
                            itemsRequestOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                            itemsRequestOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="solicitud" />';
                            itemsRequestOP += '</a>';
                            itemsRequestOP += '</div>';
                        }
                        numElem++;
                        //} else if (FileType == 'foto_avisoprivacidad') {
                    } else if (FileType == 'aviso') {
                        if (first) {
                            elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsAnnouncement += '<div class="item active">';
                            itemsAnnouncement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                            itemsAnnouncement += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px"/>';
                            itemsAnnouncement += '</a>';
                            itemsAnnouncement += '</div>';
                            elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsAnnouncementOP += '<div class="item active">';
                            itemsAnnouncementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                            itemsAnnouncementOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="avisoprivacidad" height="1024px" width="1248px"/>';
                            itemsAnnouncementOP += '</a>';
                            itemsAnnouncementOP += '</div>';

                            first = false;
                        } else {
                            elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '"></li>';
                            itemsAnnouncement += '<div class="item">';
                            itemsAnnouncement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                            itemsAnnouncement += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px"/>';
                            itemsAnnouncement += '</a>';
                            itemsAnnouncement += '</div>';
                            elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '"></li>';
                            itemsAnnouncementOP += '<div class="item">';
                            itemsAnnouncementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                            itemsAnnouncementOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="avisoprivacidad" height="1024px" width="1248px"/>';
                            itemsAnnouncementOP += '</a>';
                            itemsAnnouncementOP += '</div>';
                        }
                        numElem++;
                        //} else if (FileType == 'foto_identificacion') {
                    } else if (FileType == 'indentificacion') {
                        if (second) {
                            numElem = 1;
                            elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsIdentification += '<div class="item active">';
                            itemsIdentification += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                            itemsIdentification += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px" />';
                            itemsIdentification += '</a>';
                            itemsIdentification += '</div>';
                            elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsIdentificationOP += '<div class="item active">';
                            itemsIdentificationOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                            itemsIdentificationOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px" />';
                            itemsIdentificationOP += '</a>';
                            itemsIdentificationOP += '</div>';
                            second = false;
                        } else {
                            elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '"></li>';
                            itemsIdentification += '<div class="item">';
                            itemsIdentification += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                            itemsIdentification += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px"/>';
                            itemsIdentification += '</a>';
                            itemsIdentification += '</div>';
                            elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '"></li>';
                            itemsIdentificationOP += '<div class="item">';
                            itemsIdentificationOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                            itemsIdentificationOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px"/>';
                            itemsIdentificationOP += '</a>';
                            itemsIdentificationOP += '</div>';
                        }
                        numElem++;
                        //} else if (FileType == 'foto_comprobante') {
                    } else if (FileType == 'comprobante') {
                        if (third) {
                            numElem = 1;
                            elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsProof += '<div class="item active">';
                            itemsProof += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                            itemsProof += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="256px" width="256px"/>';
                            itemsProof += '</a>';
                            itemsProof += '</div>';
                            elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '" class="active"></li>';
                            itemsProofOP += '<div class="item active">';
                            itemsProofOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                            itemsProofOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="comprobante" height="1024px" width="1248px"/>';
                            itemsProofOP += '</a>';
                            itemsProofOP += '</div>';
                            third = false;
                        } else {
                            elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '"></li>';

                            itemsProof += '<div class="item">';
                            itemsProof += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                            itemsProof += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="256px" width="256px"/>';
                            itemsProof += '</a>';
                            itemsProof += '</div>';

                            elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '"></li>';

                            itemsProofOP += '<div class="item">';
                            itemsProofOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                            itemsProofOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="comprobante" height="1024px" width="1248px" />';
                            itemsProofOP += '</a>';
                            itemsProofOP += '</div>';
                        }
                        numElem++;
                        //} else if (FileType == 'foto_solicitud') {
                    } else if (FileType == 'contrato') {
                        if (fourth) {
                            numElem = 1;
                            elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsAgreement += '<div class="item active">';
                            itemsAgreement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                            itemsAgreement += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px"/>';
                            itemsAgreement += '</a>';
                            itemsAgreement += '</div>';

                            elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsAgreementOP += '<div class="item active">';
                            itemsAgreementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                            itemsAgreementOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px"/>';
                            itemsAgreementOP += '</a>';
                            itemsAgreementOP += '</div>';

                            fourth = false;
                        } else {
                            elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                            itemsAgreement += '<div class="item">';
                            itemsAgreement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                            itemsAgreement += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px"/>';
                            itemsAgreement += '</a>';
                            itemsAgreement += '</div>';

                            elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                            itemsAgreementOP += '<div class="item">';
                            itemsAgreementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                            itemsAgreementOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" class="imgToZoom" height="1024px" width="1248px" />';
                            itemsAgreementOP += '</a>';
                            itemsAgreementOP += '</div>';
                        }
                        numElem++;
                        //} else if (FileType == 'foto_pagare') {
                    } else if (FileType == 'pagare') {
                        if (fifth) {
                            numElem = 1;
                            elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsDebtor += '<div class="item active">';
                            itemsDebtor += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                            itemsDebtor += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px"/>';
                            itemsDebtor += '</a>';
                            itemsDebtor += '</div>';

                            elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                            itemsDebtorOP += '<div class="item active">';
                            itemsDebtorOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                            itemsDebtorOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" height="1024px"/>';
                            itemsDebtorOP += '</a>';
                            itemsDebtorOP += '</div>';

                            fifth = false;
                        } else {
                            elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                            itemsDebtor += '<div class="item">';
                            itemsDebtor += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                            itemsDebtor += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px"/>';
                            itemsDebtor += '</a>';
                            itemsDebtor += '</div>';

                            elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                            itemsDebtorOP += '<div class="item">';
                            itemsDebtorOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                            itemsDebtorOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px" />';
                            itemsDebtorOP += '</a>';
                            itemsDebtorOP += '</div>';
                        }
                        numElem++;
                    }
                }

                if (prospect == 1) {
                    prospect = "S&iacute;";
                } else if (prospect == 0) {
                    prospect = "No";
                }
                if (owner == 1) {
                    owner = "S&iacute;";
                } else if (owner == 0) {
                    owner = "No";
                }

                carouselProof = '<div id="myCarouselOP" class="carousel slide"><div class="carousel-inner">' + itemsProofOP + '</div>' + '<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarouselOP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                carouselRequest = '<div id="myCarousel2OP" class="carousel slide"><div class="carousel-inner">' + itemsRequestOP + '</div>' + '<a class="left carousel-control" href="#myCarousel2OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                carouselAnnouncement = '<div id="myCarousel3OP" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel3OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                carouselIdentification = '<div id="myCarousel4OP" class="carousel slide"><div class="carousel-inner">' + itemsIdentificationOP + '</div>' + '<a class="left carousel-control" href="#myCarousel4OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                carouselDebtor = '<div id="myCarousel5OP" class="carousel slide"><div class="carousel-inner">' + itemsDebtorOP + '</div>' + '<a class="left carousel-control" href="#myCarousel5OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                carouselAgreement = '<div id="myCarousel6OP" class="carousel slide"><div class="carousel-inner">' + itemsAgreementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel6OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';

                if (consecutive == null || consecutive == undefined) {
                    consecutive = "-";
                }

                $('#formsDetailsBody').append('<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                    + '<label>Cliente interesado en contratar el servicio?</label>' + '<input class="form-control" type="text" id="prospect" value="' + prospect + '">'
                    + '<input type="text" id="formID" value="' + fromId + '" hidden>'
                    + '<label>Motivo del desinter&eacute;s</label>' + '<input class="form-control" type="text" id="uninteresed" value="">'
                    + '<label>Comentarios</label>' + '<input class="form-control" style="height: 70px; !important" type="text" id="comments" value="' + comments + '">'

                    + '<label>Se encuentra el titular?</label>' + '<input class="form-control" type="text" id="owner" value="' + owner + '">' + '<br/>'
                    + '<label>Consecutivo</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="consecutive" value="' + consecutivo + '">' + '<br/>' + '<div class="col-md-12">' + '<div class="col-md-6">'

                    + '<label>N&uacute;mero de contrato</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="ID" value="' + requestNumber + '">'
                    + '<label>Apellido paterno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastName" value="' + lastName + '">'
                    + '<label>Financiera</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="financialService" value="' + nombreFinanciera + '">' + '</div>' + '<div class="col-md-6">'

                    + '<label>Nombre</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="name" value="' + name + '">'
                    + '<label>Apellido materno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastNameOp" value="' + lastNameOp + '">'
                    + '<label>Forma de pago</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="payment" value="' + formaDePago + '">' + '</div>' + '</div>' + '</div>' + '<div class="col-md-6"><h4>DOCUMENTOS</h4>'
                    + '<div class="col-md-12">'
                    + '<div class="col-md-6">'

                    + '<label>Comprobante de domicilio</label>' + '<br/>'
                    + '<a onclick="showImages(1);"><div id="myCarousel" class="carousel slide"><div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<label>Solicitud</label>' + '<br/>'
                    + '<a onclick="showImages(2);"><div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsRequest + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<label>Aviso de privacidad</label>' + '<br/>'
                    + '<a onclick="showImages(3);"><div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncement + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '</div>'

                    + '<div class="col-md-6">'

                    + '<label>Identificaci&oacute;n</label>' + '<br/>'
                    + '<a onclick="showImages(4);"><div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsIdentification + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '<label>Pagar&eacute;</label>' + '<br/>'
                    + '<a onclick="showImages(5);"><div id="myCarousel5" class="carousel slide"><div class="carousel-inner">' + itemsDebtor + '</div>' + '<a class="left carousel-control" href="#myCarousel5" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '<label>Contrato</label>' + '<br/>'
                    + '<a onclick="showImages(6);"><div id="myCarousel6" class="carousel slide"><div class="carousel-inner">' + itemsAgreement + '</div>' + '<a class="left carousel-control" href="#myCarousel6" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '</div>'
                    + '<div class="col-md-12">' + '<div class="col-md-5">&nbsp;</div>' + '<div class="col-md-4">&nbsp;</div>' + '<div class="col-md-3">'
                    + '<br/><br/><button type="button" class="btn btn-info" onclick="sellValidation();">VALIDAR</button>' + '</div>' + '</div>' + '</div>' + '</div>' + '</div>');

                sellFormToValidate = '<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                    + '<label>Cliente interesado en contratar el servicio?</label>' + '<input class="form-control" type="text" id="prospect" value="' + prospect + '">'
                    + '<input type="text" id="formID" value="' + fromId + '" hidden>'

                    + '<label>Motivo del desinter&eacute;s</label>' + '<input class="form-control" type="text" id="uninteresed" value="' + comments + '">'
                    + '<label>Comentarios</label>' + '<textarea class="form-control" rows="4" cols="50" type="text" id="comments">' + comments + '</textarea>'
                    + '<label>Se encuentra el titular?</label>' + '<input class="form-control" type="text" id="owner" value="' + owner + '">' + '<br/>'
                    + '<label>Consecutivo</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="consecutive" value="' + fromId + '">' + '<br/>' + '<div class="col-md-12">' + '<div class="col-md-6">'

                    + '<label>N&uacute;mero de contrato</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="ID" value="' + requestNumber + '">'
                    + '<label>Apellido paterno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastName" value="' + lastName + '">'
                    + '<label>Financiera</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="financialService" value="' + nombreFinanciera + '">' + '</div>' + '<div class="col-md-6">'

                    + '<label>Nombre</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="name" value="' + name + '">'
                    + '<label>Apellido materno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastNameOp" value="' + lastNameOp + '">'
                    + '<label>Forma de pago</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="payment" value="' + payment + '">' + '</div>' + '</div>' + '</div>'
                    + '<div class="col-md-6"><h4>DOCUMENTOS</h4>'
                    + '<div class="col-md-12">'
                    + '<div class="col-md-6">'

                    + '<input type="checkbox" name="trustedImage" id="trustedImage"> Comprobante de domicilio' + '<br/>'
                    + '<a onclick="showImages(1);"><div id="myCarousel" class="carousel slide"><div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<input type="checkbox" name="requestImage" id="requestImage"> Solicitud' + '<br/>'
                    + '<a onclick="showImages(2);"><div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsRequest + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<input style="background-color: green;" type="checkbox" name="privacyImage" id="privacyImage"> Aviso de privacidad' + '<br/>'
                    + '<a onclick="showImages(3);"><div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncement + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '</div>'

                    + '<div class="col-md-6">'
                    + '<input type="checkbox" name="identificationImage" id="identificationImage"> Identificaci&oacute;n' + '<br/>'
                    + '<a onclick="showImages(4);"><div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsIdentification + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<input type="checkbox" name="payerImage" id="payerImage"> Pagar&eacute;' + '<br/>'
                    + '<a onclick="showImages(5);"><div id="myCarousel5" class="carousel slide"><div class="carousel-inner">' + itemsDebtor + '</div>' + '<a class="left carousel-control" href="#myCarousel5" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                    + '<input type="checkbox" name="agreegmentImage" id="agreegmentImage"> Contrato' + '<br/>'
                    + '<a onclick="showImages(6);"><div id="myCarousel6" class="carousel slide"><div class="carousel-inner">' + itemsAgreement + '</div>' + '<a class="left carousel-control" href="#myCarousel6" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                    + '</div></div>'
                    + '<br/><br/>'
                    + '<h4>MOTIVOS DE RECHAZO</h4>'
                    + '<div class="col-md-12"><br/>'
                    + '<div class="col-md-8">' + '<select id="rejectedReasons"></select>' + '</div>'
                    + '<div class="col-md-8>' + '<img src="assets/icons/agregar.png" onclick="addReason();" height="42px" width="42px">' + '</div>'
                    + '</div>'
                    + '<br/><br/><br/><br/>'
                    + '<div class="col-md-12">'
                    + '<div class="col-md-10">'
                    + '<table id="tableReasons" style="width:100%">'
                    + '<tr>'
                    + '<th style="background-color: black; color: white;" colspan="2"> Motivos de rechazo</th>'
                    + '</tr>'
                    + '</table>'
                    + '</div>'
                    + '<div class="col-md-2">&nbsp;</div>'
                    + '</div>'
                    + '<br/>'
                    + '<div class="col-md-12">&nbsp;</div>'
                    + '<div class="col-md-12">&nbsp;</div>'
                    + '<div class="col-md-12">&nbsp;</div>'
                    + '<div class="col-md-12">'
                    + '<div class="col-md-4">&nbsp;</div>'
                        //+ '<div class="col-md-4">' + '<button type="button" class="btn btn-danger" id="btnCancel">RECHAZADO</button>' + '</div>'
                    + '<div class="col-md-4">&nbsp;</div>'
                    + '<div class="col-md-4">' + '<button type="button" class="btn btn-primary" id="btnSendSell">VALIDAR</button>' + '</div>'
                    + '</div>'
                    + '</div>' + '</div>';

                $('#formsDetails').modal('show');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                alert(textStatus);
            },
            complete: function (XMLHttpRequest, status) {
            }
        });
    } else {
        var type2 = type;
        $.ajax({
            method: "POST",
            url: "dataLayer/callsWeb/loadForm.php",
            data: {form: idForm, type: type, idUsuario: idUsuario},
            dataType: "JSON",
            success: function (data) {
                //alert(JSON.stringify(data));
                console.log('data venta', data);
                if (data.length == 0) {
                    sizeData = data.length;
                    MostrarToast(2, "Formulario Vacío", "Debe crear el formulario respectivo para visualizar la información");
                } else {

                    console.log('type2', type2);
                    var imgs = [];
                    var imgsName = [];

                    if (type2 == "Censo") {

                        $('#titleFormsDetails').html('');
                        $('#titleFormsDetails').append('Detalle Censo');

                        var fromId;
                        var fileContent;
                        var lote;
                        var houseStatus;
                        var nivel;
                        var giro;
                        var acometida;
                        var observacion;
                        var tapon;
                        var medidor;
                        var marca;
                        var tipo;
                        var serialNumber;
                        var niple;

                        for (var element in data) {
                            console.log(data[element]);
                            
                            fromId = data[element].id;
                            serialNumber = data[element].NoSerie;
                            acometida = data[element].acometida;

                            if (data[element].name === null || data[element].name === undefined) {
                                imgs.push("");
                            } else {
                                imgs.push(data[element].name);
                            }

                            
                            giro = data[element].giro;
                            if (giro == null) {
                                giro = "-";
                            }
                            //console.log(data[element].houseStatus);
                            houseStatus = data[element].houseStatus;
                            //console.log(data[element].lote);
                            lote = data[element].lote;
                            //console.log(data[element].marca);
                            marca = data[element].marca;
                            //console.log(data[element].medidor);
                            medidor = data[element].medidor;
                            //console.log(data[element].niple);
                            niple = data[element].niple;
                            //console.log(data[element].nivel);
                            nivel = data[element].nivel;
                            //console.log(data[element].observacion);
                            observacion = data[element].observacion;
                            //console.log(data[element].tapon);
                            tapon = data[element].tapon;
                            //console.log(data[element].tipo);
                            tipo = data[element].tipo;
                        }

                        var acometidas = [];
                        var imgsAcometida;

                        var elementsAcometida = "";
                        var itemsAcometida = "";
                        var elementsAcometidaOP = "";
                        var itemsAcometidaOP = "";


                        var measurers = [];
                        var imgsMeasurers;

                        var elementsMeasurer = "";
                        var itemsMeasurer = "";
                        var elementsMeasurerOP = "";
                        var itemsMeasurerOP = "";


                        var first = true;

                        var zero = true;
                        var first = true;
                        var numElem = 1;

                        if (imgs.length > 0) {
                            for (var photo in imgs) {
                                console.log("photo=>" + photo);
                                console.log("photo=>" + imgs[photo]);

                                var FileType = getFileType(imgs[photo]);
                                var urlFinal = imgs[photo];

                                if (FileType == 'acometida') {
                                    if (zero) {
                                        elementsAcometida += '<li data-target="#myCarousel" data-slide-to="' + photo + '" class="active"></li>';

                                        itemsAcometida += '<div class="item active">';
                                        itemsAcometida += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                        itemsAcometida += '<img src="' + base_url + urlFinal + '" alt="Acometida" height="256px" width="256px" style="" />';
                                        itemsAcometida += '</a>';
                                        itemsAcometida += '</div>';

                                        elementsAcometidaOP += '<li data-target="#myCarouselOP" data-slide-to="' + photo + '" class="active"></li>';

                                        itemsAcometidaOP += '<div class="item active">';
                                        itemsAcometidaOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                        itemsAcometidaOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="Acometida" class="imgToZoom" height="1024px" width="1248px" style="" />';
                                        itemsAcometidaOP += '</a>';
                                        itemsAcometidaOP += '</div>';
                                        zero = false;
                                    } else {
                                        elementsAcometida += '<li data-target="#myCarousel" data-slide-to="' + photo + '"></li>';

                                        itemsAcometida += '<div class="item">';
                                        itemsAcometida += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                        itemsAcometida += '<img src="' + base_url + urlFinal + '" alt="Acometida" height="256px" width="256px" style="" />';
                                        itemsAcometida += '</a>';
                                        itemsAcometida += '</div>';

                                        elementsAcometidaOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '"></li>';

                                        itemsAcometidaOP += '<div class="item">';
                                        itemsAcometidaOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                        itemsAcometidaOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="Acometida" class="imgToZoom" height="1024px" width="1248px" style="" />';
                                        itemsAcometidaOP += '</a>';
                                        itemsAcometidaOP += '</div>';
                                    }
                                    numElem++;
                                } else if (FileType == 'measurer') {
                                    var numElem = 1;
                                    if (first) {
                                        elementsMeasurer += '<li data-target="#myCarousel2" data-slide-to="' + numElem + '" class="active"></li>';

                                        itemsMeasurer += '<div class="item active">';
                                        itemsMeasurer += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                        itemsMeasurer += '<img src="' + base_url + urlFinal + '" alt="Measurers" height="256px" width="256px" style="" />';
                                        itemsMeasurer += '</a>';
                                        itemsMeasurer += '</div>';

                                        elementsMeasurerOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '" class="active"></li>';

                                        itemsMeasurerOP += '<div class="item active">';
                                        itemsMeasurerOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                        itemsMeasurerOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="Acometida" class="imgToZoom" height="1024px" width="1248px" style="" />';
                                        itemsMeasurerOP += '</a>';
                                        itemsMeasurerOP += '</div>';
                                        first = false;
                                    } else {
                                        elementsMeasurer += '<li data-target="#myCarousel2" data-slide-to="' + numElem + '" class="active"></li>';

                                        itemsMeasurer += '<div class="item">';
                                        itemsMeasurer += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                        itemsMeasurer += '<img src="' + base_url + urlFinal + '" alt="Measurers" height="256px" width="256px" style="" />';
                                        itemsMeasurer += '</a>';
                                        itemsMeasurer += '</div>';

                                        elementsMeasurerOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '" class="active"></li>';

                                        itemsMeasurerOP += '<div class="item">';
                                        itemsMeasurerOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                        itemsMeasurerOP += '<img src="' + base_url + urlFinal + '" data-zoom-image="' + base_url + urlFinal + '" alt="Acometida" class="imgToZoom" height="1024px" width="1248px" style="" />';
                                        itemsMeasurerOP += '</a>';
                                        itemsMeasurerOP += '</div>';
                                    }
                                    numElem++;
                                }
                            }
                        }

                        carouselAcometida = '<div id="myCarouselOP" class="carousel slide"><div class="carousel-inner">' + itemsAcometidaOP + '</div>' + '<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarouselOP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselMeasurer = '<div id="myCarousel2OP" class="carousel slide"><div class="carousel-inner">' + itemsMeasurerOP + '</div>' + '<a class="left carousel-control" href="#myCarousel2OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';

                        if (tapon == 1) {
                            tapon = "Verde";
                        } else if (tapon == 0) {
                            tapon = "Rojo";
                        }

                        if (acometida == 1) {
                            acometida = "S&iacute;";
                        } else if (acometida == 0) {
                            acometida = "No";
                        }

                        if (medidor == 1) {
                            medidor = "S&iacute;";
                        } else if (medidor == 0) {
                            medidor = "No";
                        }

                        if (niple == 1) {
                            niple = "S&iacute;";
                        } else if (niple == 0) {
                            niple = "No";
                        }

                        $('#formsDetailsBody').append('<div class="row"><div class="col-lg-6"></div></div>' + '</div>' + '<br/>' + '<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                            + '<input type="text" id="" value="' + fromId + '" hidden disabled>' + '<label>Tipo de lote</label>'
                            + '<input class="form-control" type="text" id="" value="' + lote + '" disabled>' + '<label>Estatus de vivienda</label>'
                            + '<input class="form-control" type="text" id="" value="' + houseStatus + '" disabled>' + '<label>Niveles socioecon&oacute;nimos de la vivienda(NSE)</label>'
                            + '<input class="form-control" style="width: 40px; !important" type="text" id="" value="' + nivel + '" disabled>' + '<label>Giro</label>'
                            + '<input class="form-control" type="text" id="" value="' + giro + '" disabled>' + '<br/>'
                            + '<label>Acometida</label>&nbsp;&nbsp;'
                            + '<input style="width: 55px; height: 34px; !important" type="text" id="" value="' + acometida + '" disabled>' + '<div class="col-md-12">'
                            + '<div class="col-md-4">' + '</div>' + '<div class="col-md-8">'
                            + '<a onclick="showImagesCensus(1);"><div id="myCarousel" class="carousel slide">' + '<ol class="carousel-indicators">' + elementsAcometida + '</ol>' + '<div class="carousel-inner">' + itemsAcometida + '</div>'
                            + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>'
                            + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>' + '</div>'
                            + '</div>' + '<br/><br/>' + '<label>Observaciones</label>' + '<input class="form-control" style="height: 100px; !important" type="text" id="" value="'
                            + observacion + '" disabled>' + '</div>' + '<div class="col-md-6">' + '<label>Color de Tap&oacute;n</label>' + '<br/>'
                            + '<input type="text" class="form-control" style="width: 90px; !important" id="" value="' + tapon + '" disabled>'
                            + '<label>Medidor</label>' + '<br/>'
                            + '<input type="text" style="width: 70px; height: 34px; !important" id="" value="' + medidor + '" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
                            + '<div class="col-md-12">' + '<div class="col-md-4">' + '</div>' + '<div class="col-md-8">' + '<a onclick="showImagesCensus(2);"><div id="myCarousel2" class="carousel slide">'
                            + '<ol class="carousel-indicators">' + elementsMeasurer + '</ol>'
                            + '<div class="carousel-inner">' + itemsMeasurer + '</div>'
                            + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>'
                            + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>' + '</div>'
                            + '</div>' + '<br/><br/>' + '<label>Marca de medidor</label>' + '<input class="form-control" type="text" id="" value="' + marca + '" disabled>'
                            + '<label>Tipo de medidor</label>' + '<input class="form-control" type="text" id="" value="' + tipo + '" disabled>' + '<label>No. de Serie</label>'
                            + '<br/>' + '<input type="text" class="form-control" style="width: 200px; !important" id="" value="' + serialNumber + '" disabled>' + '<label>Niple de corte</label>'
                            + '<br/>' + '<input type="text" class="form-control" style="width: 50px; !important" id="" value="' + niple + '" disabled>' + '</div>' + '</div>' + '</div>');
                    } else if (type2 == "Plomero") {
                        console.log("TipoPlomero data",data);
                        if ((typeof(data[0].code) !== 'undefined' && data[0].code === '500') &&
                            (data[0].status === 'BAD')) {
                            console.log('no hay formulario');
                        }else{
                            var formId,namePerso,lastName,lastNameM,
                                request,documentNumber,tapon,ri,
                                observations,newPipe,content,ph,
                                pipesCount,path,
                                fall,htmlAppend,diagram,img1, img2;
                            formId = (data[0].consecutive === '' || typeof(data[0].consecutive) === 'undefined' || typeof(data[0].consecutive) === null) ? '' : data[0].consecutive;
                            namePerso = (data[0].namePerso === '' || typeof(data[0].namePerso) === 'undefined' || typeof(data[0].namePerso) === null) ? '' : data[0].namePerso;
                            lastName = (data[0].lastName === '' || typeof(data[0].lastName) === 'undefined' || typeof(data[0].lastName) === null) ? '' : data[0].lastName;
                            request =(data[0].request === '' || typeof(data[0].request) === 'undefined' || typeof(data[0].request) === null) ? '' : data[0].request;
                            documentNumber =(data[0].dictamen === '' || typeof(data[0].dictamen) === 'undefined' || typeof(data[0].dictamen) === null) ? '' : data[0].dictamen;
                            tapon =(data[0].tapon === '' || typeof(data[0].tapon) === 'undefined' || typeof(data[0].tapon) === null) ? '' : data[0].tapon;
                            ri =(data[0].ri === '' || typeof(data[0].ri) === 'undefined' || typeof(data[0].ri) === null) ? '' : data[0].ri;
                            observations =(data[0].observaciones === '' || typeof(data[0].observaciones) === 'undefined' || typeof(data[0].observaciones) === 'null') ? '' : data[0].observaciones;
                            newPipe =(data[0].newPipe === '' || typeof(data[0].newPipe) === 'undefined' || typeof(data[0].newPipe) === null) ? '' : data[0].newPipe;
                            ph =(data[0].resultadoPH === '' || typeof(data[0].resultadoPH) === 'undefined' || typeof(data[0].resultadoPH) === null) ? '' : data[0].resultadoPH;
                            pipesCount =(data[0].numTomas === '' || typeof(data[0].numTomas) === 'undefined' || typeof(data[0].numTomas) === null) ? '' : data[0].numTomas;
                            diagram =(data[0].diagram === '' || typeof(data[0].diagram) === 'undefined' || typeof(data[0].diagram) === null) ? 'uploads/diagramas/not-available.png' : 'diagramas/d_'+data[0].diagram+'.png';
                            tapon = (tapon === 1 ) ? 'Verde' : 'Rojo';
                            newPipe = (newPipe === 1 ) ? 'Positivo' : 'Negativo';
                            ph = (ph === 1 ) ? 'Positivo' : 'Negativo';
                            ri = (ri === 1 ) ? 'Si' : 'No';
                            console.log('data[0].arrIMG.length', data[0].arrIMG.length);
                            if (data[0].arrIMG.length === 1) {
                                img1 =(data[0].arrIMG[0].nameIMG === '' || typeof(data[0].arrIMG[0].nameIMG) === 'undefined' || typeof(data[0].arrIMG[0].nameIMG) === null) ? 'diagramas/not-available.png' : data[0].arrIMG[0].nameIMG;
                                img2 = 'diagramas/not-available.png';
                            }else if (data[0].arrIMG.length === 2) {
                                img1 =(data[0].arrIMG[0].nameIMG === '' || typeof(data[0].arrIMG[0].nameIMG) === 'undefined' || data[0].arrIMG[0].nameIMG === null) ? 'diagramas/not-available.png' : data[0].arrIMG[0].nameIMG;
                                img2 =(data[0].arrIMG[1].nameIMG === '' || typeof(data[0].arrIMG[1].nameIMG) === 'undefined' || data[0].arrIMG[1].nameIMG === null) ? 'diagramas/not-available.png' : data[0].arrIMG[1].nameIMG;
                            }else {
                                img1 = 'diagramas/not-available.png';
                                img2 = 'diagramas/not-available.png';
                            }
                           
                            var html = '<div class="row">';
                                html += '<div class="col-lg-12">'
                                     html += '<div class="col-md-6">'
                                        html += '<label>Consecutivo</label>';
                                        html += '<input class="form-control" type="text" id="" value="' + formId + '">';
                                        html += '<div class="col-md-12">';
                                            html += '<div class="col-md-6">';
                                                html += '<label>Nombre</label>';
                                                html += '<input class="form-control" type="text" id="" value="' + namePerso + '">';
                                            html +='</div>';
                                            html += '<div class="col-md-6">';
                                                html += '<label>Apellido Paterno</label>';
                                                html += '<input class="form-control" type="text" id="" value="' + lastName + '">';
                                            html += '</div>';
                                        html += '</div>';
                                        html += '<br/>';
                                        html += '<label>N&uacute;mero de contrato</label>';
                                        html += '<input class="form-control" type="text" id="" value="' + request + '">';
                                        html += '<label>N&uacute;mero de dictamen t&eacute;cnico</label>';
                                        html += '<input class="form-control" type="text" id="" value="' + documentNumber + '">';
                                        html += '<br/><br/>';
                                       
                                        html +='<div class="col-md-12">';
                                            html +='<div class="col-md-6">';
                                                html +='<label>Color del tap&oacute;n</label>';
                                                html +='<input class="form-control" type="text" id="" value="' + tapon + '">';
                                            html +='</div>'
                                            html +='<div class="col-md-6">';
                                                html +='<label>RI menor a 40 mts?</label>';
                                                html +='<input class="form-control" type="text" id="" value="' + ri + '">';
                                            html +='</div>';
                                        html +='</div>';
                                        html +='<br/><br/>';
                                        
                                        html +='<label>Diagrama isom&eacute;trico</label><br/>';
                                        
                                        html += '<a class="example-image-link" href="' + base_url + diagram + '" data-lightbox="example-Isometrico" >';
                                            html +='<img src="'+base_url +diagram+'" height="128px" width="128px" alt="Diagrama Isometrico">';
                                        html += '</a>';
                                        
                                        html +='<br/>';
                                        html += '<label>Observaciones</label>';
                                        html +='<input class="form-control" style="height: 65px; !important;" type="text" id="" value="' + observations + '">';
                                    html +='</div>'
                                       
                                    html+='<div class="col-md-6">';
                                        html+='<label>C&aacute;lculo de ca&iacute;da de presi&oacute;n</label>';
                                        html+='<table class="table table-condensed">'
                                            html+= '<thead>';
                                            html+='<tr>';
                                            html+='<th>#</th>';
                                            html+='<th>Tramo</th>';
                                            html+='<th>Distancia</th>';
                                            html+='<th>Tuber&iacute;a</th>';
                                            html+='<th>% ca&iacute;da</th>';
                                            html+='</tr>';
                                            html+='</thead>';
                                            html+='<tbody id="detPlumbForm">';
                                            html+='</tbody>';
                                        html+='</table>';
                                    html+='<br/><br/><br/>';  
                                    
                                    
                                    html+= '<div class="col-md-12">'; 
                                        html+='<div class="col-md-6">';
                                            html+= '<label>Se requiere tuber&iacute;a?</label>';
                                            html+='<input class="form-control" style="width: 160px; !important;" type="text" id="" value="' + newPipe + '">';
                                            html+='<br/><br/>';
                                            html+= '<label>Instalaci&oacute;n de tuber&iacute;a</label><br/>';
                                            
                                            html += '<a class="example-image-link" href="' + base_url + img1 + '" data-lightbox="example-tuberia" >';
                                                html+='<img src="' + base_url +img1+'" height="128px" width="128px" alt="Imagen instalacion de tuberia">';
                                            html += '</a>';
                                            
                                        html+='</div>';
                                        html+='<div class="col-md-6"><br/><br/><br/><br/><br/>';
                                        
                                        html+= '<label>Pie derecho con etiqueta<br/>';
                                            html += '<a class="example-image-link" href="' + base_url +  img2 + '" data-lightbox="example-Etiqueta" >';
                                                html+='<img src="'+ base_url +img2+'" height="128px" width="128px" alt="Etiqueta">';
                                            html += '</a>';
                                        html+='</div>';
                                    html+='</div>';
                                    html+='<div class="col-md-12">';
                                        html+='<div class="col-md-6">';
                                            html+='<label>Resultado PH</label>';
                                            html+= '<input class="form-control" type="text" id="" value="' + ph + '">'; 
                                        html+='</div>';
                                        html+='<div class="col-md-6">';
                                            html+='<label>N&uacute;mero de tomas</label>';
                                            html+='<input class="form-control" type="text" id="" value="' + pipesCount + '">';
                                        html+='</div>';
                                    html+='</div>';
                                html+='</div>';
                            html+='</div>';
                        html+='</div>';
                        
//                        carousel = '<div id="myCarouselOP" class="carousel slide"><div class="carousel-inner">' + itemsProofOP + '</div>' + '<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarouselOP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        
                           $('#formsDetailsBody').append(html);
                           
                            data[0].formPlumbDet.forEach(function(frmPlumDet, idx) {
                                idx++;
                                htmlAppend +='<tr scope="row">1</tr>';
                                htmlAppend +='<td>' + idx + '</td>';
                                htmlAppend +='<td>' + frmPlumDet.path + '</td>';
                                htmlAppend +='<td>' + frmPlumDet.distance + '</td>';
                                htmlAppend +='<td>' + frmPlumDet.pipe + '</td>';
                                htmlAppend +='<td>' + frmPlumDet.fall + '</td>'; 
                                htmlAppend +='</tr>';
                            });
                            $('#detPlumbForm').append(htmlAppend);
                            $('#formsDetails').find('input').prop('disabled', true);
                        }   
                    } else if (type2 == "Venta") {
                        var fromId;
                        var comments;
                        var consecutive;
                        var financialService;
                        var lastName;
                        var lastNameOp;
                        var meeting;
                        var name;
                        var owner;
                        var payment;
                        var prospect;
                        var requestNumber;
                        var estatus;
                        var puedeValidar;
                        var uninteresting;
                        var motivosDesinteres;

                        //console.log('data = '+ data);
                        for (var element in data) {
                            console.log('data_elemente_id =   '+ data[element].id);
                            fromId = data[element].id;
                            //console.log(data[element].comments);
                            comments = data[element].comments;
                            //console.log(data[element].financialService);
                            consecutive = data[element].requestNumber;
                            estatus = data[element].estatus;
                            puedeValidar = data[element].puedeValidar;

                            financialService = data[element].financialService;
                            if (data[element].imageName == null && data[element].imageName == undefined) {
                                imgsName.push("");
                            } else {
                                imgsName.push(data[element].imageName);
                            }
                           
                            lastName = data[element].lastName;
                            lastNameOp = data[element].lastNameOp;
                            meeting = data[element].meeting;
                            name = data[element].name;
                            owner = data[element].owner;
                            payment = data[element].payment;
                            prospect = data[element].prospect;
                            requestNumber = data[element].requestNumber;
                            uninteresting = data[element].uninteresting;
                            motivosDesinteres = data[element].motivosDesinteres;

                        }

                        //var proofs = [];
                        //var imgsProof;
                        var elementsProof = "";
                        var itemsProof = "";

                        /*---------------------------*/
                        var elementsProofOP = "";
                        var itemsProofOP = "";

                        //var identifications = [];
                        //var imgsIdentification;
                        var elementsIdentification = "";
                        var itemsIdentification = "";

                        /*---------------------------*/
                        var elementsIdentificationOP = "";
                        var itemsIdentificationOP = "";

                        //var requests = [];
                        //var imgsRequest;
                        var elementsRequest = "";
                        var itemsRequest = "";
                        /*---------------------------*/
                        var elementsRequestOP = "";
                        var itemsRequestOP = "";
                        //var debtors = [];
                        //var imgsDebtor;
                        var elementsDebtor = "";
                        var itemsDebtor = "";
                        /*---------------------------*/
                        var elementsDebtorOP = "";
                        var itemsDebtorOP = "";
                        //var announcements = [];
                        //var imgsAnnouncement;
                        var elementsAnnouncement = "";
                        var itemsAnnouncement = "";
                        /*---------------------------*/
                        var elementsAnnouncementOP = "";
                        var itemsAnnouncementOP = "";
                        //var agreements = [];
                        //var imgsAgreement;
                        var elementsAgreement = "";
                        var itemsAgreement = "";
                        /*---------------------------*/
                        var elementsAgreementOP = "";
                        var itemsAgreementOP = "";

                        var zero = true;
                        var first = true;
                        var second = true;
                        var third = true;
                        var fourth = true;
                        var fifth = true;
                        var numElem = 1;

                        for (var photo in imgsName) {

                            var FileType = getFileType(imgsName[photo]);
                            var urlFinal = imgsName[photo];

                            //if (FileType == 'foto_solicitud') {
                            if (FileType == 'solicitud') {
                                
                                if (zero) {
                                    elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '" class="active"></li>';

                                    itemsRequest += '<div class="item active">';
                                    itemsRequest += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                                    //itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" />';
                                    itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" style="" />';
                                    itemsRequest += '</a>';
                                    itemsRequest += '</div>';

                                    elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '" class="active"></li>';

                                    itemsRequestOP += '<div class="item active">';
                                    itemsRequestOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                                    itemsRequestOP += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="1024px" width="1248px" style="" />';
                                    itemsRequestOP += '</a>';
                                    itemsRequestOP += '</div>';

                                    zero = false;
                                } else {
                                    elementsRequest += '<li data-target="#myCarousel2" data-slide-to="' + photo + '"></li>';

                                    itemsRequest += '<div class="item">';
                                    itemsRequest += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                                    itemsRequest += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="256px" width="256px" style="" />';
                                    itemsRequest += '</a>';
                                    itemsRequest += '</div>';

                                    elementsRequestOP += '<li data-target="#myCarousel2OP" data-slide-to="' + photo + '"></li>';

                                    itemsRequestOP += '<div class="item">';
                                    itemsRequestOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-solicitud" >';
                                    itemsRequestOP += '<img src="' + base_url + urlFinal + '" alt="solicitud" height="1024px" width="1248px" style="" />';
                                    itemsRequestOP += '</a>';
                                    itemsRequestOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_avisoprivacidad') {

                            } else if (FileType == 'aviso') {
                                if (first) {
                                    numElem = 1;
                                    elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncement += '<div class="item active">';
                                    itemsAnnouncement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                                    //itemsAnnouncement += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px" />';
                                    itemsAnnouncement += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncement += '</a>';
                                    itemsAnnouncement += '</div>';

                                    elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncementOP += '<div class="item active">';
                                    itemsAnnouncementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncementOP += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncementOP += '</a>';
                                    itemsAnnouncementOP += '</div>';

                                    first = false;
                                } else {
                                    elementsAnnouncement += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '"></li>';

                                    itemsAnnouncement += '<div class="item">';
                                    itemsAnnouncement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncement += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncement += '</a>';
                                     itemsAnnouncement += '</div>';

                                    elementsAnnouncementOP += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAnnouncementOP += '<div class="item">';
                                    itemsAnnouncementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-aviso" >';
                                    itemsAnnouncementOP += '<img src="' + base_url + urlFinal + '" alt="avisoprivacidad" height="256px" width="256px" style="" />';
                                    itemsAnnouncementOP += '</a>';
                                    itemsAnnouncementOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_pagare') {
                            } else if (FileType == 'indentificacion') {
                                if (second) {
                                    numElem = 1;
                                    elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsIdentification += '<div class="item active">';
                                    itemsIdentification += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                                    itemsIdentification += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px" style="" />';
                                    itemsIdentification += '</a>';
                                    itemsIdentification += '</div>';

                                    elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsIdentificationOP += '<div class="item active">';
                                    itemsIdentificationOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                                    itemsIdentificationOP += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px" style="" />';
                                    itemsIdentificationOP += '</a>';
                                    itemsIdentificationOP += '</div>';

                                    second = false;
                                } else {
                                    elementsIdentification += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '"></li>';

                                    itemsIdentification += '<div class="item">';
                                    itemsIdentification += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                                    itemsIdentification += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="256px" width="256px" style="" />';
                                    itemsIdentification += '</a>';
                                    itemsIdentification += '</div>';

                                    elementsIdentificationOP += '<li data-target="#myCarousel4OP" data-slide-to="' + numElem + '"></li>';

                                    itemsIdentificationOP += '<div class="item">';
                                    itemsIdentificationOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-indentificacion" >';
                                    itemsIdentificationOP += '<img src="' + base_url + urlFinal + '" alt="identificacion" height="1024px" width="1248px" style="" />';
                                    itemsIdentificationOP += '</a>';
                                    itemsIdentificationOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_comprobante') {
                            } else if (FileType == 'comprobante') {
                                if (third) {
                                    numElem = 1;
                                    elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsProof += '<div class="item active">';
                                    itemsProof += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                                    itemsProof += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="256px" width="256px" style="" />';
                                    itemsProof += '</a>';
                                    itemsProof += '</div>';

                                    elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsProofOP += '<div class="item active">';
                                    itemsProofOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                                    itemsProofOP += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="1024px" width="1248px" style="" />';
                                    itemsProofOP += '</a>';
                                    itemsProofOP += '</div>';

                                    third = false;
                                } else {
                                    elementsProof += '<li data-target="#myCarousel" data-slide-to="' + numElem + '"></li>';

                                    itemsProof += '<div class="item">';
                                    itemsProof += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                                    itemsProof += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="256px" width="256px" style="" />';
                                    itemsProof += '</a>';
                                    itemsProof += '</div>';

                                    elementsProofOP += '<li data-target="#myCarouselOP" data-slide-to="' + numElem + '"></li>';

                                    itemsProofOP += '<div class="item">';
                                    itemsProofOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-comprobante" >';
                                    itemsProofOP += '<img src="' + base_url + urlFinal + '" alt="comprobante" height="1024px" width="1248px" style="" />';
                                    itemsProofOP += '</a>';
                                    itemsProofOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_solicitud') {
                            } else if (FileType == 'contrato') {
                                if (fourth) {
                                    numElem = 1;
                                    elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAgreement += '<div class="item active">';
                                    itemsAgreement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                                    itemsAgreement += '<img src="' + base_url + urlFinal + '" alt="contrato" height="256px" width="256px" style="" />';
                                    itemsAgreement += '</a>';
                                    itemsAgreement += '</div>';

                                    elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsAgreementOP += '<div class="item active">';
                                    itemsAgreementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                                    itemsAgreementOP += '<img src="' + base_url + urlFinal + '" alt="contrato" height="1024px" width="1248px" style="" />';
                                    itemsAgreementOP += '</a>';
                                    itemsAgreementOP += '</div>';

                                    fourth = false;
                                } else {
                                    elementsAgreement += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                                    itemsAgreement += '<div class="item">';
                                    itemsAgreement += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                                    itemsAgreement += '<img src="' + base_url + urlFinal + '" alt="contrato" height="256px" width="256px" style="" />';
                                    itemsAgreement += '</a>';
                                    itemsAgreement += '</div>';

                                    elementsAgreementOP += '<li data-target="#myCarousel6" data-slide-to="' + numElem + '"></li>';

                                    itemsAgreementOP += '<div class="item">';
                                    itemsAgreementOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-contrato" >';
                                    itemsAgreementOP += '<img src="' + base_url + urlFinal + '" alt="contrato" height="1024px" width="1248px" style="" />';
                                    itemsAgreementOP += '</a>';
                                    itemsAgreementOP += '</div>';
                                }
                                numElem++;
                                //} else if (FileType == 'foto_pagare') {
                            } else if (FileType == 'pagare') {
                                if (fifth) {
                                    numElem = 1;
                                    elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsDebtor += '<div class="item active">';
                                    itemsDebtor += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                                    itemsDebtor += '<img src="' + base_url + urlFinal + '" alt="pagare" height="256px" width="256px" />';
                                    itemsDebtor += '</a>';
                                    itemsDebtor += '</div>';

                                    elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '" class="active"></li>';

                                    itemsDebtorOP += '<div class="item active">';
                                    itemsDebtorOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                                    itemsDebtorOP += '<img src="' + base_url + urlFinal + '" alt="pagare" height="1024px" width="1248px" style="" />';
                                    itemsDebtorOP += '</a>';
                                    itemsDebtorOP += '</div>';

                                    fifth = false;
                                } else {
                                    elementsDebtor += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                                    itemsDebtor += '<div class="item">';
                                    itemsDebtor += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                                    itemsDebtor += '<img src="' + base_url + urlFinal + '" alt="pagare" height="256px" width="256px" />';
                                    itemsDebtor += '</a>';
                                    itemsDebtor += '</div>';

                                    elementsDebtorOP += '<li data-target="#myCarousel5" data-slide-to="' + numElem + '"></li>';

                                    itemsDebtorOP += '<div class="item">';
                                    itemsDebtorOP += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-pagare" >';
                                    itemsDebtorOP += '<img src="' + base_url + urlFinal + '" alt="pagare" height="1024px" width="1248px" style="" />';
                                    itemsDebtorOP += '</a>';
                                    itemsDebtorOP += '</div>';
                                }
                                numElem++;
                            }
                        }
                        if (prospect == 1) {
                            prospect = "S&iacute;";
                        } else if (prospect == 0) {
                            prospect = "No";
                        }
                        if (owner == 1) {
                            owner = "S&iacute;";
                        } else if (owner == 0) {
                            owner = "No";
                        }

                        /*****AQUI DETECTAMOS SI EL CLIENTE ESTA INTERESADO EN EL SERVICIO**/
                        if (uninteresting == 'false') {
                            uninteresting = "No";
                        } else {
                            uninteresting = "S&iacute;";
                        }

                        if (motivosDesinteres == null) {
                            motivosDesinteres = "";
                        }

                        if (lastName == null) {
                            lastName = "";
                        }

                        if (lastNameOp == null) {
                            lastNameOp = "";
                        }

                        carouselProof = '<div id="myCarouselOP" class="carousel slide"><div class="carousel-inner">' + itemsProofOP + '</div>' + '<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarouselOP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselRequest = '<div id="myCarousel2OP" class="carousel slide"><div class="carousel-inner">' + itemsRequestOP + '</div>' + '<a class="left carousel-control" href="#myCarousel2OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselAnnouncement = '<div id="myCarousel3OP" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel3OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselIdentification = '<div id="myCarousel4OP" class="carousel slide"><div class="carousel-inner">' + itemsIdentificationOP + '</div>' + '<a class="left carousel-control" href="#myCarousel4OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselDebtor = '<div id="myCarousel5OP" class="carousel slide"><div class="carousel-inner">' + itemsDebtorOP + '</div>' + '<a class="left carousel-control" href="#myCarousel5OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';
                        carouselAgreement = '<div id="myCarousel6OP" class="carousel slide"><div class="carousel-inner">' + itemsAgreementOP + '</div>' + '<a class="left carousel-control" href="#myCarousel6OP" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6OP" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>';

                        var nombreFinanciera = "";
                        if (financialService == 1) {
                            nombreFinanciera = "AYOPSA";
                        } else {
                            nombreFinanciera = "MEXICANA DE GAS";
                        }

                        var formaDePago = "";
                        if (payment == 1) {
                            formaDePago = "FINANCIADO";
                        } else {
                            formaDePago = "CONTADO";
                        }

                        var consecutivo = "";
                        if (consecutive == null) {
                            consecutivo = "-";
                        }


                        $('#formsDetailsBody').append('<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                            + '<label>Cliente interesado en contratar el servicio?</label>' + '<input class="form-control" type="text" id="prospect" value="' + uninteresting + '">'
                            + '<input type="text" id="formID" value="' + fromId + '" hidden>'

                            + '<label>Motivo del desinter&eacute;s</label>' + '<input class="form-control" type="text" id="uninteresed" value="' + motivosDesinteres + '">'
                            + '<label>Comentarios</label>' + '<input class="form-control" style="height: 70px; !important" type="text" id="comments" value="' + comments + '">'

                            + '<label>Se encuentra el titular?</label>' + '<input class="form-control" type="text" id="owner" value="' + owner + '">' + '<br/>'
                            + '<label>Consecutivo</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="consecutive" value="' + idForm + '">' + '<br/>' + '<div class="col-md-12">' + '<div class="col-md-6">'

                            + '<label>N&uacute;mero de contrato</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="ID" value="' + requestNumber + '">'
                            + '<label>Apellido paterno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastName" value="' + lastName + '">'
                            + '<label>Financiera</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="financialService" value="' + nombreFinanciera + '">' + '</div>' + '<div class="col-md-6">'

                            + '<label>Nombre</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="name" value="' + name + '">'
                            + '<label>Apellido materno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastNameOp" value="' + lastNameOp + '">'
                            + '<label>Forma de pago</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="payment" value="' + formaDePago + '">' + '</div>' + '</div>' + '</div>' + '<div class="col-md-6"><h4>DOCUMENTOS VENTA</h4>'
                            + '<div class="col-md-12">'
                            + '<div class="col-md-6">'

                            + '<label>Comprobante de domicilio</label>' + '<br/>'
                            + '<a onclick="showImages(1);"><div id="myCarousel" class="carousel slide"><div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<label>Solicitud</label>' + '<br/>'
                            + '<a onclick="showImages(2);"><div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsRequest + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<label>Aviso de privacidad</label>' + '<br/>'
                            + '<a onclick="showImages(3);"><div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncement + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '</div>'

                            + '<div class="col-md-6">'

                            + '<label>Identificaci&oacute;n</label>' + '<br/>'
                            + '<a onclick="showImages(4);"><div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsIdentification + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                            + '<label>Pagar&eacute;</label>' + '<br/>'
                            + '<a onclick="showImages(5);"><div id="myCarousel5" class="carousel slide"><div class="carousel-inner">' + itemsDebtor + '</div>' + '<a class="left carousel-control" href="#myCarousel5" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                            + '<label>Contrato</label>' + '<br/>'
                            + '<a onclick="showImages(6);"><div id="myCarousel6" class="carousel slide"><div class="carousel-inner">' + itemsAgreement + '</div>' + '<a class="left carousel-control" href="#myCarousel6" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                            + '</div>'
                            + '<div class="col-md-12">' + '<div class="col-md-5">&nbsp;</div>' + '<div class="col-md-4">&nbsp;</div>' + '<div class="col-md-3">'
                            + '<br/><br/><button id="btnValidarVenta" name="btnValidarVenta" type="button" class="btn btn-info" onclick="sellValidation();">VALIDAR</button>' + '</div>' + '</div>' + '</div>' + '</div>' + '</div>');

                        //sellFormToValidate = '<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                        sellFormToValidate = '<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                            + '<label>Cliente interesado en contratar el servicio?</label>' + '<input class="form-control" type="text" id="prospect" value="' + prospect + '">'
                            + '<input type="text" id="formID" value="' + fromId + '" hidden>'
                            + '<label>Motivo del desinter&eacute;s</label>' + '<input class="form-control" type="text" id="uninteresed" value="' + comments + '">'
                            + '<label>Comentarios</label>' + '<textarea class="form-control" rows="4" cols="50" type="text" id="comments">' + comments + '</textarea>'
                            + '<label>Se encuentra el titular?</label>' + '<input class="form-control" type="text" id="owner" value="' + owner + '">' + '<br/>'
                            + '<label>Consecutivo</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="consecutive" value="' + idForm + '">' + '<br/>' + '<div class="col-md-12">' + '<div class="col-md-6">'

                            + '<label>N&uacute;mero de contrato</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="ID" value="' + requestNumber + '">'
                            + '<label>Apellido paterno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastName" value="' + lastName + '">'
                            + '<label>Financiera</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="financialService" value="' + nombreFinanciera + '">' + '</div>' + '<div class="col-md-6">'

                            + '<label>Nombre</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="name" value="' + name + '">'
                            + '<label>Apellido materno</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="lastNameOp" value="' + lastNameOp + '">'
                            + '<label>Forma de pago</label>&nbsp;&nbsp;' + '<input class="form-control" type="text" id="payment" value="' + formaDePago + '">' + '</div>' + '</div>' + '</div>'
                            + '<div class="col-md-6"><h4>DOCUMENTOS</h4>'
                            + '<div class="col-md-12">'
                            + '<div class="col-md-6">'

                            + '<input type="checkbox" name="trustedImage" id="trustedImage" onClick="checarRechazados()"> Comprobante de domicilio' + '<br/>'
                            + '<a onclick="showImages(1);"><div id="myCarousel" class="carousel slide"><div class="carousel-inner">' + itemsProof + '</div>' + '<a class="left carousel-control" href="#myCarousel" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<input type="checkbox" name="requestImage" id="requestImage" onClick="checarRechazados()"> Solicitud' + '<br/>'
                            + '<a onclick="showImages(2);"><div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsRequest + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<input style="background-color: green;" type="checkbox" name="privacyImage" id="privacyImage" onClick="checarRechazados()"> Aviso de privacidad' + '<br/>'
                            + '<a onclick="showImages(3);"><div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsAnnouncement + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                            + '</div>'

                            + '<div class="col-md-6">'
                            + '<input type="checkbox" name="identificationImage" id="identificationImage" onClick="checarRechazados()"> Identificaci&oacute;n' + '<br/>'
                            + '<a onclick="showImages(4);"><div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsIdentification + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<input type="checkbox" name="payerImage" id="payerImage" onClick="checarRechazados()"> Pagar&eacute;' + '<br/>'
                            + '<a onclick="showImages(5);"><div id="myCarousel5" class="carousel slide"><div class="carousel-inner">' + itemsDebtor + '</div>' + '<a class="left carousel-control" href="#myCarousel5" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel5" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'

                            + '<input type="checkbox" name="agreegmentImage" id="agreegmentImage" onClick="checarRechazados()"> Contrato' + '<br/>'
                            + '<a onclick="showImages(6);"><div id="myCarousel6" class="carousel slide"><div class="carousel-inner">' + itemsAgreement + '</div>' + '<a class="left carousel-control" href="#myCarousel6" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel6" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div></a>'
                            + '</div></div>'
                            + '<br/><br/>'
                            + '<div id="divMotivosRechazo" name="divMotivosRechazo">'
                            + '<h4>MOTIVOS DE RECHAZO VENTA</h4>'
                            + '<div class="col-md-12"><br/>'
                            + '<div class="col-md-4">' + '<select id="rejectedReasons"></select>'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '</div>'
                            + '<div class="col-md-4">'
                            + '<img style="margin-left: 45px; margin-bottom: 10px" src="assets/icons/agregar.png" onclick="addReason();" height="42px" width="42px">'

                            + '</div>'
                            + '</div>'
                            + '<br/><br/><br/><br/>'
                            + '<div class="col-md-12">'
                            + '<div class="col-md-10">'
                            + '<table id="tableReasons" style="width:100%">'
                            + '<tr>'
                            + '<th style="background-color: black; color: white;" colspan="2"> Motivos de rechazo</th>'
                            + '</tr>'
                            + '</table>'
                            + '</div>'
                            + '</div>'
                            + '<div class="col-md-2">&nbsp;</div>'
                            + '</div>'
                            + '<br/>'
                            + '<div class="col-md-12">&nbsp;</div>'
                            + '<div class="col-md-12">&nbsp;</div>'
                            + '<div class="col-md-12">&nbsp;</div>'
                            + '<div class="col-md-12">'
                            + '<div class="col-md-4">&nbsp;</div>'
                                //+ '<div class="col-md-4">' + '<button type="button" class="btn btn-danger" id="btnCancel">RECHAZADO</button>' + '</div>'
                            + '<div class="col-md-4">&nbsp;</div>'
                            + '<div class="col-md-4">' + '<button type="button" class="btn btn-primary" id="btnSendSell">VALIDAR</button>' + '</div>'
                            + '</div>'
                            + '</div>' + '</div>';
                        var ESTATUS_CANCELADO = 8;
                        var ESTATUS_REAGENDADO = 7;
                        var NO_PUEDE_VALIDAR = 0;
                        if (estatus === ESTATUS_CANCELADO || estatus === ESTATUS_REAGENDADO) {
                            $("#btnValidarVenta").addClass('hidden');
                            $("#btnSendSell").addClass('hidden');
                        }
                        if (puedeValidar == NO_PUEDE_VALIDAR) {
                            $("#btnValidarVenta").addClass('hidden');
                            $("#btnSendSell").addClass('hidden');
                        }

                        $.ajax({
                            method: "POST",
                            url: "dataLayer/callsWeb/sellValidate.php",
                            data: {formId: fromId},
                            dataType: "JSON",
                            success: function (data) {
                                console.log("Function executed");
                                console.log(data);
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                alert(XMLHttpRequest);
                                alert(textStatus);
                            },
                            complete: function (XMLHttpRequest, status) {
                                console.log(XMLHttpRequest);
                                console.log(status);
                            }
                        });
                    } else if (type2 == "Segunda Venta") {
                        console.log('seg vta data', data);
                        var agency,agreegment,agreementExpires,agreementMonthlyPayment,
                        agreementRi,agreementRiDate,agreementType,celullarTelephone,
                        clientBirthCountry,clientBirthDate,clientCURP,
                        clientEmail,clientJobActivity,clientJobEnterprise,
                        clientJobLocation,clientJobRange,clientJobTelephone,
                        clientName,clientRFC,clientRelationship,clientgender,
                        clientlastName,clientlastName2,consecutive,ext,homeTelephone,
                        idCity,idColonia,idState,identificationType,inHome,
                        jobTelephone,name,payment,price,requestDate,street,telephone, referencesTable, references;
                        
                        var i = 0;
                        references=data.formSegVta[0].referencias;
                        for (elem in references) {
                            //console.log(elem);
                            //console.log(references[elem]);
                            referencesTable += '<label>Nombre Ref. ' + i + '</label>';
                            referencesTable += '<input type="text" id="txtNextSellReference' + i + '" name="nextSellReference' + i + '" value="' + references[elem][0] + '" class="form-control" disable>';
                            //referencesTable += '<br/>';
                            referencesTable += '<label>Tel&eacute;fono particular</label>';
                            referencesTable += '<input type="text" id="txtNextSellReference' + i + 'Telephone" name="nextSellReference' + i + 'Telephone" value="' + references[elem][2] + '" class="form-control input-sm" disable>';
                            referencesTable += '<label>Tel&eacute;fono de trabajo</label><label>Extensi&oacute;n</label>';
                            referencesTable += '<input type="text" id="txtNextSellReference' + i + 'Telephone" name="nextSellReference' + i + 'Telephone" value="' + references[elem][1] + '" class="form-control input-sm" disable>&nbps;&nbps;&nbps;&nbps;&nbps;&nbps;<input type="text" id="txtNextSellReference' + i + 'TelephoneExt" name="nextSellReference' + i + 'TelephoneExt" value="' + references[elem][3] + '" class="form-control input-sm" disable>';
                            //referencesTable += '<label>Extensi&oacute;n</label>';
                            //referencesTable += '<input type="text" id="txtNextSellReference'+i+'TelephoneExt" name="nextSellReference'+i+'TelephoneExt" value="'+references[elem][3]+'" class="form-control input-sm" disable>';
                            //referencesTable += '<br/><br/>';
                            referencesTable += '<br/>';
                        }

                        $('#formsDetailsBody').append('<div class="row">'
                            + '<form class="cmxNextSaleform" role="form" id="formNextStepsSale">'
                            + '<!--<div><input type="text" id="txtid" hidden></div>-->'
                            + '<!-- tabs left -->'
                            + '<div class="tabbable tabs-left">'
                            + '<ul class="nav nav-tabs">'
                            + '<li class="active" id="nextSellTab1"><a href="#datosTitular" data-toggle="tab">Datos del titular</a></li>'
                            + '<li id="nextSellTab2"><a href="#agreementInformation" data-toggle="tab">Informaci&oacute;n del contrato</a></li>'
                            + '</ul>'
                                //+'<div class="tab-content" class="col-md-12">'
                            + '<div class="tab-content">'
                            + '<div class="tab-pane active" id="datosTitular">'
                            + '<div class="row">'
                            + '<div class="col-md-9">'
                            + '<div class="col-xs-6">'
                                //+'<p>&nbsp;&nbsp;&nbsp;&nbsp;CHECKLIST</p>'
                            + '<div class="col-xs-6">'

                                /*+'<label>Consecutivo</label>'
                                 +'<input type="text" id="txtConsecutive" name="consecutive" class="form-control input-sm">'

                                 +'<label>Pagar&eacute;</label>'

                                 +'<div class="input-group">'
                                 +'<input type="text" id="txtNextSellPayment" name="nextSellPayment" class="form-control input-sm">'

                                 +'<div class="input-group-addon">AYO</div>'
                                 +'</div>'

                                 +'<label>N&uacute;mero de contrato</label>'
                                 +'<input type="text" id="txtAgreement" name="Agreement" class="form-control input-sm">'*/

                                //+'<br/>'

                            + '<p>DATOS DEL TITULAR</p>'

                            + '<label>Apellido paterno</label>'
                            + '<input type="text" id="txtLastName1" name="ClientLastName1" value="' + clientlastName + '" class="form-control input-sm" disabled>'

                            + '<label>Nombre</label>'
                            + '<input type="text" id="txtName" name="ClientName" value="' + clientName + '" class="form-control input-sm" disabled>'

                            + '<label>CURP</label>'
                            + '<input type="text" id="txtCURP" name="ClientCURP" value="' + clientCURP + '" class="form-control input-sm" disabled>'

                            + '<label>Estado civil</label>'
                            + '<select class="form-control input-sm" id="txtEngagment">'
                            + '<option value="1">Soltero</option>'
                            + '<option value="2">Casado</option>'
                            + '<option value="3">Viuda</option>'
                            + '</select>'

                            + '<label>Identificaci&oacute;n</label>'
                            + '<input type="text" id="txtIdCard" name="ClientIDCard" value="' + identificationType + '" class="form-control input-sm" disabled>'

                            + '</div>'
                            + '<div class="col-xs-6">'

                            + '<label>Agencia</label>'
                            + '<input type="text" id="txtNextSellAgency" name="nextSellAgency" value="' + agency + '" class="form-control input-sm" disabled>'

                            + '<br/><br/><br/>'

                            + '<label>Fecha de solicitud </label>'
                            + '<input type="text" id="txtRequestDate" name="txtRequestDate" value="' + requestDate + '" class="form-control input-sm" disabled>'

                            + '<br/><br/>'

                            + '<label>Apellido materno</label>'
                            + '<input type="text" id="txtLastName2" name="ClientLastName2" value="' + clientlastName2 + '" class="form-control input-sm" disabled>'

                            + '<label>RFC</label>'
                            + '<input type="text" id="txtRFC" name="ClientRFC" value="' + clientRFC + '" class="form-control input-sm" disabled>'

                            + '<label>Correo</label>'
                            + '<input type="text" id="txtEmail" name="ClientEmail" value="' + clientEmail + '" class="form-control input-sm" disabled>'

                            + '<label>Sexo</label>'
                            + '<select class="form-control input-sm" id="txtNextSellGender">'
                            + '<option value="1">Femenino</option>'
                            + '<option value="2">Masculino</option>'
                            + '</select>'

                            + '<label>Tipo de identificaci&oacute;n</label>'
                            + '<select class="form-control input-sm" id="txtNextSellGender">'
                            + '<option value="1">IFE</option>'
                            + '<option value="2">Licencia para conducir</option>'
                            + '<option value="3">Pasaporte</option>'
                            + '<option value="4">Cartilla militar</option>'
                            + '</select>'
                            + '</div>'
                            + '</div>'
                            + '<div class="col-xs-6">'
                            + '<div class="col-xs-6">'

                            + '<label>Fecha de Nacimiento</label>'
                            + '<input type="date" id="txtNextSellBithdate" name="NextSellBirthdate"  value="' + clientBirthDate + '" class="form-control input-sm" disabled>'
                            + '<br/>'

                            + '<p>DOMICILIO</p>'
                            + '<label>Estado</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleState">'
                            + '<option value="1">Nuevo Le&oacute;n</option>'
                            + '<option value="1"></option>'
                            + '<option value="1"></option>'
                            + '</select>'

                            + '<label>Colonia</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleColonia">'
                            + '<option value="1">Centro</option>'
                            + '<option value="1"></option>'
                            + '<option value="1"></option>'
                            + '</select>'

                            + '<label>Vive en casa</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleInHome">'
                            + '<option value="1">S&iacute;</option>'
                            + '<option value="2">No</option>'
                            + '</select>'

                            + '<label>Tel&eacute;fono celular</label>'
                            + '<input type="text" id="txtNextSellCellularPhone" name="NextSellCellularPhone" value="' + telephone + '" class="form-control input-sm" disabled>'
                            + '<br/>'

                            + '<p>EMPLEO</p>'
                            + '<label>Empresa</label>'
                            + '<input type="text" id="txtNextSellEnterprise" name="NextSellEnterprise" value="' + clientJobEnterprise + '" class="form-control input-sm" disabled>'
                            + '<label>Puesto</label>'
                            + '<input type="text" id="txtNextSellPosition" name="NextSellPosition" value="' + clientJobRange + '" class="form-control input-sm" disabled>'
                            + '<label>Tel&eacute;fono</label>'
                            + '<input type="text" id="txtNextSellJobTelephone" name="NextSellJobTelephone" value="' + clientJobTelephone + '" class="form-control input-sm" disabled>'
                            + '</div>'
                            + '<div class="col-xs-6">'

                            + '<label>Pa&iacute;s de Nacimiento</label>'
                            + '<select class="form-control input-sm" id="txtNextSellCountry">'
                            + '<option value="1">M&eacute;xico</option>'
                            + '</select>'
                            + '<!--<br/><br/>-->'
                            + '<div>&nbsp;</div>'
                            + '<div>&nbsp;</div>'
                            + '<div>&nbsp;</div>'
                            + '<label>Municipio</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleCity">'
                            + '<option value="1">Monterrey</option>'
                            + '<option value="1"></option>'
                            + '<option value="1"></option>'
                            + '</select>'

                            + '<label>Calle</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleStreet">'
                            + '<option value="1">Centro</option>'
                            + '<option value="1"></option>'
                            + '<option value="1"></option>'
                            + '</select>'

                            + '<label>Tel&eacute;fono de casa</label>'
                            + '<input type="text" id="txtNextSellPhone" name="txtNextSellPhone" value="' + homeTelephone + '" class="form-control input-sm" disabled>'
                            + '<br/><br/><br/><br/>'
                            + '<label>Direcci&oacute;n</label>'
                            + '<input type="text" id="txtNextSellJobLocation" name="NextSellJobLocation" value="' + clientJobLocation + '" class="form-control input-sm" disabled>'
                            + '<label>Actividad/&Aacute;rea</label>'
                            + '<input type="text" id="txtNextSellJobActivity" name="txtNextSellJobActivity" value="' + clientJobActivity + '" class="form-control input-sm" disabled>'
                            + '<br/>'
                            + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" onclick="">ENVIAR</button>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '<div class="tab-pane" id="agreementInformation">'
                            + '<div class="row">'
                            + '<div class="col-md-11">'
                            + '<div class="col-xs-6">'
                            + '<p>&nbsp;&nbsp;&nbsp;&nbsp;FINANCIAMIENTO</p>'

                            + '<div class="col-xs-6">'

                            + '<label>Consecutivo</label>'
                            + '<input type="text" id="txtConsecutive" name="consecutive" value="' + consecutive + '" class="form-control input-sm" disabled>'

                            + '<label>Pagar&eacute;</label>'

                            + '<div class="input-group">'
                            + '<input type="text" id="txtNextSellPayment" name="nextSellPayment" value="' + payment + '" class="form-control input-sm" disabled>'

                            + '<div id="segundaVentaFinanciada" name="segundaVentaFinanciada" class="input-group-addon">AYO</div>'
                            + '</div>'

                            + '<label>N&uacute;mero de contrato</label>'
                            + '<input type="text" id="txtAgreement" name="Agreement" value="' + agreegment + '" class="form-control input-sm" disabled>'

                            + '<label>Tipo de contrato</label>'
                            + '<select class="form-control input-sm" id="txtNextStepSaleAgreegmentType">'
                            + '</select>'

                            + '<label>Plazo</label>'
                            + '<input type="text" id="txtNextSellPaymentTime" name="nextSellPaymentTime" value="' + agreementExpires + '" class="form-control input-sm" disabled>'

                            + '<label>RI</label>'
                            + '<input type="text" id="txtNextSellRI" name="nextSellRI" value="' + agreementRi + '" class="form-control input-sm" disabled>'
                            + '<br/>'

                            + '<p>REFERENCIAS</p>'

                            + referencesTable

                                //+'<label>Nombre Ref. 1</label>'
                                //+'<input type="text" id="txtNextSellReference1" name="nextSellReference1" class="form-control input-sm">'

                                //+'<label>Tel&eacute;fono de trabajo</label>'
                                //+'<input type="text" id="txtNextSellReference1Telephone" name="nextSellReference1Telephone" class="form-control input-sm">'
                                //+'<br/>'

                                //+'<label>Nombre Ref. 2</label>'
                                //+'<input type="text" id="txtNextSellReference2" name="nextSellReference2" class="form-control input-sm">'

                            + '<label>Tel&eacute;fono de trabajo</label>'
                                ////+'<input type="text" id="txtNextSellReference2Telephone" name="nextSellReference2Telephone" class="form-control input-sm">'
                            + '</div>'
                            + '<div class="col-xs-6">'

                            + '<label>Precio</label>'
                            + '<input type="text" id="txtNextSellPrice" name="nextSellAgency" class="form-control input-sm">'

                            + '<label>Mensualidad</label>'
                            + '<input type="text" id="txtNextSellMonthlyCost" name="nextSellMonthlyCost" class="form-control input-sm">'

                            + '<label>Fecha RI</label>'
                            + '<input type="text" id="txtNextSellDateRI" name="nextSellDateRI" class="form-control input-sm">'

                            + '<br/><br/>'

                                /*+'<label>Tel&eacute;fono particular</label>'
                                 +'<input type="text" id="txtNextSellReferenceTelephone" name="nextSellReferenceTelephone" class="form-control input-sm">'

                                 +'<label>Extensi&oacute;n </label>'
                                 +'<input type="text" id="txtNextSellReferenceTelephoneExt" name="nextSellReferenceTelephoneExt" class="form-control input-sm">'
                                 +'<br/>'

                                 +'<label>Tel&eacute;fono particular</label>'
                                 +'<input type="text" id="txtNextSellReferenceTelephone2"  name="nextSellReferenceTelephone2" class="form-control input-sm">'

                                 +'<label>Extensi&oacute;n </label>'
                                 +'<input type="text" id="txtNextSellReferenceTelephoneExt2"  name="nextSellReferenceTelephoneExt2" class="form-control input-sm">'*/

                            + '</div>'
                            + '</div>'
                            + '<div class="col-xs-6">'
                            + '<div class="col-xs-10">'

                            + '<label>MOTIVOS DE RECHAZO</label>'
                            + '<select class="form-control input-sm" id="txtNextSellRejectReason">'
                            + '<option value="1">Motivo</option>'
                            + '</select>'

                            + '<div id="RejectReasonList"></div>'

                            + '</div>'
                            + '<div class="col-xs-1">'
                            + '<button class="">'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '<!-- /tabs -->'
                            + '</div>'
                            + '</form>'

                            + '</div>');
                    } else if (type2 == "Instalacion") {
                        console.log("Entering To Instalacion");
                        var id;
                        var name;
                        var lastName;
                        var request;
                        var phLabel;
                        var agencyPh;
                        var agencyNumber;
                        var installation;
                        var abnormalities;
                        var comments;
                        var brand;
                        var type;
                        var serialNuber;
                        var measurement;
                        var latitude;
                        var longitude;
                        var created_at;
                        var content;

                        for (var element in data) {
                            console.log(data[element].id);
                            id = data[element].id;
                            console.log(data[element].comments);
                            name = data[element].name;
                            console.log(data[element].name);
                            lastName = data[element].lastName;
                            console.log(data[element].request);
                            request = data[element].request;
                            console.log(data[element].phLabel);
                            phLabel = data[element].phLabel;
                            console.log(data[element].agencyPh);
                            agencyPh = data[element].agencyPh;
                            console.log(data[element].agencyNumber);
                            agencyNumber = data[element].agencyNumber;
                            console.log(data[element].installation);
                            installation = data[element].installation;
                            console.log(data[element].abnormalities);
                            abnormalities = data[element].abnormalities;
                            console.log(data[element].comments);
                            comments = data[element].comments;
                            console.log(data[element].brand);
                            brand = data[element].brand;
                            console.log(data[element].type);
                            type = data[element].type;
                            console.log(data[element].serialNumber);
                            serialNumber = data[element].serialNumber;
                            console.log(data[element].measurement);
                            measurement = data[element].measurement;
                            console.log(data[element].latitude);
                            latitude = data[element].latitude;
                            console.log(data[element].longitude);
                            longitude = data[element].longitude;
                            console.log(data[element].created_at);
                            created_at = data[element].created_at;
                            
                            imgs=data[element].arrIMG;
                            
                           // if (content === null && content === undefined) {
                            //    imgs.push("");
                            //} else {
                            //    imgs.push(content);
                            //    console.log(content);
                            //}
                        }

                        if (installation == 0) {
                            installation = "No";
                        } else if (installation == 1) {
                            installation = "S&iacute;";
                        }

                        var numberAgencyPH;
                        if (agencyNumber !== null || agencyNumber !== 0 || agencyNumber !== undefined) {
                            numberAgencyPH = "S&iacute;";
                        } else {
                            numberAgencyPH = "No";
                        }

                        var elementsAcometida = "";
                        var itemsAcometida = "";

                        var elementsMeasurer = "";
                        var itemsMeasurer = "";

                        var elementsInstallation = "";
                        var itemsInstallation = "";

                        var detailsHtml="<table class='table table-condensed'><thead><tr><th>#</th><th>Material</th><th>Cant.</th></tr></thead><tbody>";
                        var details = data[element].formInstDet;

                        for (var detail in details){
                            detailsHtml+="<tr>" + "<th scope='row' >" + details[detail].id + "</th>";
                            detailsHtml+="<td>" + details[detail].material + "</td>";
                            detailsHtml+="<td>" + details[detail].qty + "</td></tr>";
                        }

                        detailsHtml+="</tbody></table>"

                        for (var photo in imgs) {

                            if (imgs[photo]!= null && imgs[photo] != undefined) {
                                //console.log(photo);
                                //console.log(imgs[photo]);
                                // var photoType = imgs[photo].substr(0, imgs[photo].indexOf('_'));
                                //console.log (photoType);
                                var FileName = imgs[photo].nameIMG;
                                //console.log (fileName);
                                // var FileType = fileName.substr(fileName.indexOf('/') + 1);
                                // console.log(FileType);
                                var urlFinal = FileName;
                                console.log(urlFinal);
                                var first = true;
                                var second = true;
                                var third = true;

                                //if (FileType == 'acometida_installation') {
                                if (FileName!=null && FileName!=undefined){
                                    if (FileName.indexOf('acometida') > -1) {
                                        if (first) {
                                            numElem = 0;
                                            elementsAcometida += '<li data-target="#myCarousel2" data-slide-to="' + numElem + '" class="active"></li>';

                                            itemsAcometida += '<div class="item active">';
                                            itemsAcometida += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                            itemsAcometida += '<img src="' + base_url + urlFinal + '" alt="acometida" height="256px" width="256px" />';
                                            itemsAcometida += '</a>';
                                            itemsAcometida += '</div>';
                                            first = false;
                                        } else if (numElem > 0) {
                                            elementsAcometida += '<li data-target="#myCarousel2" data-slide-to="' + numElem + '"></li>';

                                            itemsAcometida += '<div class="item">';
                                            itemsAcometida += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-acometida" >';
                                            itemsAcometida += '<img src="' + base_url + urlFinal + '" alt="acometida" height="256px" width="256px" />';
                                            itemsAcometida += '</a>';
                                            itemsAcometida += '</div>';
                                        }
                                        numElem++;
                                    } else if (FileName.indexOf('cuadro') > -1) {
                                        if (second) {
                                            numElem = 0;
                                            elementsMeasurer += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                            itemsMeasurer += '<div class="item active">';
                                            itemsMeasurer += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                            itemsMeasurer += '<img src="' + base_url + urlFinal + '" alt="measurer" height="256px" width="256px" />';
                                            itemsMeasurer += '</a>';
                                            itemsMeasurer += '</div>';

                                            second = false;
                                        } else {
                                            elementsMeasurer += '<li data-target="#myCarousel3" data-slide-to="' + numElem + '" class="active"></li>';

                                            itemsMeasurer += '<div class="item active">';
                                            itemsMeasurer += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-measurer" >';
                                            itemsMeasurer += '<img src="' + base_url + urlFinal + '" alt="measurer" height="256px" width="256px" />';
                                            itemsMeasurer += '</a>';
                                            itemsMeasurer += '</div>';
                                        }
                                        numElem++;
                                        //} else if (FileType == 'measurerPhoto_installation') {
                                    } else if (FileName.indexOf('caratula') > -1) {
                                        if (third) {
                                            numElem = 0;
                                            elementsInstallation += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '" class="active"></li>';

                                            itemsInstallation += '<div class="item active">';
                                            itemsInstallation += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-Measurer" >';
                                            itemsInstallation += '<img src="' + base_url + urlFinal + '" alt="Measurer Photo" height="256px" width="256px" />';
                                            itemsInstallation += '</a>';
                                            itemsInstallation += '</div>';

                                            third = false;
                                        } else {
                                            elementsInstallation += '<li data-target="#myCarousel4" data-slide-to="' + numElem + '"></li>';

                                            itemsInstallation += '<div class="item">';
                                            itemsInstallation += '<a class="example-image-link" href="' + base_url + urlFinal + '" data-lightbox="example-Measurer" >';
                                            itemsInstallation += '<img src="' + base_url + urlFinal + '" alt="Measurer Photo" height="256px" width="256px" />';
                                            itemsInstallation += '</a>';
                                            itemsInstallation += '</div>';
                                        }
                                        numElem++;
                                        //} else if (FileType == 'foto_comprobante') {
                                    }
                                }
                            }
                        }

                        $('#formsDetailsBody').append('<div class="row">' + '<div class="col-lg-12">' + '<div class="col-md-6">'
                            + '<label>Consecutivo</label>' + '<input class="form-control" type="text" id="consecutive" value="' + id + '">'
                            + '<div class="col-md-12">' + '<div class="col-md-6">' + '<label>Nombre</label>' + '<input class="form-control" type="text" id="clientName" value="' + name + '">' + '</div>'
                            + '<div class="col-md-6">' + '<label>Apellido Paterno</label>' + '<input class="form-control" type="text" id="lastName" value="' + lastName + '">' + '</div>' + '</div>' + '<br/>'
                            + '<label>N&uacute;mero de contrato</label>' + '<input class="form-control" type="text" id="request" value="' + request + '">' + '<div class="col-md-12">'
                            + '<div class="col-md-6">' + '<label>Estado de acometida</label>'
                            + '<div id="myCarousel2" class="carousel slide"><div class="carousel-inner">' + itemsAcometida + '</div>' + '<a class="left carousel-control" href="#myCarousel2" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel2" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>' + '<br/><br/>'
                            + '</div>'
                            + '<div class="col-md-6">' + '<label>Color de etiqueta de PH</label>' + '<input class="form-control" type="text" id="phLabel" value="' + phLabel + '">' + '</div>' + '</div>'
                            + '<div class="col-md-12">' + '<div class="col-md-6">' + '<label>N&uacute;mero de agencia PH?</label>' + '<input class="form-control" type="text" id="idNumberAgency" value="' + numberAgencyPH + '">' + '</div>'
                            + '<div class="col-md-6">' + '<label>N&uacute;mero de la agencia</label>' + '<input class="form-control" type="text" id="idAgency" value="' + agencyNumber + '">' + '</div>' + '</div>'
                            + '<div class="col-md-12">' + '<div class="col-md-6">' + '<label>Procede a instalaci&oacute;n?</label>' + '<input class="form-control" type="text" id="installation" value="' + installation + '">' + '</div>'
                            + '<div class="col-md-6">' + '<label>Cat&aacute;logo de anomal&iacute;as</label>' + '<input class="form-control" type="text" id="abnormalities" value="' + abnormalities + '">' + '</div>' + '</div>'
                            + '<label>Comentarios</label>' + '<input class="form-control" style="height: 65px; !important;" type="text" id="comments" value="' + comments + '">' + '</div>'
                            + '<div class="col-md-6"><h4>MEDIDOR</h4>' + '<div class="col-md-12">' + '<div class="col-md-6">' + '<label>Marca del medidor</label>'
                                //+'<input class="form-control" style="width: 160px; !important;" type="text" id="">'
                            + '<input class="form-control" type="text" id="marcaMedidor" value="' + brand + '">' + '<label>No. de serie</label><br/>' + '<input class="form-control" type="text" id="serialNumber" value="' + serialNumber + '">'

                            + '<br><label>Car&aacute;tula del medidor<br/>'
                            + '<div id="myCarousel4" class="carousel slide"><div class="carousel-inner">' + itemsInstallation + '</div>' + '<a class="left carousel-control" href="#myCarousel4" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel4" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>' + '<br/><br/>'
                            + '</div>' + '<div class="col-md-6">'

                            + '<label>Tipo del medidor</label>'

                            + '<input class="form-control" type="text" id="type" value="' + type + '">' + '<label>Lectura del medidor</label><br/>' + '<input class="form-control" type="text" id="measurement" value="' + measurement + '">' + '<br/>'

                            + '<label>Cuadro de medici&oacute;n<br/>' +'<div id="myCarousel3" class="carousel slide"><div class="carousel-inner">' + itemsMeasurer + '</div>' + '<a class="left carousel-control" href="#myCarousel3" data-slide="prev">' + '<span class="icon-prev"></span>' + '</a>' + '<a class="right carousel-control" href="#myCarousel3" data-slide="next">' + '<span class="icon-next"></span>' + '</a>' + '</div>' + '<br/><br/>'

                            + '</div>' + '<div>'

                            + '<label>Material utilizado</label>' + detailsHtml  + '<br/><br/><br/>' + '</div></div>' + '</div>' + '</div>' + '</div>');
                    }
                    $('#formsDetails').modal('show');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                alert(textStatus);
            },
            complete: function (XMLHttpRequest, status) {
            }
        });
    }
    //console.log('aqui entramos a rotar');
    //$('.lb-outerContainer').rotate(90);

}


function showImages(carousel) {
    console.log('Element Pressed');
    console.log(carousel);
    $('#formSlideShowBody').html('');
    $('#titleformSlideShow').html('');
    $('#titleformSlideShow').append('Visualizador de Im&aacute;genes');
    if (carousel == 1) {
        $('#formSlideShowBody').append(carouselProof);
        $('#formSlideShow').modal('show');
    } else if (carousel == 2) {
        $('#formSlideShowBody').append(carouselRequest);
        $('#formSlideShow').modal('show');
    } else if (carousel == 3) {
        $('#formSlideShowBody').append(carouselAnnouncement);
        $('#formSlideShow').modal('show');
    } else if (carousel == 4) {
        $('#formSlideShowBody').append(carouselIdentification);
        $('#formSlideShow').modal('show');

    } else if (carousel == 5) {
        $('#formSlideShowBody').append(carouselDebtor);
        $('#formSlideShow').modal('show');
    } else if (carousel == 6) {
        $('#formSlideShowBody').append(carouselAgreement);
        $('#formSlideShow').modal('show');
    } else if(carousel == 7) {
        $('#formSlideShowBody').append(carouselPlomeria);
        $('#formSlideShow').modal('show');
    }

}

function showImagesCensus(carousel) {
    console.log('Element Pressed');
    console.log(carousel);
    $('#formSlideShowBody').html('');
    $('#titleformSlideShow').html('');
    //var output = "";
    $('#titleformSlideShow').append('Visualizador de Im&aacute;genes');
    if (carousel == 1) {
        $('#formSlideShowBody').append(carouselAcometida);
        $('#formSlideShow').modal('show');
    } else if (carousel == 2) {
        $('#formSlideShowBody').append(carouselMeasurer);
        $('#formSlideShow').modal('show');
    }
}



/**
 * METODO QUE SE ENCCARGA DE MOSTRAR EL MODAL DE LAS PLOMERIAS 
 * 
 * @param {type} url
 * @returns {undefined}
 */
function mostrarCaruselPlomeria(url)
{
    if(typeof url !=="undefined" && url != "")
    {
        var html = '';
        html += '<div id="myCarouselOP" class="carousel slide">';
            html+='<div class="carousel-inner">';
                        html +='<div class="item active">';
                                html +='<img src="'+ url +'" alt="comprobante" height="1024px" width="1248px" style="">';
                        html += '</div>';
                        
            html += '</div>'
        html +='</div>';
        
        html +='<a class="left carousel-control" href="#myCarouselOP" data-slide="prev">';
                html +='<span class="icon-prev"></span>';
        html +='</a>';
        html +='<a class="right carousel-control" href="#myCarouselOP" data-slide="next">';
                html +='<span class="icon-next"></span>';
        html +='</a>';
        
        $('#formSlideShowBody').html(html);
        $('#titleformSlideShow').html('Visualizador de Im&aacute;genes');
        $('#formSlideShow').modal('show');
    }
}

/*
 * Metodo que se encarga de recuperar el tipo de imagen a mostrar
 * 
 * @param {type} photo
 * @returns {getFileType.res}
 */
function  getFileType(photo)
{
    var res = photo.split("_");
    return res[0];
}