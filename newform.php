<?php include("header.php") ?>

<!-- Codigo -->
			<div class="row">
            <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Información del Formulario
                </header>
                <div class="panel-body">
                    <form class="form-horizontal tasi-form" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Titulo del Formulario</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Rol</label>
                            <div class="col-sm-10">
                                <select class="form-control m-b-10">
			                        <option>Cambaleo</option>
			                        <option>Plomero</option>
			                    </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label"></label>
                            <div class="col-sm-10">
								<button type="button" id="addField" class="btn btn-round btn-info"><i class="fa fa-plus"></i> Agregar Campos </button>
                            </div>
                        </div>     
                    </form>
                </div>
            </section>
            </div>
            </div>

			<div class="row">
            <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Detalle del Formulario
                </header>
				<div class="panel-body">                
                	<form class="form-horizontal tasi-form" id="createForm">
                    </form>
                </div>
             </section>
            </div>
            </div>





		<div class="modal fade" tabindex="-1" id="modalField" role="dialog">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Crear Campo</h4>
		      </div>
		      <div class="modal-body">
					<form class="cmxform" role="form" id="formNewForm">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Titulo de la Etiqueta</label>
                            <input type="text" id="txtTag" name="txtTag" class="form-control" placeholder="Nombre del Cliente">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <select class="form-control" id="txtType">
			                    <option value="text">Texto</option>
			                    <option value="numeric">Númerico</option>
			                    <option value="date">Fecha</option>
			                    <option value="photo">Fotografia</option>
			                    <option value="combo">Combo</option>
			                    <option value="truefalse">Verdadero / Falso</option>
			                </select>
                        </div>
                        <div class="form-group" id="formValues" style="display:none">
                            <label for="exampleInputPassword1">Opciones</label>
                            <input type="text" id="txtValue" name="txtValue" class="tags tags-input" data-type="tags" value=""/>
                        </div>
                    </form>



		      </div>
		      <div class="modal-footer">
		        <button type="button" id="addFieldData" class="btn btn-success">Agregar</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->



<?php include("footer.php") ?>

<!-- Abajo los js que incluimos -->

<!--tagsinput-->
<link href="assets/css/tagsinput.css" rel="stylesheet">

<!--tags input-->
<script src="assets/js/tags-input.js"></script>

<!--tags input init-->
<script src="assets/js/tags-input-init.js"></script>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">

    $(document).ready(function() {


    	$("#titleHeader").html("Nuevo Formulario")
		$("#subtitle-header").html("Detalle Nuevo  Formulario")    	


	    $('#addField').click(function() {
	    	  resetValidation()		
	          $('#modalField').modal('show')
	          cleanFields()
	    });

		$("#txtType").change(function() {

		  	if($("#txtType").val() == "combo")
		  	{
		  		$("#formValues").show()
		  	}
		  	else
		  	{
		  		$("#formValues").hide()
		  	}
		});

	    $("#addFieldData").click(function() {
	          var xtag = $("#txtTag").val()
	          var xvalue = $("#txtValue").val()
	          var xtype =$("#txtType").val();	
	          createField( xtag, xtype, xvalue)
	    });

	    function cleanFields(){

			   $("#txtTag").val('')
	           //$("#txtType").val('')
	           $("#txtValue").val('')
	           $("#txtType").val('text').change()
  					    	

	    }

	    function createField(tag, type, value)
	    {
	    	if($("#formNewForm").valid()){
	    	console.log(tag)
			console.log(type)
			console.log(value)

			var xparam = '';

			if(type == 'text')
			{
				xparam = '<input type="text" class="form-control">'
			}
			if(type == 'number')
			{
				xparam = '<input type="numeric" class="form-control">'
			}
			if(type == 'date')
			{
				xparam = '<input type="text" class="form-control date">'
			}
			if(type == 'photo')
			{
				xparam = '<input type="text" class="form-control">'
			}
			if(type == 'combo')
			{
				var xoption = '';
				var res = value.split(",");
				for (i = 0; i < res.length; i++) {
					xoption += '<option>'+res[i]+'</option>'				
				}

				xparam = '<select class="form-control" id="txtType">'
				xparam += xoption
				xparam += '</select>'
			}
			if(type == 'truefalse')
			{
				xparam = '<input type="text" class="form-control">'
			}														

			var xfield = ''
				xfield += '<div class="form-group">'
				xfield += '<label class="col-sm-2 col-sm-2 control-label">'+tag+'</label>'
				xfield += '<div class="col-sm-10">'
				xfield += xparam
				xfield += '</div>'
				xfield += '</div>'

			console.log(xfield);


			$("#createForm").append(xfield)
			$('#modalField').modal('hide')
			cleanFields()
	    	}
	    	
	    }

    });

    function resetValidation(){
            form_form.resetForm();
    }

    // validate signup form on keyup and submit
    var form_form = $("#formNewForm").validate({
            rules: {
                txtTag: {
                    required: true,
                    minlength: 4
                },
                txtValue: {
                    required: true,
                    email: true
                }
            },
            messages: {
                txtTag: {
                    required: "Es necesario este campo",
                    minlength: "El titulo debera ser mayor a tres caracteres"
                },
                txtMail: "Es necesario agregar opciones"
            }
        });


</script>


</script>